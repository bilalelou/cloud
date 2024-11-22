<?php

namespace App\Http\Controllers;

use App\Models\DeliveryServer;
use App\Models\ServerProvider;
use App\Models\TempDeliveryServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class KamateraController extends Controller
{
    public function createKamateraIndex()
    {
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            if(auth()->user()->cannot("create_kamatera_server")) abort(403);
            
            $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "kamatera")->where("status", true)->get();
            return view("kamatera_servers.create_kamatera_server", compact("providers"));
        }
        else
        {
            return view("500");
        }
    }

    public function authenticate()
    {
        $o_auth = json_decode(ServerProvider::where("cloud_type" , "kamatera")->where("status", true)->first()->o_auth, true);

        $res = Http::post('https://console.kamatera.com/service/authenticate', [
            'clientId' => $o_auth["client_id"],
            'secret' => $o_auth["client_secret"]
        ])
        ->json()["authentication"];
        
        return $res;
    }

    public function getKamateraOptions(Request $request)
    {
        ini_set('max_execution_time', 300);

        $api_key = $this->authenticate();

        $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "kamatera")->where("status", true)->get();

        $server_options = Http::withHeaders(['Authorization' => 'Bearer ' . $api_key, 'Content-Type' => 'application/json'])->timeout(180)->get('https://console.kamatera.com/service/server')->json();

        return response()->json(["success" => true, "server_options" => $server_options, "providers" => $providers]);
    }

    public function getServerInfo($name)
    {
        $start_time = microtime(true);
        $api_key = $this->authenticate();

        if (microtime(true) - $start_time > 180) return response()->json(['error' => 'Timeout'], 500);

        try 
        {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ])->get('https://console.kamatera.com/service/servers')->json();
            
            if (!empty($response)) 
            {
                $names = array_column($response, 'name');
                
                if (!in_array($name, $names)) 
                {
                    sleep(10);
                    return $this->getServerInfo($name);
                } 
                else 
                {
                    foreach ($response as $server) 
                    {
                        if ($server["name"] == $name) 
                        {
                            return Http::withHeaders([
                                'Authorization' => 'Bearer ' . $api_key,
                                'Content-Type' => 'application/json'
                            ])->get('https://console.kamatera.com/service/server/'.$server["id"])
                            ->json();
                        }
                    }
                }
            } 
            else 
            {
                sleep(10);
                return $this->getServerInfo($name);
            }
        } 
        catch (\Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function createKamatera(Request $request)
    {
        ini_set('max_execution_time', 500);
        $api_key = $this->authenticate();
        $password = DB::table('app_settings')->value('default_password');
        $successfull_kamateras = [];

        foreach($request["filledRowsData"] as $data)
        {
            $provider = ServerProvider::find($data["provider_id"]);

            for ($i = 1; $i <= $data["numberOfServers"]; $i++) 
            {
                try
                {
                    $rand = rand(0, 9999) . rand(0, 9999);
                    $name = 'kamatera-' . $data["cpu"] . '-' . $rand;
                    
                    $response = Http::withHeaders(['Authorization' => 'Bearer ' . $api_key, 'Content-Type' => 'application/json'])
                                    ->post('https://console.kamatera.com/service/server', [
                                        "disk_src_0" => $data["image"],
                                        "datacenter" => $data["region"],
                                        "name" => $name,
                                        "cpu" => $data["cpu"],
                                        "ram" => (int)$data["ram"],
                                        "password" => $password,
                                        "power" => true,
                                        "disk_size_0" => 10,
                                        "billing" => "hourly",
                                        "network_name_0" => "wan",
                                    ])->json();

                    if (array_key_exists('errors', $response))
                    {
                        return response()->json([
                            'success' => false, 
                            'message' => "Error: " . $response['errors'][0]['info']
                        ]);
                    }
                    else
                    {
                        $server = $this->getServerInfo($name);
                        $kamatera = new TempDeliveryServer();

                        switch($data["image"])
                        {
                            case $data["region"] . ":6000C292e748d49a20772ccff041a175":
                                $os_installed = "almalinux8";
                                break;
                            case $data["region"] . ":6000C2943fa543906349589fc6005f6a":
                                $os_installed = "centos7";
                                break;
                            case $data["region"] . ":6000C29c13379e120fa255303ccee130":
                                $os_installed = "centos8";
                                break;
                            case $data["region"] . ":6000C297540c5498b5d519c77fcfe736":
                                $os_installed = "ubuntu20";
                                break;
                            default:
                                $os_installed = "unknown";
                                break;
                        }

                        $kamatera->os_installed = $os_installed;
                        $kamatera->serverprovider_id = $provider->id;
                        $kamatera->main_ip = $server['networks'][0]['ips'][0];
                        $kamatera->cloud_id = $server['id'];
                        $kamatera->name = $server['name'];
                        $kamatera->status = $server['power'] == "on" ? "saved" : "inactive";
                        $kamatera->save();
                        
                        array_push($successfull_kamateras, $kamatera->id);
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

        $servers = TempDeliveryServer::find($successfull_kamateras);

        return response()->json([
            "success" => true, 
            "message" => "Created " . count($successfull_kamateras) . "/" . count($request["filledRowsData"]) . " servers",
            "servers" => $servers,
            "kamateraIds" => $successfull_kamateras,
            "numberOfServers" => $data["numberOfServers"]
        ]);
    }

    public function storeKamatera(Request $request)
    {
        $servers = TempDeliveryServer::find(explode(",", $request->ids));
        $password = DB::table('app_settings')->value('default_password');
        $api_key = $this->authenticate();

        $stored_clean = [];

        foreach($servers as $server)
        {
            $provider = ServerProvider::find($server->serverprovider_id);

            if($server->spamhaus === true)
            {
                $url = "https://console.kamatera.com/service/server/{$server->cloud_id}/terminate";
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $api_key,
                ])->delete($url);
                    
                if ($response->successful())
                {
                    $server->delete();
                }
            }
            else
            {
                $response = Http::withHeaders(['Authorization' => 'Bearer ' . $api_key, 'Content-Type' => 'application/json'])->get("https://console.kamatera.com/service/server/{$server->cloud_id}")->json();

                $kamatera = new DeliveryServer();

                $kamatera->serverprovider_id = $provider->id;
                $kamatera->name = $response['name'];
                $kamatera->main_ip = $response['networks'][0]['ips'][0];

                $kamatera->cloud_id = $response['id'];
                $kamatera->ssh_auth_type = "plain_password";
                $kamatera->interval = "both";
                $kamatera->is_proxy = false;

                $kamatera->type = $response['cpu'] . "-" . $response['ram'] . "GB";

                switch($response['datacenter'])
                {
                    case 'AS': $geo = 'China'; break;
                    case 'CA-TR': $geo = 'Canada'; break;
                    case 'EU': $geo = 'The Netherlands'; break;
                    case 'EU-FR': $geo = 'Germany'; break;
                    case 'EU-LO': $geo = 'United Kingdom'; break;
                    case 'EU-MD': $geo = 'Spain'; break;
                    case 'EU-ML': $geo = 'Italy'; break;
                    case 'EU-ST': $geo = 'Sweden'; break;
                    case 'IL': $geo = 'Israel'; break;
                    case 'IL-HA': $geo = 'Israel'; break;
                    case 'IL-PT': $geo = 'Israel'; break;
                    case 'IL-RH': $geo = 'Israel'; break;
                    case 'IL-TA': $geo = 'Israel'; break;
                    case 'US-AT': $geo = 'United States'; break;
                    case 'US-CH': $geo = 'United States'; break;
                    case 'US-LA': $geo = 'United States'; break;
                    case 'US-MI': $geo = 'United States'; break;
                    case 'US-NY2': $geo = 'United States'; break;
                    case 'US-SC':$geo = 'United States';break;
                    case 'US-SE': $geo = 'US-SE'; break;
                    case 'US-TX':  $geo = 'United States'; break;
                }

                $kamatera->geo = $geo;
                $kamatera->main_domain = DeliveryServer::generateRandomDomain();
                $kamatera->os_installed = $server->os_installed;
                
                $kamatera->status = "saved";
                $kamatera->ssh_user = "root";
                $kamatera->ssh_password = $password;
                $kamatera->ssh_key_content = null;
                $kamatera->ssh_port = 22;

                $kamatera->type = "cloud";
                $kamatera->Installation_method = "lite";

                $kamatera->save();

                array_push($stored_clean, $kamatera->id);
                
                $server->delete();
            }
        }

        $servers = DeliveryServer::find($stored_clean);

        return response()->json([
            "success" => true,
            "message" => "Servers created successfully",
            "kamateraIds" => $stored_clean,
            "servers" => $servers
        ]);
    }
}
