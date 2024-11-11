<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class SeedersPermissionsProblems extends Seeder
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
                'name' => 'show_problems',
                'guard_name' => 'web',
                'permission_id'=> 'Problems',
                'project' => 'Tickets',
                'description' => 'The Ability to access problem page',
            ],
            [
                'name' => 'create_problems',
                'guard_name' => 'web',
                'permission_id'=> 'Problems',
                'project' => 'Tickets',
                'description' => 'The Ability to create new problems',
            ],
 
        ];
        Permission::insert($permissions);


    }
}
