<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'dashboard' => [],
            'reports' => ['view_reports'],
            'support_management' => ['create', 'update', 'delete', 'view'],
            'user_management' => ['create', 'update', 'delete', 'view'],
            'role' => ['create', 'update', 'delete', 'view'],
            'activity_logs' => [],
            'permissions' => ['create', 'update', 'delete', 'view'],
            'system_settings' => ['update', 'view'],
            'chat_support' => [],
        ];

        $permissionItems = [];

        foreach ($modules as $moduleName => $actions) {
            $permissionItems[] = [
                'name' => $moduleName,
                'module' => $moduleName,
                'guard_name' => 'sanctum', // Ensure this matches your application's guard
                'status' => 1,
            ];
            foreach ($actions as $action) {
                $permissionItems[] = [
                    'name' => "$moduleName-$action",
                    'module' => $moduleName,
                    'guard_name' => 'sanctum', // Ensure this matches your application's guard
                    'status' => 1,
                ];
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Permission::insert($permissionItems);
    }
}
