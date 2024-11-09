<?php

namespace App\Models;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Deliveryserver;


class Deliveryserverinterface extends Model
{
    use HasFactory;

    public function server()
    {
      return $this->belongsTo(Deliveryserver::class, 'deliveryserver_id');
    }

    public static function getServerByInterface($ip)
    {
      $activeServersId = Deliveryserver::where('status','active')->pluck('id');
      return Deliveryserverinterface::where('ip',$ip)->whereIn('deliveryserver_id',$activeServersId)->first()->server;
    }

    public function checkInterface($ip)
    {
      try
      {
          $offer_info = Offer::join('compaigns','compaigns.offer_id','offers.id')
              ->where('compaigns.url_redirection',true)
              ->whereNotNull('compaigns.offer_id')
              ->select('offers.lead_link','compaigns.id')
              ->orderBy('compaigns.created_at','desc')->first();

          $random = "?d=$offer_info->id&ei=001&if=62642&li=00";
          $random = base64_encode($random);
          $interfaces = $ip;
          $url = "http://$interfaces/checkDomain/" . $random;

          $res = strval(Http::timeout(10)->get($url));

          if(str_contains($res, $offer_info->lead_link))
          {
              $status = "up";
          }
          else
          {
              $status = "down";
          }
      }
      catch(\Exception $ex)
      {
          $status = "down";
      }

      return $status;

    }
}
