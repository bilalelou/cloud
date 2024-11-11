<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsMixWithTags extends Seeder
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
                'name' => 'show_geos_help_in_mwt',
                'guard_name' => 'web',
                'permission_id' => 'senders',
                'project' => 'Compaigns',
                'description' => 'Users with this permission have a button that helps them select multiple senders with the same geo in Mix With Tags',
            ],
            [
                'name' => 'select_multiple_sender_in_mwt',
                'guard_name' => 'web',
                'permission_id' => 'senders',
                'project' => 'Compaigns',
                'description' => 'Users with this permissions can select multiple senders in Mix With Tags. The default is one sender.',
            ]
        ];
        Permission::insert($permissions);

       
    }
}
