<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SpatiePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds for PLM permissions and roles.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definitive PLM permissions list
        $permissions = [
            // Dashboard
            'view-dashboard',

            // PLM Styles Workspace & 10 Sub-Tabs
            'view-styles',
            'create-styles',
            'edit-styles',
            'delete-styles',
            'duplicate-styles',
            'archive-styles',
            'manage-tech-pack',
            'manage-bom',
            'manage-measurements',
            'manage-colorways',
            'manage-samples',
            'manage-artworks',
            'manage-costing',
            'manage-approvals',
            'manage-files',
            'manage-comments',

            // Clients Management
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',

            // Master Files - Colors
            'view-colors',
            'create-colors',
            'edit-colors',
            'delete-colors',

            // Master Files - Sizes
            'view-sizes',
            'create-sizes',
            'edit-sizes',
            'delete-sizes',

            // Master Files - Scales
            'view-scales',
            'create-scales',
            'edit-scales',
            'delete-scales',

            // Master Files - Design Positions
            'view-design-positions',
            'create-design-positions',
            'edit-design-positions',
            'delete-design-positions',

            // Activity Logs
            'view-activity-logs',

            // User & Access Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-roles',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. Create or Update System Roles
        // Super Admin & Admin Roles (Full Access to everything)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // Merchandiser Role (Styles, Tech Packs, BOM, Costing, Samples, Clients, Approvals)
        $merchandiserRole = Role::firstOrCreate(['name' => 'merchandiser', 'guard_name' => 'web']);
        $merchandiserPermissions = [
            'view-dashboard',
            'view-styles', 'create-styles', 'edit-styles', 'duplicate-styles',
            'manage-tech-pack', 'manage-bom', 'manage-measurements', 'manage-colorways',
            'manage-samples', 'manage-artworks', 'manage-costing', 'manage-approvals',
            'manage-files', 'manage-comments',
            'view-clients', 'create-clients', 'edit-clients',
            'view-colors', 'create-colors',
            'view-sizes', 'create-sizes',
            'view-scales',
            'view-design-positions',
            'view-activity-logs',
        ];
        $merchandiserRole->syncPermissions($merchandiserPermissions);

        // Designer Role (Styles, Artworks, Tech Packs, Measurements, Colors)
        $designerRole = Role::firstOrCreate(['name' => 'designer', 'guard_name' => 'web']);
        $designerPermissions = [
            'view-dashboard',
            'view-styles', 'create-styles', 'edit-styles',
            'manage-tech-pack', 'manage-measurements', 'manage-colorways',
            'manage-artworks', 'manage-files', 'manage-comments',
            'view-clients',
            'view-colors', 'create-colors',
            'view-sizes',
            'view-scales',
            'view-design-positions', 'create-design-positions',
        ];
        $designerRole->syncPermissions($designerPermissions);

        // Staff / Viewer Role
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staffPermissions = [
            'view-dashboard',
            'view-styles',
            'view-clients',
            'view-colors',
            'view-sizes',
            'view-scales',
            'view-design-positions',
        ];
        $staffRole->syncPermissions($staffPermissions);

        // 4. Ensure Requested Admin User Exists with Credentials:
        // Username / Email: admin@gmail.com
        // Password: admin@123
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        if (!$adminUser) {
            $adminUser = User::where('username', 'admin')->first();
        }

        if (!$adminUser) {
            $adminUser = User::create([
                'name'                          => 'System Administrator',
                'email'                         => 'admin@gmail.com',
                'username'                      => 'admin',
                'password'                      => Hash::make('admin@123'),
                'status'                        => 'Active',
                'tbl_user_type_idtbl_user_type' => 1,
                'email_verified_at'             => now(),
                'updatedatetime'                => now(),
            ]);
        } else {
            $adminUser->update([
                'name'                          => 'System Administrator',
                'email'                         => 'admin@gmail.com',
                'username'                      => 'admin',
                'password'                      => Hash::make('admin@123'),
                'status'                        => 'Active',
                'tbl_user_type_idtbl_user_type' => 1,
            ]);
        }

        // Assign both super-admin and admin roles
        $adminUser->syncRoles([$superAdminRole, $adminRole]);

        // Also assign super-admin to any existing admin@example.com for backward compatibility
        $legacyAdmin = User::where('email', 'admin@example.com')->first();
        if ($legacyAdmin) {
            $legacyAdmin->syncRoles([$superAdminRole, $adminRole]);
        }

        // 5. Update tbl_user_privilege for custom legacy middleware compatibility
        if (Schema::hasTable('tbl_menu_list') && Schema::hasTable('tbl_user_privilege')) {
            $allMenus = DB::table('tbl_menu_list')->get();
            foreach ($allMenus as $m) {
                DB::table('tbl_user_privilege')->updateOrInsert(
                    [
                        'tbl_user_idtbl_user'           => $adminUser->id,
                        'tbl_menu_list_idtbl_menu_list' => $m->idtbl_menu_list,
                    ],
                    [
                        'access_status'  => 1,
                        'add'            => 1,
                        'edit'           => 1,
                        'statuschange'   => 1,
                        'remove'         => 1,
                        'status'         => 1,
                        'approvestatus'  => 1,
                        'checkstatus'    => 1,
                        'updatedatetime' => now(),
                    ]
                );
            }
        }

        // Log seeding activity
        activity()
            ->causedBy($adminUser)
            ->withProperties(['event' => 'spatie_permissions_seeded', 'roles' => ['super-admin', 'admin', 'merchandiser', 'designer', 'staff']])
            ->log('Spatie PLM Permissions and Roles configured successfully');
    }
}
