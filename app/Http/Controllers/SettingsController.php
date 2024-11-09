<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServerProvider;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\domaincheckecontroller;
use App\Models\SshKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    function index()
    {
        if(auth()->user()->cannot('see_settings')) abort(403);
        
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            $password = DB::table('app_settings')->value('default_password');

            $ssh = SshKey::first();

            return view("settings.index", compact("ssh", "password"));
        }
        else
        {
            return view("500");
        }
    }

    function update_default_password(Request $request)
    {
        if(auth()->user()->cannot("change_servers_password")) abort(403);
        if($request->default_password == null) return redirect()->back()->withErrors(["Password can't be NULL"]);

        DB::table('app_settings')->update(['default_password' => $request->default_password]);

        return redirect()->back()->with("success", "Default password changed succusfully");
    }

    function generateKeys(Request $request)
    {
        if(auth()->user()->cannot("update_servers_rsa")) abort(403);

        if($request->passphrase == null) return response()->json(['success' => false,'message' => "Passphrase can't be NULL",]);

        try
        { 
            $privateKeyPath = Storage::path('public/keys_temp/id_rsa');
            $publicKeyPath = Storage::path('public/keys_temp/id_rsa.pub');

            $key_name = $request->key_name;
            $key_namePath = Storage::path('public/keys_temp/key_name.txt');

            $passphraseFile = Storage::path('public/keys_temp/passphrase.txt');
            $passphrase = $request->passphrase;

            $comment = 'youssefhayyati47@gmail.com';

            if (File::exists($privateKeyPath)) unlink($privateKeyPath);
            if (File::exists($publicKeyPath)) unlink($publicKeyPath);

            $command = sprintf(
                'ssh-keygen -t rsa -b 4096 -f %s -N %s -C %s',
                escapeshellarg($privateKeyPath),
                escapeshellarg($passphrase),
                escapeshellarg($comment)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) 
            {
                info('Error generating SSH key pair: ' . implode("\n", $output));
                return response()->json(['success' => false,'message' => 'Error while generating keys',]);
            }
            else 
            {
                File::put($passphraseFile, $passphrase);
                File::put($key_namePath, $key_name);
            }

        }
        catch(\Exception $e)
        {
            info($e->getMessage());
            return response()->json(['success' => false,'message' => 'Error while generating keys',]);
        }

        return response()->json(['success' => true,'message' => 'Keys generated successfully',]);
    }

    function updateProvidersKeys(Request $request)
    {
        if(auth()->user()->cannot("update_servers_rsa")) abort(403);

        $providers = ServerProvider::where('is_cloud', true)->where('cloud_type', 'digitalocean')->where('status', true)->get();

        $public_key_temp = file_get_contents(Storage::path("public/keys_temp/id_rsa.pub"));
        $private_key_temp = file_get_contents(Storage::path("public/keys_temp/id_rsa"));
        $passphrase_temp = file_get_contents(Storage::path("public/keys_temp/passphrase.txt"));
        $name_temp = file_get_contents(Storage::path("public/keys_temp/key_name.txt"));

        $public_key = Storage::path('public/keys/id_rsa.pub');
        $private_key = Storage::path('public/keys/id_rsa');
        $passphrase = Storage::path('public/keys/passphrase.txt');
        $name = Storage::path('public/keys/key_name.txt');

        $successfull_providers = [];
        $failed_providers = [];

        foreach($providers as $provider)
        {
            try
            {
                $url = 'https://api.digitalocean.com/v2/account/keys';

                $ch = curl_init($url);

                // Set cURL options
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $provider->cloud_api_key,
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'name' => $name_temp,
                    'public_key' => $public_key_temp,
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                $results = json_decode($response, true);
                
                if(isset($results['message']))
                {
                    array_push($failed_providers, $provider->name);
                }
                else
                {
                    file_put_contents($public_key, $public_key_temp);
                    file_put_contents($private_key, $private_key_temp);
                    file_put_contents($passphrase, $passphrase_temp);
                    file_put_contents($name, $name_temp);

                    $provider->id_rsa_fingerprint = $results['ssh_key']['id'];
                    $provider->save();

                    array_push($successfull_providers, $provider->name);
                }
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
                return response()->json(['success' => false,'message' => 'Error while updating keys',]);
            }
        }
        
        $ssh = SshKey::first() ?? new SshKey();
        
        $ssh->name = $name_temp;
        $ssh->private_key = $private_key_temp;
        $ssh->public_key = $public_key_temp;
        $ssh->passphrase = $passphrase_temp;
        $ssh->save();

        return response()->json(['success' => true, "message" => "percentage of successful providers is : " . (count($successfull_providers) / count($providers)) * 100 . "%",]);
    }
}
