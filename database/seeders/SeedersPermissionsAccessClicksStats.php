<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsAccessClicksStats extends Seeder
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
                'name' => 'access_clicks_status',
                'guard_name' => 'web',
                'permission_id' => 'stats',
                'project'=> 'Offers',
                'description' => 'Access the sponsors click status page',
            ],
        ];

        Permission::insert($permissions);
    }
}
