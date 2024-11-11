<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class SeedersPermissionsCloudProject extends Seeder
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
                'name' => 'create_digitalocean_server',
                'guard_name' => 'web',
                'permission_id'=> 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create digitalocean server',
            ],
            [
                'name' => 'cloud',
                'guard_name' => 'web',
                'permission_id'=> 'projects',
                'project'=> 'Tools',
                'description' => 'The Ability to access the cloud project',
            ],
            [
                'name' => 'create_linode_server',
                'guard_name' => 'web',
                'permission_id'=> 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create linode server',
            ],
            [
                'name' => 'create_hetzner_server',
                'guard_name' => 'web',
                'permission_id'=> 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create hetzner server',
            ],
            [
                'name' => 'create_azure_server',
                'guard_name' => 'web',
                'permission_id'=> 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create azure server',
            ],
            [
                'name' => 'create_kamatera_server',
                'guard_name' => 'web',
                'permission_id'=> 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create kamatera server',
            ],
            [
                'name' => 'create_cloud_server',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'Gives the user the ability to create cloud server',
            ],
            [
                'name' => 'delete_cloud_server',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'The ability to delete cloud server',
            ],
            [
                'name' => 'update_servers_rsa',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'The ability to update servers rsa',
            ],
            [
                'name' => 'change_servers_password',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'The ability to change servers password',
            ], 
            [
                'name' => 'see_settings',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'The Ability to see settings page',
            ],            
            [
                'name' => 'see_servers',
                'guard_name' => 'web',
                'permission_id' => 'CloudServers',
                'project'=> 'Cloud',
                'description' => 'The Ability to see servers page',
            ],
            [
                'name' => 'see_providers',
                'guard_name' => 'web',
                'permission_id' => 'CloudServersProviders',
                'project'=> 'Cloud',
                'description' => 'The Ability to see providers page',
            ],
            [
                'name' => 'create_cloud_provider',
                'display_name' => 'web',
                'permission_id' => 'CloudServersProviders',
                'project'=> 'Cloud',
                'description' => 'The ability to create cloud provider',
            ],
            [
                'name' => 'delete_cloud_provider',
                'guard_name' => 'web',
                'permission_id' => 'CloudServersProviders',
                'project'=> 'Cloud',
                'description' => 'The ability to delete cloud provider',
            ],
        ];
        Permission::insert($permissions);
    }
}