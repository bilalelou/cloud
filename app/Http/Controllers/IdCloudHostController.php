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

   public function storeIdCloudHost()
   {
      
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
         info("hello bilal");

         info($e);

         return  response()->json([
            "success" => false,
            "msg" => "Fail - 605",
         ]);
      }
   }
}
