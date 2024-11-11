<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsCheckServers extends Seeder
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
                'name' => 'CheckServers',
                'guard_name' => 'web',
                'permission_id' => 'Servers',
                'project' => 'Deliverability',
                'description' => 'Access to check servers page',
            ],
        ];

        Permission::insert($permissions);
    }
}
