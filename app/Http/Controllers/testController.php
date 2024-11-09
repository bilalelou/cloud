<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CloudServersProvider;
use App\Models\CloudServer;
use App\Models\User;
use DB;
use SSH;
use Config;
use Illuminate\Support\Facades\Http;
use Spatie\Valuestore\Valuestore;

class testController extends Controller
{
    function index()
    {
        Auth::loginUsingId(1);

        //verify api token
            // $response = Http::withHeaders(['Authorization' => 'Bearer EKviawDiqmYxcDXdrfun8AZWoye81Ues0BJB8TlV','Content-Type' => 'application/json',])
            //                 ->get('https://api.cloudflare.com/client/v4/user/tokens/verify');

            // $data = $response->json();
            // dd($data);


        //get zone identifier of a domain
            // curl -X GET "https://api.cloudflare.com/client/v4/zones?name=yourdomain.com" \
            //     -H "Authorization: Bearer YOUR_API_TOKEN" \
            //     -H "Content-Type: application/json" ;

        //update dns records
            // curl -X PUT "https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/dns_records/YOUR_DNS_RECORD_ID" \
            //     -H "Authorization: Bearer YOUR_API_TOKEN" \
            //     -H "Content-Type: application/json" \
            //     --data '{"type":"A","name":"subdomain","content":"new_ip_address","ttl":120,"proxied":true}'

        //get a list of all domains (zones) associated with your Cloudflare account
            // curl -X GET "https://api.cloudflare.com/client/v4/zones" \
            //     -H "Authorization: Bearer YOUR_API_TOKEN" \
            //     -H "Content-Type: application/json"


        // dd(1111);
        // $input = array("a" => "green", "red", "b" => "green", "blue", "red");
        // print_r($input);

        // $user = User::find(1);
        // $user->givePermissionTo('update_servers_rsa');
        // dd($user->can('update_servers_rsa'));

    }
}
