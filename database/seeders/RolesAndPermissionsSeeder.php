<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // User Management Permissions
        $userPermissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'export_users',
            'import_users',
            'manage_user_roles',
        ];

        // Role Management Permissions
        $rolePermissions = [
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',
        ];

        // Settings Permissions
        $settingPermissions = [
            'view_settings',
            'edit_settings',
            'manage_backups',
            'manage_logs',
        ];

        // Content Management Permissions
        $contentPermissions = [
            'view_content',
            'create_content',
            'edit_content',
            'delete_content',
            'publish_content',
        ];

        // Report Permissions
        $reportPermissions = [
            'view_reports',
            'create_reports',
            'export_reports',
            'view_analytics',
        ];

        // API Permissions
        $apiPermissions = [
            'api_access',
            'api_full_access',
        ];

        // Create Permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $settingPermissions,
            $contentPermissions,
            $reportPermissions,
            $apiPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Define roles with their permissions
        $roles = [
            'Super Admin' => $allPermissions,

            'Administrator' => array_merge(
                $userPermissions,
                $rolePermissions,
                $settingPermissions,
                ['view_reports', 'export_reports', 'view_analytics']
            ),

            'Manager' => array_merge(
                ['view_users', 'edit_users', 'export_users'],
                ['view_roles'],
                ['view_content', 'edit_content', 'publish_content'],
                ['view_reports', 'view_analytics']
            ),

            'Developer' => array_merge(
                ['view_users'],
                $apiPermissions,
                ['view_reports']
            ),

            'Content Editor' => array_merge(
                $contentPermissions,
                ['view_analytics']
            ),

            'HR Manager' => [
                'view_users',
                'create_users',
                'edit_users',
                'export_users',
                'import_users',
                'view_reports',
                'create_reports',
                'export_reports'
            ],

            'Support Agent' => [
                'view_users',
                'view_content',
                'view_reports'
            ],

            'Sales Representative' => [
                'view_users',
                'view_content',
                'view_reports',
                'view_analytics'
            ],

            'Customer' => [
                'view_content'
            ],

            'RestrictedUser' => [
                'view_content'
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($permissions);
        }

        // Create default admin user
        $admin = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('Super Admin');
    }
}
