<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsShowItServerStats extends Seeder
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
                'name' => 'show_it_server_stats',
                'guard_name' => 'web',
                'permission_id'=> 'myWallet',
                'project' => 'Stats',
                'description' => 'let the it see serrver stats without revenue column'
            ],
        ];

        Permission::insert($permissions);
    }
}
