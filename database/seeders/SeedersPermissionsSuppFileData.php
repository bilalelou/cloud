<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsSuppFileData extends Seeder
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
                "name" => "view_suppfile_data_page",
                "guard_name" => "web",
                "permission_id"=> "SuppFileData",
                "project"=> "Tools",
                "description" => "This permission gives you the possibility to enter the 'Suppfile Data' page",
            ],
            [
                "name" => "use_suppfile_data",
                "guard_name" => "web",
                "permission_id"=> "SuppFileData",
                "project"=> "Tools",
                "description" => "This permission gives you the possibility to use 'Only SuppFileData' in Compaign Builder",
            ],
        ];
        Permission::insert($permissions);
    }
}
