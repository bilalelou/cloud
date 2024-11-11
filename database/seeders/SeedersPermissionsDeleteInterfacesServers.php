<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsDeleteInterfacesServers extends Seeder
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
                'name' => 'delete_servers',
                'guard_name' => 'web',
                'permission_id'=> 'Servers',
                'project' => 'Deliverability',
                'description' => 'The Ability to delete servers',
            ],
            [
                'name' => 'delete_interfaces',
                'guard_name' => 'web',
                'permission_id'=> 'DeliveryServersInterfaces',
                'project' => 'Deliverability',
                'description' => 'The Ability to delete interfaces',
            ],
        ];

        Permission::insert($permissions);
    }
}
