<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsSimpleServerInstall extends Seeder
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
                'name' => 'SimpleServerInstall',
                'guard_name' => 'web',
                'permission_id' => 'serverinstall',
                'project' => 'Deliverability',
                'description' => 'Access to simple install server page',
            ],
        ];

        Permission::insert($permissions);
    }
}
