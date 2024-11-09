<?php

namespace App\Http\Controllers;

use App\Models\DeliveryServer;
use App\Models\ServerProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AzureController extends Controller
{
    public function generateNames()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return ["resourceGroupName" => "rg-".$randomString, "ipName" => "ip-".$randomString, "vmName" => "vm-".$randomString, "nicName" => "nic-".$randomString, "subnetName" => "subnet-".$randomString, "virtualNetworkName" => "vnet-".$randomString];
    }

    public function CreateAzureIndex()
    {
        $check_domain = (new domaincheckecontroller)->envoidomain();

        if($check_domain == "true")
        {
            if(auth()->user()->cannot("create_azure_server")) abort(403);
            
            $providers = ServerProvider::where("is_cloud", true)->where("cloud_type", "azure")->where("status", true)->get();
            return view('azure_virtual_machines.create_azure_vm', compact('providers'));
        }
        else
        {
            return view("500");
        }
    }

    public function getSubscriptions(Request $request)
    {
        $provider = ServerProvider::find($request->providerId);
        // get access token
        $o_auth = json_decode($provider->o_auth, true);
        $access_token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);
        // get subscriptions
        $subscriptions = Http::withToken($access_token)->get('https://management.azure.com/subscriptions?api-version=2022-12-01');

        if(array_key_exists("error", $subscriptions->json()))
        {
            return response()->json(["success" => false, "message" => $subscriptions->json()["error"]["message"]]);
        }
        else
        {
            return response()->json(["success" => true, "subs" => $subscriptions->json()["value"]]);
        }

    }

    public function getRegions(Request $request)
    {
        $provider = ServerProvider::find($request->providerId);

        // get access token
        $o_auth = json_decode($provider->o_auth, true);
        $access_token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);

        //get locations
        $regions = Http::withToken($access_token)->get('https://management.azure.com/subscriptions/'.$request->subscriptionId.'/locations?api-version=2022-12-01');

        if(array_key_exists("error", $regions->json()))
        {
            return response()->json(["success" => false,"message" => $regions->json()["error"]["message"]]);
        }
        else
        {
            return response()->json(["success" => true,"regions" => $regions->json()["value"]]);
        }
    }

    public function getSizes(Request $request)
    {
        $provider = ServerProvider::find($request->providerId);

        // get access token
        $o_auth = json_decode($provider->o_auth, true);
        $access_token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);

        // get sizes
        $sizes = Http::withToken($access_token)->get('https://management.azure.com/subscriptions/'.$request->subscriptionId.'/providers/Microsoft.Compute/locations/eastus/vmSizes?api-version=2022-08-01');
        
        if(array_key_exists("error", $sizes->json()))
        {
            return response()->json(["success" => false, "message" => $sizes->json()["error"]["message"]]);
        }
        else
        {
            return response()->json(["success" => true, "sizes" => $sizes->json()["value"]]);
        }
    }

    public function createPublicIp($access_token, $subscriptionId, $resourceGroupName, $region, $ipName)
    {
        $publicIp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ])->put("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/{$resourceGroupName}/providers/Microsoft.Network/publicIPAddresses/{$ipName}?api-version=2023-09-01", [
            "location" => $region,
        ]);
            
        return $publicIp->json();
    }

    public function getIP($access_token, $subscriptionId, $resourceGroupName, $ipName)
    {
        $ip = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ])->get("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/{$resourceGroupName}/providers/Microsoft.Network/publicIPAddresses/{$ipName}?api-version=2023-09-01");

        return $ip->json();
    }

    public function createVirtualNetwork($access_token, $subscriptionId, $resourceGroupName, $region, $virtualNetworkName)
    {
        $virtualNetworkPrefix = '10.0.0.0/16';

        $virtualNetwork = Http::withToken($access_token)->put("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/".$resourceGroupName."/providers/Microsoft.Network/virtualNetworks/{$virtualNetworkName}?api-version=2023-06-01", [
            'location' => $region,
            'properties' => [
                'addressSpace' => [
                    'addressPrefixes' => [$virtualNetworkPrefix],
                ],
            ],
        ]);

        return $virtualNetwork->json();
    }

    public function createSubnet($access_token, $subscriptionId, $resourceGroupName, $region, $virtualNetworkName, $subnetName)
    {
        $subnetPrefix = '10.0.0.0/24';
        $subnet = Http::withToken($access_token)->put("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/".$resourceGroupName."/providers/Microsoft.Network/virtualNetworks/{$virtualNetworkName}/subnets/{$subnetName}?api-version=2023-06-01", [
            'location' => $region,
            'properties' => [
                'addressPrefix' => $subnetPrefix,
            ]
        ]);

        return $subnet->json();
    }

    public function createNic($access_token, $subscriptionId, $resourceGroupName, $region, $virtualNetworkName, $subnetName, $publicIp, $nicName)
    {
        $nic = Http::withToken($access_token)->put("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/{$resourceGroupName}/providers/Microsoft.Network/networkInterfaces/{$nicName}?api-version=2023-06-01", [
            'properties' => [
                'ipConfigurations' => [
                    [
                        'name' => 'ipconfig1',
                        'properties' => [
                            'publicIPAddress' => [
                                'id' => $publicIp ?? null,
                            ],
                            'subnet' => [
                                'id' => "/subscriptions/{$subscriptionId}/resourceGroups/".$resourceGroupName."/providers/Microsoft.Network/virtualNetworks/{$virtualNetworkName}/subnets/{$subnetName}",
                            ],
                        ],
                    ],
                ],
            ],
            'location' => $region,
        ]);
        
        return $nic->json();
    }

    public function getImage($image)
    {
        switch ($image) {
            case 'centos7.9':
                $imagePublisher = 'OpenLogic';
                $imageOffer = 'CentOS';
                $imageSku = '7_9';
                break;
            case 'centos8.0':
                $imagePublisher = 'OpenLogic';
                $imageOffer = 'CentOS';
                $imageSku = '8_0';
                break;
            case 'centos8.2':
                $imagePublisher = 'OpenLogic';
                $imageOffer = 'CentOS';
                $imageSku = '8_2';
                break;
            case 'ubuntu20.04':
                $imagePublisher = 'Canonical';
                $imageOffer = 'UbuntuServer';
                $imageSku = '20.04-LTS';
                break;
            default:
            $imagePublisher = '';
            $imageOffer = '';
            $imageSku = '';
            break;
        }

        return ["imagePublisher" => $imagePublisher, "imageOffer" => $imageOffer, "imageSku" => $imageSku];
    }

    public function createResourceGroup($providerId, $subscriptionId, $resourceGroupName, $location)
    {
        $provider = ServerProvider::find($providerId);
        // get access token
        $o_auth = json_decode($provider->o_auth, true);
        $access_token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);

        // create a resource group
        $subs = Http::withToken($access_token)->put("https://management.azure.com/subscriptions/".$subscriptionId."/resourcegroups/".$resourceGroupName."?api-version=2021-04-01", [
            'location' => $location,
        ]);

        return $subs->json();
    }

    public function createVm($access_token, $subscriptionId, $resourceGroupName, $region, $image, $nicName, $vmName, $size, $password)
    {
        $vm = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
            ])->put("https://management.azure.com/subscriptions/".$subscriptionId."/resourceGroups/".$resourceGroupName."/providers/Microsoft.Compute/virtualMachines/".$vmName."?api-version=2023-09-01", [
                'location' => $region,
                'properties' => [
                    'hardwareProfile' => ['vmSize' => $size],
                    'storageProfile' => [
                        'imageReference' => [
                            'publisher' => $image["imagePublisher"],
                            'offer' => $image["imageOffer"],
                            'sku' => $image["imageSku"],
                            'version' => 'latest',
                        ],
                        'osDisk' => ['createOption' => 'fromImage'],
                    ],
                    'osProfile' => [
                        'computerName' => $vmName,
                        'adminUsername' => 'userAdmin',
                        'adminPassword' => $password,
                    ],
                    'networkProfile' => [
                        'networkInterfaces' => [
                            [
                            'id' => '/subscriptions/'.$subscriptionId.'/resourceGroups/'.$resourceGroupName.'/providers/Microsoft.Network/networkInterfaces/'.$nicName,
                        ],
                    ],
                ],
            ],
        ]);

        return $vm->json();
    }

    public function getVMStatus($access_token, $subscriptionId, $resourceGroupName, $vmName)
    {
        $status = Http::withToken($access_token)->get("https://management.azure.com/subscriptions/{$subscriptionId}/resourceGroups/{$resourceGroupName}/providers/Microsoft.Compute/virtualMachines/{$vmName}?api-version=2015-06-15");
        return $status->json();
    }

    public function createVirtualMachines(Request $request)
    {
        set_time_limit(380);

        $provider = ServerProvider::find($request->providerId);
        $region = $request->region;
        $size = $request->size;
        $image = $this->getImage($request->image);
        $subscriptionId = $request->subscriptionId;
        $numberOfVMs = $request->numberOfVMs;
        $password = DB::table('app_settings')->value('default_password');
        $successful_vms = [];
        
        // get access token
        $o_auth = json_decode($provider->o_auth, true);
        $access_token = DeliveryServer::generateAccessToken($o_auth["client_id"], $o_auth["client_secret"], $o_auth["tenant_id"]);
        
        for($i = 0; $i < $numberOfVMs; $i++)
        {
            //generate names
            $resourcesNames = $this->generateNames();

            try
            {
                //create a resource group
                $resourceGroup = $this->createResourceGroup($provider->id, $subscriptionId, $resourcesNames["resourceGroupName"], $region);
                if(array_key_exists("error",$resourceGroup)) return response()->json(["success" => false,"message" => $resourceGroup["error"]["message"]]);
                sleep(5);

                //create a public ip
                $publicIp = $this->createPublicIp($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $region, $resourcesNames["ipName"]);
                if(array_key_exists("error",$publicIp)) return response()->json(["success" => false,"message" => $publicIp["error"]["message"]]);
                sleep(5);

                //create a virtual network
                $virtualNetwork = $this->createVirtualNetwork($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $region, $resourcesNames["virtualNetworkName"]);
                if(array_key_exists("error",$virtualNetwork)) return response()->json(["success" => false,"message" => $virtualNetwork["error"]["message"]]);        
                sleep(5);
                
                //create a subnet
                $subnet = $this->createSubnet($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $region, $virtualNetwork['name'], $resourcesNames["subnetName"]);
                if(array_key_exists("error",$subnet)) return response()->json(["success" => false,"message" => $subnet["error"]["message"]]);
                sleep(5);
                
                //create a nic
                $nic = $this->createNic($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $region, $virtualNetwork['name'], $subnet['name'], $publicIp['id'], $resourcesNames["nicName"]);
                if(array_key_exists("error",$nic)) return response()->json(["success" => false,"message" => $nic["error"]["message"]]);
                sleep(5);
                
                //create the virtual machine
                $vm = $this->createVm($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $region, $image, $resourcesNames["nicName"], $resourcesNames["vmName"], $size, $password);
                if(array_key_exists("error",$vm)) return response()->json(["success" => false,"message" => $vm["error"]["message"]]);
                sleep(5);

                
                if(array_key_exists("error",$vm))
                {
                    return response()->json(["success" => false, "message" => $vm["error"]["message"]]);
                }
                elseif (array_key_exists("id",$vm))
                {
                    //get vm's IP
                    do
                    {
                        $ip = $this->getIP($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $publicIp['name']);
                        sleep(5);
                    }
                    while(!array_key_exists("ipAddress", $ip["properties"]));
                    
                    //get vm's status
                    do
                    {
                        $status = $this->getVMStatus($access_token, $subscriptionId, $resourcesNames["resourceGroupName"], $resourcesNames["vmName"]);
                        sleep(5);
                    }
                    while($status["properties"]["provisioningState"] != "Succeeded");
                    
                    //storing data in database
                    $virtualMachine = new DeliveryServer();
                    $virtualMachine->cloud_id = $vm["properties"]["vmId"];
                    $virtualMachine->serverprovider_id = $provider->id;
                    $virtualMachine->name = $resourcesNames["vmName"];
                    $virtualMachine->type = $vm["properties"]["hardwareProfile"]["vmSize"];
                    $virtualMachine->main_ip = $ip["properties"]["ipAddress"];
                    $virtualMachine->status = "saved";
                    $virtualMachine->ssh_auth_type = "plain_password";
                    $virtualMachine->interval = "both";
                    $virtualMachine->is_proxy = false;
                    $virtualMachine->main_domain = DeliveryServer::generateRandomDomain();
        
                    $img = $vm["properties"]["storageProfile"]["imageReference"];
        
                    if($img["publisher"] == "OpenLogic" && $img["offer"] == "CentOS" && $img["sku"] == "7_9")
                    {
                        $virtualMachine->os_installed = "centos7";
                    }
                    else if($img["publisher"] == "OpenLogic" && $img["offer"] == "CentOS" && $img["sku"] == "8_0")
                    {
                        $virtualMachine->os_installed = "centos8";
                    }

                    $virtualMachine->geo = $vm["location"];
                    $virtualMachine->ssh_user = "userAdmin";
                    $virtualMachine->ssh_password = $password;
                    $virtualMachine->ssh_key_content = null;
                    $virtualMachine->ssh_port = 22;
                    $json = ["subscription_id" => $subscriptionId,"resource_group_name" => $resourcesNames["resourceGroupName"]];
                    $virtualMachine->vm_info = json_encode($json);
        
                    $virtualMachine->save();

                    array_push($successful_vms, $virtualMachine->id);
                }
            }
            catch(Exception $e)
            {
                return response()->json(["success" => false, "message" => $e->getMessage()]);
            }
        }
        
        $virtualMachines = DeliveryServer::whereIn('id', $successful_vms)->select('id','name','main_ip','os_installed','status')->get();

        return response()->json([
            "success" => true, 
            "message" => "(".count($successful_vms)."/".$numberOfVMs.") Server created successfully",
            "servers" => $virtualMachines,
            "successful_vms" => $successful_vms
        ]);
    }

    
}