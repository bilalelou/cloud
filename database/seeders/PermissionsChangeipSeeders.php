<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsChangeipSeeders extends Seeder
{

    public function run()
    {
        $permissions = [
            [
                'name' => 'use_change_ip',
                'guard_name' => 'web',
                'permission_id' => 'Servers',
                'project' => 'Deliverability',
                'description' => 'Add servers to change in app manage',
            ],
        ];
        Permission::insert($permissions);

    }
}