<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsShowLogs extends Seeder
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
                'name' => 'Show_Logs',
                'guard_name' => 'web',
                'permission_id'=> 'settings',
                'project' => 'Deliverability',
                'description' => 'The ability to access logs page',
            ],
        ];

        Permission::insert($permissions);
    }
}
