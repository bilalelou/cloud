<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsCheckServers2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [
            [
                'name' => 'ShowServersStatusMonitoring',
                'guard_name' => 'web',
                'permission_id' => 'Servers',
                'project' => 'Deliverability',
                'description' => 'Access to Servers Status Monitoring page',
            ],
        ];

        Permission::insert($permissions);
    }
}
