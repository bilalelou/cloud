<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class SeedersPermissionsTicketsProject extends Seeder
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
                'name' => 'show_own_tickets',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The Ability to see own tickets',
            ],
            [
                'name' => 'show_all_tickets',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The Ability to see all tickets',
            ],
            [
                'name' => 'show_group_tickets',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The Ability to see group tickets',  
            ],
            [
                'name' => 'create_ticket',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The ability to create ticket',
            ],
            [
                'name' => 'close_ticket',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The ability to close ticket',
            ],
            [
                'name' => 'fix_ticket',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'The ability to fix ticket',
            ],
            [
                'name' => 'it_pending_ticket',
                'guard_name' => 'web',
                'permission_id'=> 'tickets',
                'project' => 'Tickets',
                'description' => 'When the user who has this permission opens a ticket, its status changes to "Pending"',
            ],
            [
                'name' => 'tickets',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project' => 'Tools',
                'description' => 'The Ability to access the tickets project',
            ],
        ];
        Permission::insert($permissions);

    }
}
