<?php

namespace App\Observers;

use App\Models\CloudServer;

class CloudServerObserver
{
    /**
     * Handle the CloudServer "created" event.
     *
     * @param  \App\Models\CloudServer  $cloudServer
     * @return void
     */
    public function created(CloudServer $cloudServer)
    {
        $cloudServer->name = "EMC_SR".str_pad($cloudServer->id, 4, '0', STR_PAD_LEFT);
        $cloudServer->save();

        info($cloudServer);
    }

    /**
     * Handle the CloudServer "updated" event.
     *
     * @param  \App\Models\CloudServer  $cloudServer
     * @return void
     */
    public function updated(CloudServer $cloudServer)
    {
        //
    }

    /**
     * Handle the CloudServer "deleted" event.
     *
     * @param  \App\Models\CloudServer  $cloudServer
     * @return void
     */
    public function deleted(CloudServer $cloudServer)
    {
        //
    }

    /**
     * Handle the CloudServer "restored" event.
     *
     * @param  \App\Models\CloudServer  $cloudServer
     * @return void
     */
    public function restored(CloudServer $cloudServer)
    {
        //
    }

    /**
     * Handle the CloudServer "force deleted" event.
     *
     * @param  \App\Models\CloudServer  $cloudServer
     * @return void
     */
    public function forceDeleted(CloudServer $cloudServer)
    {
        //
    }
}
