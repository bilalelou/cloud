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
      $apiKey = '5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96';

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
         "billing_account_id" => "1200241687",
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
            "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
         ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/locations");
         $regions = $regionsResponse->json();

         $systemsResponse = Http::withHeaders([
            "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
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
     
     $responses = [];
     foreach ($urls as $url) {
         try {
             $VmResponse = Http::withHeaders([
                 "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
             ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/{$url}/user-resource/vm/list");
     
             $data = $VmResponse->json();
     
             info("Processing URL: " . $url);
             info($data);
     
             $responses[] = [
                 "url" => $url,
                 "success" => true,
                 "data" => $data,
             ];
         } catch (Exception $e) {
             info($e->getMessage());
     
             $responses[] = [
                 "url" => $url,
                 "success" => false,
                 "msg" => "Fail - 605",
             ];
         }
     }
     
     return response()->json($responses);
}
}