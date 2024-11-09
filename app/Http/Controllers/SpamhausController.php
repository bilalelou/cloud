<?php

namespace App\Http\Controllers;

use App\Models\TempDeliveryServer;
use Illuminate\Http\Request;

class SpamhausController extends Controller
{
    public function checkSpamhaus(Request $request)
    {
        $server_ids = explode(",", $request->ids);
        $servers = TempDeliveryServer::find($server_ids);
        $clean = 0;

        foreach($servers as $server)
        {
            $reverse_ip = implode(".", array_reverse(explode(".", $server->ip)));
            $response_sbl = shell_exec("dig +short " . $reverse_ip . ".sbl.spamhaus.org")."";
            
            if(str_contains($response_sbl, "127.0.0.2") || str_contains($response_sbl, "127.0.0.3") || str_contains($response_sbl, "127.0.0.9"))
            {
                $server->spamhaus = true;
                $server->save();
            }
            else 
            {
                $server->spamhaus = false;
                $server->save();
                $clean++;
            }
        }

        return response()->json([
            "success" => true,
            "servers" => $servers,
            "linodeIds" => $request->linodeIds,
            "message" => $clean . " Servers are clean out of " . count($servers)
        ]);
    }
}
