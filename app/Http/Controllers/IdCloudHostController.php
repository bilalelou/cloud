<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Valuestore\Valuestore;



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
      $url = 'https://api.idcloudhost.com/v1/jkt03/user-resource/vm';
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
        // "jkt01",
        "jkt03",
        // "sgp01t",
        // "jkt02",
    ];
    
    $allIps = [];
    $responses = [];
    
    foreach ($urls as $url) {
        $allUuids = [];
    
        try {
            $VmResponse = Http::withHeaders([
                "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
            ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/{$url}/user-resource/vm/list");
    
            $data = $VmResponse->json();
    
            if (!is_array($data)) {
                throw new \Exception("Invalid response structure for URL: {$url}");
            }
    
            foreach ($data as $item) {
                if (isset($item['uuid'])) {
                    $allUuids[] = $item['uuid'];
                }
            }
    
            info("Extracted UUIDs from URL: {$url}, UUIDs: " . implode(", ", $allUuids));
    
            $responsesIps = [];
            foreach ($allUuids as $uuid) {
                try {
                    $apiResponse = Http::withHeaders([
                        "apikey" => "xcaL575O7OVVIhERPtPz2mcjDZhvnMpn",
                    ])->withoutVerifying()->post("https://api.idcloudhost.com/v1/{$url}/user-resource/vm/ip/public", [
                        "uuid" => $uuid,
                    ]);
    
                    $ipData = $apiResponse->json();
    
                    $responsesIps[] = [
                        "uuid" => $uuid,
                        "data" => $ipData,
                        "success" => true,
                    ];
    
                    if (isset($ipData['public_ipv4'])) {
                        $allIps[] = $ipData['public_ipv4'];
                    }
    
                    info("Processed UUID {$uuid}, IP: " . ($ipData['public_ipv4'] ?? 'N/A'));
                } catch (\Exception $e) {
                    $responsesIps[] = [
                        "uuid" => $uuid,
                        "success" => false,
                        "error" => $e->getMessage(),
                    ];
    
                    info("Error processing UUID: {$uuid}, Error: " . $e->getMessage());
                }
            }
    
            $responses[] = [
                "url" => $url,
                "success" => true,
                "data" => $data,
                "responsesIps" => $responsesIps,
            ];
        } catch (\Exception $e) {
            info("Error processing URL: {$url}, Error: " . $e->getMessage());
    
            $responses[] = [
                "url" => $url,
                "success" => false,
                "msg" => $e->getMessage(),
            ];
        }
    }
    

    return response()->json([
        "success" => true,
        "responses" => $responses,
        "allIps" => $allIps, 
    ]);

}
}


