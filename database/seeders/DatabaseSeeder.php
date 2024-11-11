<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AllSeedersPermissions;
use Database\Seeders\PermissionsChangeipSeeders;
use Database\Seeders\PermissionsCheckServers2;
use Database\Seeders\PermissionSedeer;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SeedersPermissionsAccessClicksStats;
use Database\Seeders\SeedersPermissionsCheckIpsApi;
use Database\Seeders\SeedersPermissionsCheckServers;
use Database\Seeders\SeedersPermissionsCloudProject;
use Database\Seeders\SeedersPermissionsCustomSetting;
use Database\Seeders\SeedersPermissionsDeleteInterfacesServers;
use Database\Seeders\SeedersPermissionsManageServers;
use Database\Seeders\SeedersPermissionsMixWithTags;
use Database\Seeders\SeedersPermissionsProblems;
use Database\Seeders\SeedersPermissionsSenders;
use Database\Seeders\SeedersPermissionsShowInstallServer2_0;
use Database\Seeders\SeedersPermissionsShowItServerStats;
use Database\Seeders\SeedersPermissionsShowLogs;
use Database\Seeders\SeedersPermissionsShowServerHistory;
use Database\Seeders\SeedersPermissionsSimpleServerInstall;
use Database\Seeders\SeedersPermissionsSuppFileData;
use Database\Seeders\SeedersPermissionsTest_send_server;
use Database\Seeders\SeedersPermissionsTicketsProject;
use Database\Seeders\SeedersPermissions_offer_stats_page_permission;
use Database\Seeders\UserSeeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    //     Permission::truncate();

    //     $this->call([

    //            AllSeedersPermissions::class,
    //            PermissionsChangeipSeeders::class,
    //            PermissionsCheckServers2::class,
    //            PermissionSedeer::class,
    //            RoleAndPermissionSeeder::class,
    //            SeedersPermissionsAccessClicksStats::class,
    //            SeedersPermissionsCheckIpsApi::class,
    //            SeedersPermissionsCheckServers::class,
    //            SeedersPermissionsCloudProject::class,
    //            SeedersPermissionsCustomSetting::class,
    //            SeedersPermissionsDeleteInterfacesServers::class,
    //            SeedersPermissionsManageServers::class,
    //            SeedersPermissionsMixWithTags::class,
    //            SeedersPermissionsProblems::class,
    //            SeedersPermissionsSenders::class,
    //            SeedersPermissionsShowInstallServer2_0::class,
    //            SeedersPermissionsShowItServerStats::class,
    //            SeedersPermissionsShowLogs::class,
    //            SeedersPermissionsShowServerHistory::class,
    //            SeedersPermissionsSimpleServerInstall::class,
    //            SeedersPermissionsSuppFileData::class,
    //            SeedersPermissionsTest_send_server::class,
    //            SeedersPermissionsTicketsProject::class,
    //            SeedersPermissions_offer_stats_page_permission::class,
    //            UserSeeder::class  
    //    ]);

    }
}
