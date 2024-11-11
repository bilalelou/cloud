<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsManageServers extends Seeder
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
                'name' => 'manage-server',
                'guard_name' => 'web',
                'permission_id'=> 'settings',
                'project' => 'Deliverability',
                'description' => 'The Ability to access the manage server project',
            ],
        ];

        Permission::insert($permissions);
    }
}
