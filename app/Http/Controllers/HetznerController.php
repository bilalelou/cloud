<?php

namespace App\Http\Controllers;

use App\Models\DeliveryServer;
use App\Models\ServerProvider;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Storage;
use SSH;

class HetznerController extends Controller
{
    public function createHetznerIndex()
    {
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            if(auth()->user()->cannot("create_hetzner_server")) abort(403);
            
            $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "hetzner")->where("status", true)->get();
            return view("hetzner_servers.create_hetzner_server", compact("providers"));
        }
        else
        {
            return view("500");
        }
    }

    public function getHetznerOptions()
    {
        $api_key = ServerProvider::where("is_cloud", true)->where("cloud_type", "hetzner")->where("status", true)->first()->cloud_api_key;

        /////////////////////////regions////////////////////////////
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/locations");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '. $api_key,
        ]);
        $regions = json_decode(curl_exec($ch), true);

        /////////////////////////images////////////////////////////

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/images");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '. $api_key,
        ]);
        $results = json_decode(curl_exec($ch), true);
        $images = [];
        for($i = 1; $i <= $results["meta"]["pagination"]["last_page"]; $i++)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/images?page=$i");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer '. $api_key,
            ]);
            $response = curl_exec($ch);
            $temp = json_decode($response, true);
            foreach($temp["images"] as $image)
            {
                array_push($images, $image);
            }
        }
        
        /////////////////////////types////////////////////////////

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/server_types");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '. $api_key,
        ]);
        $sizes = json_decode(curl_exec($ch), true);


        return response()->json([
            'success' => true,
            'regions' => $regions,
            'sizes' => $sizes,
            'images' => $images
        ]);
    }

    public function createHetznerServer(Request $request)
    {
        set_time_limit(180);
        $successfull_hetzners = [];

        foreach($request["filledRowsData"] as $data)
        {
            $provider = ServerProvider::find($data["provider_id"]);

            for ($i = 1; $i <= $data["numberOfServers"]; $i++) 
            {
                try
                {
                    $rand = rand(0, 9999) . rand(0, 9999);
                    $name = 'hetzner-' . $data["size"] . '-' . $rand;
                    $createUrl = 'https://api.hetzner.cloud/v1/servers';

                    $createData = [
                        'name' => $name,
                        'server_type'=> $data["size"],
                        'location' => $data["region"], 
                        'image' => $data["image"],
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $createUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($createData));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $provider->cloud_api_key,
                        'Content-Type: application/json',
                    ]);

                    $response = curl_exec($ch);
                    $response = json_decode($response, true);

                    if (array_key_exists('error', $response))
                    {
                        return response()->json([
                            'success' => false, 
                            'message' => $response['error']['message']
                        ]);
                    }
                    else
                    {
                        $hetzner = new DeliveryServer();
                        $hetzner->serverprovider_id = $provider->id;
                        $hetzner->name = $response["server"]["name"];
                        $hetzner->main_ip = $response["server"]["public_net"]["ipv4"]["ip"];

                        $hetzner->cloud_id = $response["server"]["id"];
                        $hetzner->ssh_auth_type = "plain_password";
                        $hetzner->interval = "both";
                        $hetzner->is_proxy = false;

                        $hetzner->type = $response["server"]["server_type"]["name"];
                        $hetzner->geo = $response["server"]["datacenter"]["location"]["city"];
                        $hetzner->main_domain = DeliveryServer::generateRandomDomain();

                        $hetzner->os_installed = $response["server"]["image"]["name"];
                        $hetzner->status = $response["server"]["status"];
                        $hetzner->ssh_user = "root";
                        $hetzner->ssh_password = $response["root_password"];
                        $hetzner->ssh_key_content = null;
                        $hetzner->ssh_port = 22;

                        $hetzner->type = "cloud";
                        $hetzner->Installation_method = "lite";        

                        $hetzner->save();

                        array_push($successfull_hetzners, $hetzner->id);
                    }
                }
                catch (\Exception $e)
                {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Servers created successfully",
            'hetznerIds' => $successfull_hetzners,
            'numberOfServers' => $data["numberOfServers"]
        ]);
    }

    public function changePassword($cloud_id, $api_key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/servers/" . $cloud_id . "/actions/reset_password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        return $response["root_password"];
    }

    public function getHetznerStatus(Request $request)
    {
        set_time_limit(180);
        $running_servers = [];
        $start = microtime(true);
        $timeout = 900;


        while(count($running_servers) < count($request->hetznerIds))
        {
            if (microtime(true) - $start > $timeout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timeout: The operation exceeded the allowed time.',
                ]);
            }

            foreach($request->hetznerIds as $hetznerId)
            {
                try 
                {
                    $hetzner = DeliveryServer::find($hetznerId);
                    $provider = $hetzner->serverprovider;
                    $getUrl = "https://api.hetzner.cloud/v1/servers/{$hetzner->cloud_id}";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $getUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $provider->cloud_api_key,
                        'Content-Type: application/json',
                    ]);

                    $response = curl_exec($ch);
                    $response = json_decode($response, true);

                    if (array_key_exists('error', $response))
                    {
                        return response()->json(['message' => $response['error']['message']]);
                    }
                    else
                    {
                        if ($response["server"]["status"] == "running" && !in_array($hetzner->id, $running_servers))
                        {

                            sleep(40);
                            $password = $this->changePassword($hetzner->cloud_id, $provider->cloud_api_key);
                            $hetzner->ssh_password = $password;
                            $hetzner->status = "saved";
                            $hetzner->save();

                            array_push($running_servers, $hetzner->id);
                        }
                    }

                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }
            }
        }

        $servers = DeliveryServer::whereIn('id', $running_servers)->get();

        return response()->json([
            'success' => true,
            'message' => "(" .count($running_servers) . "/" . count($request->hetznerIds) . ") Servers are Running ",
            'hetznerIds' => $running_servers,
            'servers' => $servers
        ]);
    }
}
