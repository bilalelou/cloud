<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Valuestore\Valuestore;
use App\Models\TempDeliveryServer;
use App\Models\DeliveryServer;




class IdCloudHostController extends Controller
{
    public function createIdCloudHostIndex()
    {
        // return view('idcloudhost_servers.create_idcloudhost_server', compact('regions', 'osSystems'));
        return view('idcloudhost_servers.create_idcloudhost_server');
    }

    public function storeIdCloudHost(Request $request)
    {
        // Define the API endpoint and the headers
        $url = 'https://api.idcloudhost.com/v1/jkt01/user-resource/vm';
        $apiKey = 'xcaL575O7OVVIhERPtPz2mcjDZhvnMpn';

        $payload = [
            'name' => "senhaji",
            "os_name" =>$request->system["0"]["name"],
            // "os_name" => "centos", // hadi static 7aliyan !!!
            "os_version" =>$request->system["0"]["version"],
            'disks' => $request->config["disk"],
            "vcpu" => $request->config["cpu"],
            "ram" => $request->config["ram"],
            // "ram" => 2048, // hadi static 7aliyan !!!
            // "designated_pool_uuid" => "TODO",
            "username" => "root",
            "password" => "dfuhb??fuh!bAAAfvujh188_7487",
            "billing_account_id" => "1200242373",
            // "network_uuid" => "TODO",
            // "cloud_init" => "TODO",
        ];

        // info($request);
        // info($request->system);
        info($payload);

        try
        {
            $response = Http::withHeaders([
                "apikey" => $apiKey
            ])->withoutVerifying()->post($url, $payload);
            info($response);
        }
        catch(Exception $e)
        {
            return response()->json([
                'success' => true,
                'data' => "Error - API",
            ]);
        }      

    }

    public function getRegionsAndSystems()
    {
        try
        {
            $regionsResponse = Http::withHeaders([
                "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
            ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/locations");
            $regions = $regionsResponse->json();

            $systemsResponse = Http::withHeaders([
                "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
            ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/vm_images/plain_os");

            $systems = $systemsResponse->json();

            return  response()->json([
                "success" => true,
                "regions" => $regions,
                "systems" => $systems,
            ]);
        }
        catch(Exception $e)
        {
            info($e);

            return  response()->json([
                "success" => false,
                "msg" => "Fail - 605",
            ]);
            info($response);
        }
    }

    public function getVmList(Request $request)
    {
        $urls = [
            "jkt01",
            "jkt03",
            "sgp01t",
            "jkt02",
        ];

        $billing_account_id = "1200242373";
        $infoVms = [];
        $tempServersIds = [];

        foreach ($urls as $url)
        {
            try
            {
                $VmResponseIp = Http::withHeaders([
                    "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
                ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/{$url}/network/ip_addresses?billing_account_id={$billing_account_id}");

                $VmResponseData = Http::withHeaders([
                    "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
                ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/{$url}/user-resource/vm/list");

                if ($VmResponseIp->successful() && $VmResponseData->successful())
                {
                    $responseIps = $VmResponseIp->json();
                    $responseData = $VmResponseData->json();

                    foreach ($responseData as $key => $data)
                    {
                        $infoVm = [
                            'os' => $data['os_name'] . " " . $data['os_version'],
                            'status' => $data['status'] === 'running' ? 'active' : 'inactive',
                            'ip' => $responseIps[$key]['address'] ?? null,
                        ];

                        $infoVms[] = $infoVm;

                        $tempServer = new TempDeliveryServer();
                        $tempServer->serverprovider_id = "1"; // Adjust as needed
                        $tempServer->main_ip = $infoVm['ip'];
                        $tempServer->cloud_id = "1";
                        $tempServer->name = "root";
                        $tempServer->os_installed = $infoVm['os'];
                        $tempServer->status = $infoVm['status'];
                        $tempServer->save();

                        $tempServersIds[] = $tempServer->id;
                    }
                }
            }
            catch (\Exception $e)
            {
                info("Error processing URL: {$url}, Message: " . $e->getMessage());

                continue;
            }
        }

        $servers = TempDeliveryServer::whereIn("id", $tempServersIds)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed successfully',
            'servers' => $servers,
        ]);
    }

    public function storeServers(Request $request){
        $servers = TempDeliveryServer::find(explode(",", $request->ids));

        $IdClousHost = new DeliveryServer();
        $IdClousHost->serverprovider_id = $provider->id;
        $IdClousHost->name = $response['label'];
        $IdClousHost->main_ip = $response['ipv4'][0];
        $IdClousHost->cloud_id = $response['id'];
        $IdClousHost->ssh_auth_type = "plain_password";
        $IdClousHost->interval = "both";
        $IdClousHost->is_proxy = false;
        $IdClousHost->type = $response['type'];
        $IdClousHost->geo = $response['region'];
        $IdClousHost->main_domain = DeliveryServer::generateRandomDomain();
        $IdClousHost->os_installed = "";
        $IdClousHost->status = "saved";
        $IdClousHost->ssh_user = "root";
        $IdClousHost->ssh_password = $password;
        $IdClousHost->ssh_key_content = null;
        $IdClousHost->ssh_port = 22;
        $IdClousHost->type = "cloud";
        $IdClousHost->Installation_method = "lite";
        $IdClousHost->save();


    }
}

