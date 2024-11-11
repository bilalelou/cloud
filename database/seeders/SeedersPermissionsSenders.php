<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsSenders extends Seeder
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
                'name' => 'create_new_sender',
                'guard_name' => 'web',
                'permission_id'=> 'senders',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to create a new Sender',
            ],
            [
                'name' => 'show_all_senders',
                'guard_name' => 'web',
                'permission_id'=> 'senders',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to use the Senders',
            ],
            [
                'name' => 'view_senders_page',
                'guard_name' => 'web',
                'permission_id'=> 'senders',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to enter the Senders Page',
            ],
        ];
        Permission::insert($permissions);
    }
}
