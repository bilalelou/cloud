<?php

namespace App\Http\Controllers;

use App\Models\DeliveryServer;
use App\Models\ServerProvider;
use App\Models\TempDeliveryServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LinodeController extends Controller
{
    public function createLinodeIndex()
    {
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            if(auth()->user()->cannot("create_linode_server")) abort(403);
            
            $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "linode")->where("status", true)->get();
            return view("linode_servers.create_linode_server", compact("providers"));
        }
        else
        {
            return view("500");
        }
    }

    public function getLinodeOptions()
    {
        $api_key = ServerProvider::where("is_cloud", true)->where("cloud_type", "linode")->where("status", true)->first()->cloud_api_key;

        /////////////////////////regions////////////////////////////
        
        $url = 'https://api.linode.com/v4/regions';
        $ch_regions = curl_init();
        curl_setopt($ch_regions, CURLOPT_URL, $url);
        curl_setopt($ch_regions, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_regions, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);

        $response1 = curl_exec($ch_regions);
        $regions = json_decode($response1, true);

        /////////////////////////images////////////////////////////

        $url = 'https://api.linode.com/v4/images';
        $ch_sizes = curl_init();
        curl_setopt($ch_sizes, CURLOPT_URL, $url);
        curl_setopt($ch_sizes, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_sizes, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);

        $response2 = curl_exec($ch_sizes);
        $images = json_decode($response2, true);

        /////////////////////////types////////////////////////////

        $url = 'https://api.linode.com/v4/linode/types';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);

        $response3 = curl_exec($ch);
        $sizes = json_decode($response3, true);


        return response()->json([
            'success' => true,
            'regions' => $regions,
            'sizes' => $sizes,
            'images' => $images
        ]);
    }

    public function createLinode(Request $request)
    {
        set_time_limit(180);
        $successfull_linodes = [];
        $password = DB::table('app_settings')->value('default_password');
        
        foreach($request["filledRowsData"] as $data)
        {
            $provider = ServerProvider::find($data["provider_id"]);

            for ($i = 1; $i <= $data["numberOfServers"]; $i++) 
            {
                try
                {
                    $rand = rand(0, 9999) . rand(0, 9999);
                    $label = 'linode-' . $data["size"] . '-' . $rand;

                    $createUrl = 'https://api.linode.com/v4/linode/instances';

                    $createData = [
                        'type' => $data["size"],
                        'region' => $data["region"], 
                        'image' => $data["image"],
                        'root_pass' => $password,
                        'label' => $label,
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

                    if (array_key_exists('errors', $response))
                    {
                        return response()->json([
                            'success' => false, 
                            'message' => $response['errors'][0]['reason']
                        ]);
                    }
                    else
                    {
                        $linode = new TempDeliveryServer();
                        $linode->serverprovider_id = $provider->id;
                        $linode->main_ip = $response['ipv4'][0];
                        $linode->cloud_id = $response['id'];
                        $linode->name = $response['label'];
                        $linode->os_installed = substr($response['image'] , strpos($response['image'] , '/') + 1);
                        $linode->status = $response['status'];
                        $linode->save();
                        
                        array_push($successfull_linodes, $linode->id);
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
        
        $servers = TempDeliveryServer::find($successfull_linodes);

        return response()->json([
            "success" => true,
            "message" => "Servers created successfully",
            "linodeIds" => $successfull_linodes,
            "servers" => $servers,
            'numberOfServers' => $data["numberOfServers"]
        ]);
    }

    public function storeLinode(Request $request)
    {
        
        $servers = TempDeliveryServer::find(explode(",", $request->ids));
        $password = DB::table('app_settings')->value('default_password');

        $stored_clean = [];

        foreach($servers as $server)
        {
            $provider = ServerProvider::find($server->serverprovider_id);

            if($server->spamhaus === true)
            {
                $url = "https://api.linode.com/v4/linode/instances/".$server->cloud_id;
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $provider->cloud_api_key,
                ])->delete($url);
                    
                if ($response->successful())
                {
                    $server->delete();
                }
            }
            else
            {
                do
                {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '. $provider->cloud_api_key,
                    ])->get('https://api.linode.com/v4/linode/instances/'.$server->cloud_id)->json();

                }
                while($response['status'] != "running");

                $linode = new DeliveryServer();
                $linode->serverprovider_id = $provider->id;
                $linode->name = $response['label'];
                $linode->main_ip = $response['ipv4'][0];

                $linode->cloud_id = $response['id'];
                $linode->ssh_auth_type = "plain_password";
                $linode->interval = "both";
                $linode->is_proxy = false;

                $linode->type = $response['type'];
                $linode->geo = $response['region'];
                $linode->main_domain = DeliveryServer::generateRandomDomain();
                $linode->os_installed = substr($response['image'] , strpos($response['image'] , '/') + 1);
                
                $linode->status = "saved";
                $linode->ssh_user = "root";
                $linode->ssh_password = $password;
                $linode->ssh_key_content = null;
                $linode->ssh_port = 22;

                $linode->save();

                array_push($stored_clean, $linode->id);
                
                $server->delete();
            }
        }

        $servers = DeliveryServer::find($stored_clean);

        return response()->json([
            "success" => true,
            "message" => "Servers created successfully",
            "linodeIds" => $stored_clean,
            "servers" => $servers
        ]);
    }

    // public function getLinodeStatus(Request $request)
        // {
        //     set_time_limit(180);
        //     $running_servers = [];
        //     $start = microtime(true);
        //     $timeout = 900; // 15 minutes yla majash response twqef

        //     while(count($running_servers) < count($request->linodeIds))
        //     {
        //         if (microtime(true) - $start > $timeout) {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => 'Timeout: The operation exceeded the allowed time.',
        //             ]);
        //         }

        //         foreach($request->linodeIds as $linodeId)
        //         {
        //             try 
        //             {
        //                 $linode = DeliveryServer::find($linodeId);
        //                 $provider = $linode->serverprovider;
        //                 $getUrl = "https://api.linode.com/v4/linode/instances/{$linode->cloud_id}";

        //                 $ch = curl_init();
        //                 curl_setopt($ch, CURLOPT_URL, $getUrl);
        //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //                 curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //                     'Authorization: Bearer ' . $provider->cloud_api_key,
        //                     'Content-Type: application/json',
        //                 ]);

        //                 $response = curl_exec($ch);
        //                 $response = json_decode($response, true);

        //                 if (array_key_exists('errors', $response))
        //                 {
        //                     return response()->json(['message' => $response['errors'][0]['reason']]);
        //                 }
        //                 else
        //                 {
                            
        //                     if ($response['status'] == "running" && !in_array($linode->id, $running_servers))
        //                     {
        //                         $linode->status = "saved";
        //                         $linode->save();
        //                         array_push($running_servers, $linodeId);
        //                     }
        //                 }

        //             } catch (\Exception $e) {
        //                 return response()->json(['success' => false, 'message' => $e->getMessage()]);
        //             }
        //         }
        //     }

        //     $servers = DeliveryServer::whereIn('id', $running_servers)->get();

        //     return response()->json([
        //         'success' => true,
        //         'message' => "(" .count($running_servers) . "/" . count($request->linodeIds) . ") Servers are Running ",
        //         'linodeIds' => $running_servers,
        //         'servers' => $servers
        //     ]);
        // }
}
 