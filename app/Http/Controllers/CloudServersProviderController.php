<?php

namespace App\Http\Controllers;

use App\Models\ServerProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\domaincheckecontroller;

class CloudServersProviderController extends Controller
{
    function index()
    {
        if(auth()->user()->cannot('see_providers')) abort(403);

        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            $providers = ServerProvider::where("is_cloud", true)->get();

            return view("cloud_servers_providers.index", compact("providers"));
        }
        else
        {
            return view("500");
        }
    }

    function addNewProvider(Request $request)
    {
        if(auth()->user()->cannot('create_cloud_provider')) return response()->json(["success" => false,"message" => "Unauthorized"]);
        
        $errors = [];
        
        if($request->name == null) array_push($errors, "Please select a provider name");
        if(!filter_var($request->email, FILTER_VADATE_EMAIL) || $request->email == null) array_push($errors, "Please insert a valid email");
        if($request->password == null) array_push($errors, "Please insert a password");
        if(ServerProvider::where("name", $request->name)->where("cloud_email", $request->email)->exists() == true) array_push($errors, "Email already exists for this provider");
        
        if($errors != []) return redirect()->back()->withErrors($errors);

        $provider = new ServerProvider();

        $provider->name = $request->name;
        $provider->comment = $request->comment;
        $provider->cloud_email = $request->email;
        $provider->cloud_password = $request->password;
        if($request->api_key == "") 
        {
            $json = [
                "client_id" => $request->client_id,
                "tenant_id" => $request->tenant_id ?? null,
                "client_secret" => $request->client_secret
            ];
            $provider->o_auth = json_encode($json);
        }
        else
        {
            $provider->cloud_api_key = $request->api_key;
        }

        $provider->is_cloud = true;
        $provider->cloud_type = $request->type;

        if($request->status == "active") $provider->status = true;
        else $provider->status = false;

        $provider->save();

        return redirect()->back()->with("success", "Proivder added successfully");
    }

    function getProviderById(Request $request)
    {
        $provider = ServerProvider::find($request->provider_id);
        $o_auth = json_decode($provider->o_auth, true);

        return response()->json(["success" => true, "provider" => $provider, "o_auth" => $o_auth]);
    }

    function updateProvider(Request $request)
    {
        $errors = [];

        if($request->name_edit == null) array_push($errors, "Please select a provider name");
        if(!filter_var($request->email_edit, FILTER_VALIDATE_EMAIL) || $request->email_edit == null) array_push($errors, "Please insert a valid email");
        if($request->password_edit == null) array_push($errors, "Please insert a password");
        if($request->api_key_edit == null && $request->client_id_edit == null) array_push($errors, "Please insert an api key or OAuth credentials");
        if(ServerProvider::where("name", $request->name_edit)->where("cloud_email", $request->email_edit)->where("id", '!=', $request->id_edit)->exists() == true) array_push($errors, "Email already exists for this provider");
        if($errors != []) return redirect()->back()->withErrors($errors);

        $provider = ServerProvider::find($request->id_edit);
        $provider->name = $request->name_edit;
        $provider->cloud_email = $request->email_edit;
        $provider->cloud_password = $request->password_edit;
        $provider->comment = $request->comment_edit;
        $provider->cloud_api_key = $request->api_key_edit;
        $json = [
            "client_id" => $request->client_id_edit,
            "tenant_id" => $request->tenant_id_edit,
            "client_secret" => $request->client_secret_edit
        ];
        $provider->o_auth = json_encode($json);

        if($request->status_edit == "active") $provider->status = true;
        else $provider->status = false;

        $provider->save();

        return redirect()->back()->with("success", "Proivder updated successfully");
    }

    function getAvailableGeos(Request $request)
    {
        $provider = ServerProvider::where("is_cloud", true)->where("cloud_type", "digitalocean")->first();
        $api_key = $provider->cloud_api_key;

        //get available regions
        $regions_url = "https://api.digitalocean.com/v2/regions?per_page=200";
        $regions_ch = curl_init();
        curl_setopt($regions_ch, CURLOPT_URL, $regions_url);
        curl_setopt($regions_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($regions_ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer ". $api_key,
        ]);

        $region_response = curl_exec($regions_ch);
        $region_results = json_decode($region_response, true);

        if(array_key_exists("id", $region_results)) return response()->json(["success" => false, "msg" => $region_results["message"]]);

        //get available sizes
        $sizes_url = "https://api.digitalocean.com/v2/sizes?per_page=200";
        $sizes_ch = curl_init();
        curl_setopt($sizes_ch, CURLOPT_URL, $sizes_url);
        curl_setopt($sizes_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($sizes_ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer ". $api_key,
        ]);

        $size_response = curl_exec($sizes_ch);
        $size_results = json_decode($size_response, true);

        if(array_key_exists("id", $size_results)) return response()->json(["success" => false, "msg" => $size_results["message"]]);

        //remove sizes in regions who arent in slugs
        $size_slugs = array_column($size_results["sizes"], "slug");

        foreach($region_results["regions"] as $key => $region)
        {
            if($region["available"] == false) unset($region_results["regions"][$key]);

            foreach($region["sizes"] as $key2 => $size)
            {
                if(!in_array($size, $size_slugs))
                {
                    unset($region_results["regions"][$key]["sizes"][$key2]);
                }
            }
        }

        //get available images
        $images_url = "https://api.digitalocean.com/v2/images?per_page=200&type=distribution";
        $images_ch = curl_init();
        curl_setopt($images_ch, CURLOPT_URL, $images_url);
        curl_setopt($images_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($images_ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer ". $api_key,
        ]);

        $image_response = curl_exec($images_ch);
        $image_results = json_decode($image_response, true);

        if(array_key_exists("id", $image_results)) return response()->json(["success" => false, "msg" => $image_results["message"]]);

        return response()->json(["success" => true, "region_results" => $region_results, "size_results" => $size_results, "image_results" => $image_results]);
    }

    function deleteProvider(Request $request)
    {
        if(auth()->user()->cannot('delete_cloud_provider')) return response()->json(["success" => false,"message" => "Unauthorized"]);

        $provider = ServerProvider::find($request->provider_id);
        $provider->delete();

        return response()->json([
            "success" => true,
            "message" => "Provider deleted successfully"
        ]);
    }
}
