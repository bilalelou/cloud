<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Valuestore\Valuestore;



class IdCloudHostController extends Controller
{
   public function createIdCloudHostIndex() {

     /////////////////////////regions////////////////////////////
        $regionsResponse = Http::withHeaders([
        "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
    ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/locations");
        $regions = $regionsResponse->json();

        
    
 
     /////////////////////////os////////////////////////////

        $osSystemsResponse = Http::withHeaders([
             "apikey" => "5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96",
        ])->withoutVerifying()->get("https://api.idcloudhost.com/v1/config/vm_images/plain_os");



        $osSystems = $osSystemsResponse->json();




        return view('idcloudhost_servers.create_idcloudhost_server', compact('regions','osSystems'));

//      /////////////////////////regions////////////////////////////
//      $ch = curl_init();
//      curl_setopt($ch, CURLOPT_URL, "https://api.hetzner.cloud/v1/locations");
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//      curl_setopt($ch, CURLOPT_HTTPHEADER, [
//          'Content-Type: application/json',
//          'Authorization: Bearer '. $api_key,
//      ]);
//      $regions = json_decode(curl_exec($ch), true);

//     $type = $request->query('get');
//     $api_key = '5zYeg6RngxrlsTNJtzqp3ta2kdS3Fv96'; // ضع هنا مفتاح API الخاص بك

    
//     if ($type === 'locations') {
//         $url = 'https://api.idcloudhost.com/v1/config/locations';
//     } elseif ($type === 'vm_images') {
//         $url = 'https://api.idcloudhost.com/v1/config/vm_images/plain_os';
//     } else {
//         return response()->json(['error' => 'Invalid type specified'], 400);

 }
}

