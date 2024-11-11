<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsTest_send_server extends Seeder
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
                "name" => "test_send_server",
                "guard_name" => "web",
                "permission_id"=> "test_send_server",
                "project"=> "Compaigns",
                "description" => "This permission gives you the possibility to access the page 'Test Send Server' and send a test without the need to create a new compaign",
            ],
        ];
        Permission::insert($permissions);
    }
}
