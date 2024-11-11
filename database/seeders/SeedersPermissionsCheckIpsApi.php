<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsCheckIpsApi extends Seeder
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
                'name' => 'access_check_ips_api',
                'guard_name' => 'web',
                'permission_id'=> 'checkIpsApi',
                'project'=> 'Tools',
                'description' => 'Access the check ips using Bulcklistblacklist api page',
            ],
        ];

        Permission::insert($permissions);
    }
}
