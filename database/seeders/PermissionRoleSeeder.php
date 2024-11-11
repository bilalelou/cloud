<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permision = new Permission();

         $permision->name = "create_idcloudhost_server";
         $permision->permission_id = "CloudServers";

         $permision->save();
        }
    }

