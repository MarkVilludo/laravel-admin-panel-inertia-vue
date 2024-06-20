<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // admin role
        $roleAdmin = Role::create([
            'name' => 'Administrator',
            'guard_name' => 'sanctum', // Ensure this matches your application's guard
            'status' => 1,
        ]);

        // Get all permissions with the sanctum guard
        $allPermissions = Permission::where('guard_name', 'sanctum')->pluck('name');
        $roleAdmin->syncPermissions($allPermissions);


        // chat support role
        $roleSupport = Role::create([
            'name' => 'Support',
            'guard_name' => 'sanctum', // Ensure this matches your application's guard
            'status' => 1,
        ]);

        // support permission
        $supportPermissions = Permission::where('name', 'chat_support')->pluck('name');
        $roleSupport->syncPermissions($supportPermissions);
    }
}
