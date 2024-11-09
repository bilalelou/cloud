<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerProvider extends Model
{
    use HasFactory;

    protected $table = 'serverproviders';
    
    public function servers()
    {
        return $this->hasMany(DeliveryServer::class, 'serverprovider_id', 'id');
    }
}
