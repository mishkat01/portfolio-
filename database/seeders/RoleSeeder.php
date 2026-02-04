<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Role: Super Admin
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        // Create Role: Editor
        $editorRole = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);

        // Create Permissions
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-admins',
            'manage-roles',
            'manage-settings',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create([
                'name' => ucfirst(str_replace('-', ' ', $permissionName)),
                'slug' => $permissionName,
            ]);

            // Assign all permissions to Super Admin
            $superAdminRole->permissions()->attach($permission);
        }

        // Assign specific permissions to Editor
        $editorPermissions = Permission::whereIn('slug', ['view-dashboard', 'manage-users'])->get();
        $editorRole->permissions()->attach($editorPermissions);
    }
}
