<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsCustomSetting extends Seeder
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
                'name' => 'see_custom_setting_page',
                'guard_name' => 'web',
                'permission_id' => 'customSettings',
                'project'=> 'Tools',
                'description' => 'Access the custom settings page',
            ],
        ];

        Permission::insert($permissions);

    }
}
