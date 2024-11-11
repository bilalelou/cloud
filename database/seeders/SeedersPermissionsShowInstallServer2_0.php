<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class SeedersPermissionsShowInstallServer2_0 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::where('name', 'Show_install_server_2.0')->delete();

        $permissions = [
            [
                'name' => 'Show_install_server_2.0',
                'guard_name' => 'web',
                'permission_id'=> 'serverinstall',
                'project'=> 'Deliverability',
                'description' => 'Access to install server 2.0 version page',
            ],
        ];
        Permission::insert($permissions);

    }
}
