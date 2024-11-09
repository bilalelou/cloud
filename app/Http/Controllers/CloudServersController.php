<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryServer;
use App\Models\ServerProvider;
use Config;
use Illuminate\Support\Facades\Auth;
use SSH;
use App\Http\Controllers\Traits\TraitsUser;
use App\Http\Controllers\domaincheckecontroller;
use App\Models\Deliveryserverinterface;
use App\Models\Lead;
use App\Models\ServerAccess;
use Illuminate\Support\Facades\Http;

class CloudServersController extends Controller
{
    use TraitsUser;

    ////////////////////////////////DigitalOcean//////////////////////////////
    function index(Request $request)
    {
        if(Auth::check()) $check = true;

        else $check = $this->TraitsUser($request->email,$request->password,$request); 

        if($check == true)
        {
            if(auth()->user()->cannot("see_servers")) abort(403);

            $check_domain = (new domaincheckecontroller)->envoidomain();

            if($check_domain == "true")
            {
                $servers = DeliveryServer::whereNotNull("cloud_id")
                                        ->join("serverproviders", "serverproviders.id", "deliveryservers.serverprovider_id")
                                        ->select("deliveryservers.*", "serverproviders.cloud_type")
                                        ->get();

                return view("cloud_servers.list_cloud_servers", compact("servers"));
            }
            else
            {
                return view("500");
            }
        }
    }

    function createCloudServersIndex(Request $request)
    {
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            if(auth()->user()->cannot("create_digitalocean_server")) abort(403);

            $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "digitalocean")->where("status", true)->get();

            return view("cloud_servers.create_cloud_servers", compact("providers"));
        }
        else
        {
            return view("500");
        }
    }

    function createCloudServers(Request $request)
    {   
        set_time_limit(180);

        if(auth()->user()->cannot("create_cloud_server")) abort(403);
        $sum = floor(array_sum(array_map(function($row){return $row["numberOfServers"];}, $request->filledRowsData)));
        if($sum > 10) return response()->json(["success" => false, "msg" => "You can't create more than 10 servers"]);
        if($sum < 0) return response()->json(["success" => false, "msg" => "please provide a valid number"]);
        
        $servers_id = [];
        foreach($request->filledRowsData as $row)
        {
            $provider = ServerProvider::find($row["provider_id"]);

            $api_key = $provider->cloud_api_key;
            $url = "https://api.digitalocean.com/v2/droplets"; // hna nzido linode url too

            // set the field name in $fields
            $names = '[';
            for($i = 0; $i < $row["numberOfServers"]; $i++)
            {
                $names .= '"' . $row["size"] . '-' . $i . '",'; // name ybqa nfsso
            }
            $names = substr($names, 0, -1);
            $names .= ']';

            //fingerprint
            $ssh = $provider->id_rsa_fingerprint;

            // fields
            $fields = '{"names":' . $names . ',
                "region":"' . $row["region"] . '",
                "size":"' . $row["size"] . '",
                "image":"' . $row["image"] . '",
                "ssh_keys": ["' . $ssh . '"]
            }';
            
            // curl command
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer '. $api_key,]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            
            // get results of adding new droplets
            $response = curl_exec($ch);
            $results = json_decode($response, true);

            if(array_key_exists("id", $results)) return response()->json(["success" => false, "msg" => $results["message"]]);

            sleep(10);
            
            // saving droplets to DB
            foreach($results["droplets"] as $droplet)
            {
                try
                {
                    $server = new DeliveryServer();

                    $server->cloud_id = $droplet["id"];
                    $server->serverprovider_id = $provider->id;
                    $server->name = "EMC_SR". $droplet["id"];
                    $server->type = $droplet["size_slug"];
                    $server->main_ip = '0.0.0.0';
                    $server->ssh_auth_type = "plain_password";
                    $server->interval = "both";
                    $server->is_proxy = false;

                    if($droplet["status"] == "new")
                    {
                        $server->status = "new";
                    }
                    elseif($droplet["status"] == "active")
                    {
                        $server->status = "saved";
                        $server->main_ip = $droplet["status"]["v4"][0]["ip_address"];
                    }

                    $server->main_domain = DeliveryServer::generateRandomDomain();

                    if($droplet["image"]["distribution"] ." ". $droplet["image"]["name"] == "CentOS 7 x64")
                    {
                        $server->os_installed = "centos7";
                    }
                    else
                    {
                        $server->os_installed = $droplet["image"]["distribution"] ." ". $droplet["image"]["name"];
                    }

                    $server->geo = $droplet["region"]["name"];

                    $server->ssh_user = "root";
                    $server->ssh_password = null;
                    $server->ssh_key_content = null;
                    $server->ssh_port = 22;

                    $server->save();

                    array_push($servers_id, $server->id);
                }
                catch(\Exception $e)
                {
                    info('Error: ' . $e);
                }
            }
        }

       return response()->json(["success" => true, "msg" => count($servers_id)." cloud server(s) created successfully", "servers_id" => $servers_id]);
    }

    function checkServer(Request $request)
    {
        set_time_limit(120);

        $loop = 0;
        $iterations = 50;
        $success_droplets = [];
        $servers_ids = $request->servers_id;

        while($loop < count($servers_ids))                                                                                                                                                               
        {
            foreach($servers_ids as $server_id)
            {
                $server = DeliveryServer::find($server_id);
                $provider = $server->serverprovider;
                $api_key = $provider->cloud_api_key;
                
                $droplet_url = "https://api.digitalocean.com/v2/droplets/".$server->cloud_id;
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $droplet_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Authorization: Bearer ". $api_key,
                ]);
                
                $response = curl_exec($ch);
                $result = json_decode($response, true);
                
                
                if(array_key_exists("id", $result)) continue;
                
                if($result["droplet"]["status"] == "active" && !in_array($server_id, $success_droplets)) 
                {
                    $server->main_ip = $result["droplet"]["networks"]["v4"][0]["ip_address"];
                    $server->save();
                    
                    $res = DeliveryServer::initializeCloudServers($server_id);
                    
                    if($res == true) {
                        array_push($success_droplets, $server_id);
                        $loop++;
                    }
                }
            }

            $iterations--;
            if($iterations == 0) return response()->json(["success" => false, "msg" => "Time limit !!!!"]);
            
            $servers = DeliveryServer::whereIn("id", $servers_ids)->get();
        }

        return response()->json(["success" => true, "msg" => " Cloudserver(s) initialized successfully", "success_droplets" => $success_droplets, "servers" => $servers]);
    }

    function deleteServers(Request $request)
    {
        if(auth()->user()->cannot("delete_cloud_server")) abort(403);
        
        $servers_id = explode(",", $request->servers_id);

        try
        {
            foreach($servers_id as $server_id)
            {
                $server = DeliveryServer::find($server_id);
                $provider = $server->serverprovider;
                $url = "";
                $arr = [];
                $token = "";

                switch ($provider->cloud_type) 
                {
                    case "digitalocean":
                        $url = "https://api.digitalocean.com/v2/droplets/" . $server->cloud_id;
                        $token = $provider->cloud_api_key; 
                        break;
                    case "linode":
                        $url = "https://api.linode.com/v4/linode/instances/" . $server->cloud_id;
                        $token = $provider->cloud_api_key; 
                        break;
                    case "hetzner":
                        $url = "https://api.hetzner.cloud/v1/servers/" . $server->cloud_id;
                        $token = $provider->cloud_api_key;
                        break;
                    case "kamatera":
                        $url = "https://console.kamatera.com/service/server/{$server->cloud_id}/terminate";
                        $token = KamateraController::authenticate();
                        $arr = ["confirm" => 1, "force" => 1];
                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $token,
                        ])->put("https://console.kamatera.com/service/server/{$server->cloud_id}/power", ["power" => "off"]);
                        break;
                    case "azure":
                        $o_auth = json_decode($provider->o_auth, true);
                        $token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);
                        $subscriptionId = json_decode($server->vm_info, true)["subscription_id"];
                        $resourceGroupName = json_decode($server->vm_info, true)["resource_group_name"];
                        $url = "https://management.azure.com/subscriptions/".$subscriptionId."/resourcegroups/".$resourceGroupName."?api-version=2021-04-01";
                        break;
                }
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '. $token,
                ])->delete($url, $arr);

                if ($response->successful()) 
                {
                    $server->status = "returned";
                    $server->save();
                } 
                else 
                {
                    return response()->json(['success' => false, 'msg' => 'Failed to delete server']);
                }
            }
        }
        catch(\Exception $e)
        {
            info($e);
            return response()->json(["success" => false, "msg" => $e]);
        }

        return response()->json(["success" => true, "msg" => "server(s) has been deleted successfully"]);
    }

    function reinstallServers(Request $request)
    {
        set_time_limit(380);

        $server_ids = explode(",", $request->servers_ids);
        
        $results = Http::post("deliverability." . env('domain2') . "/api/rienstallServerApi", [
            'id' => $server_ids,
            'pmta' => $request->pmta,
            "recordVersion" => "off"
        ]);
        
        $results = json_decode($results, true);
        
        foreach($results as $result)
        {
            if(!str_contains($result["msg"], "reinstalled successfully"))
            {
                $server = DeliveryServer::find($result["id"]);
                $server->status = "inactive";
                $server->save();
            }
        }

        return response()->json(["success" => true, "results" => $results]);
    }

}
