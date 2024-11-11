<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
class AllSeedersPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $permissions = [
            // -----------------------Project Deliverability-----------------------------------------------------------------------------------
            [
                'name' => 'deliverability',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project' => 'Tools',
                'description' => 'The Ability to access the deliverability project',
            ], 
            [
                'name' => 'servers_access',
                'guard_name' => 'web',
                'permission_id'=> 'Servers',
                'project' => 'Deliverability',
                'description' => 'Access to server access page',
            ], 
            [
                'name' => 'see_all_servers',
                'guard_name' => 'web',
                'permission_id'=> 'Servers',
                'project' => 'Deliverability',
                'description' => 'The Ability to see all servers',
            ],
            [
                'name' => 'see_group_servers',
                'guard_name' => 'web',
                'permission_id'=> 'Servers',
                'project' => 'Deliverability', 
                'description' => 'The Ability to see group servers',               
            ],   
            [
                'name' => 'updateServersStatus',
                'guard_name' => 'web',
                'permission_id'=> 'Servers',
                'project' => 'Deliverability',
                'description' => 'The ability to update server status',
            ],
            [
                'name' => 'move_servers',
                'guard_name' => 'web',
                'permission_id' => 'Servers',
                'project' => 'Deliverability',
                'description' => 'The ability to move servers from one group to another group',
            ],
            // -------------------------------------------editserver---------------------------------------------------
            [
                'name' => 'edit_servers',
                'guard_name' => 'web',
                'permission_id'=> 'editserver',
                'project' => 'Deliverability',
                'description' => 'The Ability to edit server',
            ],
            [
                'name' => 'show_configIsp',
                'guard_name' => 'web',
                'permission_id' => 'editserver',
                'project' => 'Deliverability',
                'description' => 'The ability to see isp config',
            ],
            [
                'name' => 'update_ispConfig',
                'guard_name' => 'web',
                'permission_id' => 'editserver',
                'project' => 'Deliverability',
                'description' => 'The ability to update isp config',
            ],
            //--------------------------------------------server provider ---------------------------------------------------------------- 
            [
                'name' => 'manage_server_providers',
                'guard_name' => 'web',
                'permission_id'=> 'ServerProvider',
                'project' => 'Deliverability',
                'description' => 'Manage server providers',
            ],
            [
                'name' => 'EmailAccount',
                'guard_name' => 'web',
                'permission_id' => 'ServerProvider',
                'project' => 'Deliverability',
                'description' => 'The Ability to see email account',
            ],
            [
                'name' => 'ProviderEmailAccount',
                'guard_name' => 'web',
                'permission_id' => 'ServerProvider',
                'project' => 'Deliverability',
                'description' => 'The Ability to see provider email account',
            ],              
            //-------------------------------------------------    serverinstall---------------------------------------------
            [
                'name' => 'server_installer_access',
                'guard_name' => 'web',
                'permission_id'=> 'serverinstall',
                'project' => 'Deliverability',
                'description' => 'Access to install server page',
            ],
            [
                'name' => 'server_rienstall_access',
                'guard_name' => 'web',
                'permission_id'=> 'serverinstall',
                'project' => 'Deliverability',
                'description' => 'Access to reinstall server page',
            ],
            // -----------------------------------------DeliveryServersInterfaces-------------------------------------------
            [
                'name' => 'see_group_interfaces',
                'guard_name' => 'web',
                'permission_id'=> 'DeliveryServersInterfaces',
                'project' => 'Deliverability',
                'description' => 'The Ability to see group interfaces',
            ],
            [
                'name' => 'see_all_interfaces',
                'guard_name' => 'web',
                'permission_id'=> 'DeliveryServersInterfaces',
                'project' => 'Deliverability',
                'description' => 'The Ability to see all interfaces',
            ],
            // ---------------------------------------------Domains---------------------------------------------
            [
                'name' => 'delete_domains',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'The ability to delete domains',
            ],            
            [
                'name' => 'see_all_domains',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'The Ability to see all domains',
            ], 
            [
                'name' => 'see_group_domains',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'The Ability to see group domains',
            ],
            [
                'name' => 'checkDomain',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'Access to check domains page',
            ],
            [
                'name' => 'show_ChangeDomain',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'Access to change domains page',
            ],
            [
                'name' => 'see_SpfRawChecker',
                'guard_name' => 'web',
                'permission_id' => 'Domains',
                'project' => 'Deliverability',
                'description' => 'Access to Spf Raw Checker page',
            ],
            [
                'name' => 'manage_domains_provider',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'Manage domains provider',
            ],
            [
                'name' => 'delete_domains_provider',
                'guard_name' => 'web',
                'permission_id'=> 'Domains',
                'project' => 'Deliverability',
                'description' => 'The ability to delete domain provider',
            ],  
            [
                'name' => 'settingInterfaces',
                'guard_name' => 'web',
                'permission_id' => 'settings',
                'project' => 'Deliverability',
                'description' => 'Access to setting Interfaces',
            ],  
            // -----------------------Project Subscribers-----------------------------------------------------------------------------------
            [
                'name' => 'Geo_show',
                'guard_name' => 'web',
                'permission_id'=> 'Geo',
                'project' => 'Subscribers',
                'description' => 'Access to Geos page',
            ],
            [
                'name' => 'show_all_isp',
                'guard_name' => 'web',
                'permission_id'=> 'isps',
                'project' => 'Subscribers',
                'description' => 'The Ability to see all isps',
            ],
            [
                'name' => 'show_group_isp',
                'guard_name' => 'web',
                'permission_id'=> 'isps',
                'project' => 'Subscribers',
                'description' => 'The Ability to see group isps',
            ],
            [
                'name' => 'edit_isp',
                'guard_name' => 'web',
                'permission_id'=> 'isps',
                'project' => 'Subscribers',
                'description' => 'The Ability to edit isps',
            ],
            [
                'name' => 'black_list_show',
                'guard_name' => 'web',
                'permission_id'=> 'Black_list',
                'project' => 'Subscribers',
                'description' => 'The Ability to see black list page',
            ], 
            [
                'name' => 'see_group_emailList',
                'guard_name' => 'web',
                'permission_id'=> 'email_list',
                'project' => 'Subscribers',
                'description' => 'The Ability to see group email list',
            ], 
            [
                'name' => 'see_all_emailList',
                'guard_name' => 'web',
                'permission_id'=> 'email_list',
                'project' => 'Subscribers',
                'description' => 'The Ability to see all email list',
            ], 
            [
                'name' => 'subscribe',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project' => 'Tools',
                'description' => 'The Ability to access the subscribers project',
            ],
            // -----------------------Project Compaigns-----------------------------------------------------------------------------------
            [
                'name' => 'compaigns',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project'=> 'Tools',
                'description' => 'The Ability to access the compaigns project',
            ],
            [
                'name' => 'see_all_compaigns',
                'guard_name' => 'web',
                'permission_id'=> 'compaigns',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see/edit/save all the Compaigns',
            ],
            [
                'name' => 'see_group_compaigns',
                'guard_name' => 'web',
                'permission_id'=> 'compaigns',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see/edit/save only all the Compaigns the people in your team created',
            ],
            [
                'name' => 'see_own_compaigns',
                'guard_name' => 'web',
                'permission_id'=> 'compaigns',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see/edit/save only all the Compaigns you created',
            ],
            [
                'name' => 'create_new_compaign',
                'guard_name' => 'web',
                'permission_id'=> 'compaigns',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to create a new Compaign',
            ],  
            // ----------------------------headers------------------------------------------------------------------------------------------------
            [
                'name' => 'show_all_headers',
                'guard_name' => 'web',
                'permission_id'=> 'headers',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see all the Headers',
            ],
            [
                'name' => 'show_group_headers',
                'guard_name' => 'web',
                'permission_id'=> 'headers',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see the Headers of your group',
            ],
            [
                'name' => 'create_new_header',
                'guard_name' => 'web',
                'permission_id'=> 'headers',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to create a new Header',
            ],
            [
                'name' => 'view_headers_page',
                'guard_name' => 'web',
                'permission_id'=> 'headers',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to enter the Headers Page',
            ], 

            // -------------------------------testHistory-------------------------------------------------
            [
                'name' => 'see_all_testHistory',
                'guard_name' => 'web',
                'permission_id'=> 'testHistory',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see all the Test Histories',
            ],
            [
                'name' => 'see_group_testHistory',
                'guard_name' => 'web',
                'permission_id'=> 'testHistory',
                'project'=> 'Compaigns',
                'description' => "This permission gives you the possibility to see only all of your team's Test Histories",
            ],
            [
                'name' => 'see_own_testHistory',
                'guard_name' => 'web',
                'permission_id'=> 'testHistory',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see only your Test Histories',
            ],

            // ---------------------------------------saveHistory-------------------------------
            [
                'name' => 'see_all_saveHistory',
                'guard_name' => 'web',
                'permission_id'=> 'saveHistory',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see all the Save Histories',
            ],
            [
                'name' => 'see_group_saveHistory',
                'guard_name' => 'web',
                'permission_id'=> 'saveHistory',
                'project'=> 'Compaigns',
                'description' => "This permission gives you the possibility to see only all of your team's Save Histories",
            ],
            [
                'name' => 'see_own_saveHistory',
                'guard_name' => 'web',
                'permission_id'=> 'saveHistory',
                'project'=> 'Compaigns',
                'description' => 'This permission gives you the possibility to see only your Save Histories',
            ],  
              
            // -----------------------Project Offres-----------------------------------------------------------------------------------
            [
                'name' => 'offer',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project'=> 'Tools',
                'description' => 'The Ability to access the networks project',
            ],  
            // ---------------------------sponsors
            [
                'name' => 'sponsor_create',
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'The ability to create sponsor',
            ],
            [
                'name' => 'sponsor_edit',
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'The ability to edit sponsor',
            ],
            [
                'name' => 'show_group_sponsor',              
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'The Ability to see group sponsors',
            ],
            [
                'name' => 'show_all_sponsor',              
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'The Ability to see all sponsors',
            ],
            [
                'name' => 'see_sponsors_page',
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'The ability to access sponsors page',
            ],
            [
                'name' => 'auto_login',              
                'guard_name' => 'web',
                'permission_id' => 'sponsors',
                'project'=> 'Offers',
                'description' => 'Gives the user the ability to use auto login',
            ],
            // -------------------------------------------offer
            [
                'name' => 'offre_create',
                'guard_name' => 'web',
                'permission_id' => 'offers',
                'project'=> 'Offers',
                'description' => 'Gives the user the ability to create an offer',
            ],
            [
                'name' => 'offre_edit',
                'guard_name' => 'web',
                'permission_id' => 'offers',
                'project'=> 'Offers',
                'description' => 'Gives the user the ability to edit an offer',
            ],
            [
                'name' => 'show_group_offer',              
                'guard_name' => 'web',
                'permission_id' => 'offers',
                'project'=> 'Offers',
                'description' => 'The Ability to see group offers',
            ],
            [
                'name' => 'show_all_offer',              
                'guard_name' => 'web',
                'permission_id' => 'offers',
                'project'=> 'Offers',
                'description' => 'The Ability to see all offers',
            ], 
            // ---------------------------down-supp-file
            [
                'name' => 'upload',              
                'guard_name' => 'web',
                'permission_id' => 'down-supp-file',
                'project'=> 'Offers',
                'description' => 'let the user upload a suppression file',
            ],
            [
                'name' => 'supp_file_show',              
                'guard_name' => 'web',
                'permission_id' => 'down-supp-file',
                'project'=> 'Offers',
                'description' => 'See allowed suppression files',
            ],
            [
                'name' => 'all_supp_file_show',              
                'guard_name' => 'web',
                'permission_id' => 'down-supp-file',
                'project'=> 'Offers',
                'description' => 'See all suppression files',
            ],
            // ----------------------------------------leads
            [
                'name' => 'show_leads',
                'guard_name' => 'web',
                'permission_id'=> 'leads',
                'project'=> 'Offers',
                'description' => 'Access to leads page',
            ],
            [
                'name' => 'show_all_leads',
                'guard_name' => 'web',
                'permission_id' => 'leads',
                'project'=> 'Offers',
                'description' => 'See all leads',
            ],
            [
                'name' => 'show_group_leads',
                'guard_name' => 'web',
                'permission_id' => 'leads',
                'project'=> 'Offers',
                'description' => 'See group leads',
            ],
            [
                'name' => 'show_own_leads',
                'guard_name' => 'web',
                'permission_id' => 'leads',
                'project'=> 'Offers',
                'description' => 'See own leads',
            ],
            // -----------------------------------------------------main delivread
            [
                'name' => 'see_spam_page',
                'guard_name' => 'web',
                'permission_id' => 'checkSpamIpPage',
                'project'=> 'Tools',
                'description' => 'Access to CheckSpamIps page',
            ], 
            [
                'name' => 'super_admin',
                'guard_name' => 'web',
                'permission_id'=> 'settings',
                'project'=> 'Tools',
                'description' => 'Has to access to everything in the application',
            ],
            [
                'name' => 'setting',
                'guard_name' => 'web',
                'permission_id'=> 'settings',
                'project'=> 'Tools',
                'description' => 'The Ability to see settings page',
            ],
            // -----------------------Project Stats-----------------------------------------------------------------------------------
            [
                'name' => 'show_own_users_stats',
                'guard_name' => 'web',
                'permission_id'=> 'myWallet',
                'project' => 'Stats',
                'description' => 'Show own user statistiues',
            ],
            [
                'name' => 'show_all_users_stats',
                'guard_name' => 'web',
                'permission_id'=> 'myWallet',
                'project' => 'Stats',
                'description' => 'Show all user statistiues',
            ],
            [
                'name' => 'show_group_users_stats',
                'guard_name' => 'web',
                'permission_id'=> 'myWallet',
                'project' => 'Stats',
                'description' => 'Show group user statistiues',
            ],
            [
                'name' => 'stats',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project' => 'Tools',
                'description' => 'The Ability to access the stats project',
            ],
        ];

        Permission::insert($permissions);

        User::where('email', 'impact@e-impact.com')->delete();
        $user = new User;
        $user->name = 'admin';
        $user->email = 'impact@e-impact.com';
        $user->active = true;
        $user->password = Hash::make('password');
        $user->save();

        Role::where('name', 'admin')->delete();
        $role = new Role;
        $role->name = 'admin';
        $role->guard_name = 'web';
        $role->save();

        $role = Role::where('name', 'admin')->first();
        $user = User::where('email', 'impact@e-impact.com')->first();

        $role->givePermissionTo('super_admin');
        $user->assignRole($role);
    }

}
