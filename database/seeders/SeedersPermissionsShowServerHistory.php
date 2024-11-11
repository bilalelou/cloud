<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeedersPermissionsShowServerHistory extends Seeder
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
                'name' => 'ShowServerHistory',
                'guard_name' => 'web',
                'permission_id' => 'Servers',
                'project' => 'Deliverability',
                'description' => 'The Ability to see server history',
            ],
        ];

        Permission::insert($permissions);
    }
}
