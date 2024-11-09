<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                "name" => "see_settings",
                "guard_name" => "web",
            ],
            [
                "name" => "see_providers",
                "guard_name" => "web",
            ],
            [
                "name" => "see_servers",
                "guard_name" => "web",
            ]
        ];

        Permission::insert($permissions);
    }
}
