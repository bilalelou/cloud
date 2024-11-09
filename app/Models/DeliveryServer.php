<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Config;
use SSH;

class DeliveryServer extends Model
{
    use HasFactory;

    protected $table = 'deliveryservers';
    
    public function serverprovider()
    {
       return $this->belongsTo(ServerProvider::class);
    }

    public static function generateRandomDomain()
    {
        $extensions = [".ai",".app",".art",".best",".biz",".ca",".cc",".club",".co",".co.uk",".com",".cx",".de",".design",".dev",".eu",".fm",".fun",".gg",".host",".icu",".id",".in",".inc",".info",".io",".is",".life",".live",".me",".mx",".net",".nl",".online",".org",".ph",".pro",".pw",".shop",".space",".store",".tech",".to",".tv",".uk",".us",".vip",".website",".world",".xyz"];

        $characters = "abcdefghijklmnopqrstuvwxyz";

        $length = rand(3,10);

        $randomString = "";

        for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, strlen($characters) - 1)];

        return $randomString.$extensions[array_rand($extensions)];
    }

    public static function generateAccessToken($client_id, $client_secret, $tenant_id) 
    {
        $post_f = [
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'resource' => 'https://management.azure.com',
            'scope' => 'https://management.azure.com/.default',
        ];

        $api_url = "https://login.microsoftonline.com/$tenant_id/oauth2/token";
        
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_f);

        $response = json_decode(curl_exec($ch), true);
        $access_token = $response['access_token'];

        return $access_token;
    }

    function initializeCloudServers($server_id)
    {
        set_time_limit(180);

        $password = DB::table('app_settings')->value('default_password');
        
        try
        {
            $server = DeliveryServer::find($server_id);

            $private_key_link = storage_path("/app/public/keys/id_rsa");
            $passphrase = file_get_contents(storage_path("/app/public/keys/passphrase.txt"));

            Config::set('remote.connections.production.host', $server->main_ip);
            Config::set('remote.connections.production.port', '22');
            Config::set('remote.connections.production.username', $server->ssh_user);
            Config::set('remote.connections.production.key', $private_key_link);
            Config::set('remote.connections.production.keyphrase', $passphrase);
            Config::set('remote.connections.production.timeout', 60);

            $commands = "sudo yes '".$password."' | passwd root && sed -i '/#PasswordAuthentication yes/c\PasswordAuthentication yes' /etc/ssh/sshd_config && sudo service sshd restart";

            SSH::into('production')->run($commands, function($line)
            {
                info($line);
            });

            $server->status = "saved";
            $server->ssh_password = $password;
            $server->save();
        }
        catch(\Exception $e)
        {
            info("Error: \n" . $e);
            return false;

        }

        return true;
    }
}
