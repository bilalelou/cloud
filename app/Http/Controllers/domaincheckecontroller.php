<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\App_settings;
use Illuminate\Support\Facades\Http;

class domaincheckecontroller extends Controller
{
    //
    public function envoidomain()
    {
      $url = url()->current();
      $host1 = preg_replace("/^https?:\/\/(.+)$/i", "\\1", $url);
      $host = str_replace("/", ":", $host1);
      $app_setting = App_settings::first();

      $domains = ($app_setting->check_licence_ip === 'ip1') 
                ? ['admin.e-impactpro.com', 'trackograph.com'] 
                : ['trackograph.com', 'admin.e-impactpro.com'];

      try
      {

        $response = null;
        $maxRetries = 3;
  
        for ($i = 0; $i < $maxRetries; $i++)
        {
            $response = Http::timeout(10)->post("http://{$domains[0]}/checkDomaine", ['host' => $host]);
            
            if ($response->successful()) break;
        }
  
        if (!$response || !$response->successful())
        {
            $response = Http::timeout(10)->post("http://{$domains[1]}/checkDomaine", ['host' => $host]);

            $app_setting->check_licence_ip = ($app_setting->check_licence_ip === 'ip1') ? 'ip2' : 'ip1';
            $app_setting->save();
        }

      } catch (\Exception $th) 
      {
          $response = Http::timeout(10)->post("http://{$domains[1]}/checkDomaine", ['host' => $host]);
          $app_setting->check_licence_ip = ($app_setting->check_licence_ip === 'ip1') ? 'ip2' : 'ip1';
          $app_setting->save();
      }
      return $response;
    }

}
