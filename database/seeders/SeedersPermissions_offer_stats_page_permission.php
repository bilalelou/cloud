<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeedersPermissions_offer_stats_page_permission extends Seeder
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
                'name' => 'access_offers_stats',
                'guard_name' => 'web',
                'project'=> 'Stats',
                'permission_id' => 'offerStats',
                'description' => 'let the user access offers stats page'
            ]
        ];

        Permission::insert($permissions);
    }
}
