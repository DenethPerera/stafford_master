<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Color;
use App\Models\DesignPosition;
use App\Models\Scale;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Menu List
        $menus = [
            ['idtbl_menu_list' => 1,  'menu' => 'Privileges',              'status' => 1],
            ['idtbl_menu_list' => 2,  'menu' => 'User Types',              'status' => 1],
            ['idtbl_menu_list' => 3,  'menu' => 'User Accounts',           'status' => 1],
            ['idtbl_menu_list' => 10, 'menu' => 'Dashboard',               'status' => 1],
            ['idtbl_menu_list' => 11, 'menu' => 'Styles Workspace (PLM)',  'status' => 1],
            ['idtbl_menu_list' => 21, 'menu' => 'Scales Master',           'status' => 1],
            ['idtbl_menu_list' => 22, 'menu' => 'Colors Master',           'status' => 1],
            ['idtbl_menu_list' => 23, 'menu' => 'Sizes Master',            'status' => 1],
            ['idtbl_menu_list' => 24, 'menu' => 'Design Positions Master', 'status' => 1],
            ['idtbl_menu_list' => 25, 'menu' => 'Clients Master',          'status' => 1],
            ['idtbl_menu_list' => 30, 'menu' => 'Activity Logs',           'status' => 1],
        ];

        foreach ($menus as $menu) {
            DB::table('tbl_menu_list')->updateOrInsert(
                ['idtbl_menu_list' => $menu['idtbl_menu_list']],
                $menu
            );
        }
        $this->command->info("✔ Menus seeded: " . count($menus) . " entries");

        // 2. Seed User Types
        $userTypes = [
            ['idtbl_user_type' => 1, 'type' => 'SUPER ADMIN', 'status' => 'active', 'updatedatetime' => now()],
            ['idtbl_user_type' => 2, 'type' => 'ADMIN',       'status' => 'active', 'updatedatetime' => now()],
            ['idtbl_user_type' => 3, 'type' => 'MANAGER',     'status' => 'active', 'updatedatetime' => now()],
            ['idtbl_user_type' => 4, 'type' => 'STAFF',       'status' => 'active', 'updatedatetime' => now()],
        ];

        foreach ($userTypes as $type) {
            DB::table('tbl_user_type')->updateOrInsert(
                ['idtbl_user_type' => $type['idtbl_user_type']],
                $type
            );
        }
        $this->command->info("✔ User Types seeded: " . count($userTypes) . " entries");

        // 3. Super Admin & Admin Users
        $superAdmin = DB::table('users')->where('email', 'superadmin@gmail.com')->first();
        if (!$superAdmin) {
            $sId = DB::table('users')->insertGetId([
                'name'                          => 'Super Admin',
                'email'                         => 'superadmin@gmail.com',
                'username'                      => 'superadmin',
                'password'                      => Hash::make('admin@4321'),
                'status'                        => 'Active',
                'tbl_user_type_idtbl_user_type' => 1,
                'email_verified_at'             => now(),
                'updatedatetime'                => now(),
                'created_at'                    => now(),
                'updated_at'                    => now(),
            ]);
        } else {
            $sId = $superAdmin->id;
        }

        $admin = DB::table('users')->where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $aId = DB::table('users')->insertGetId([
                'name'                          => 'Admin User',
                'email'                         => 'admin@gmail.com',
                'username'                      => 'admin',
                'password'                      => Hash::make('admin@123'),
                'status'                        => 'Active',
                'tbl_user_type_idtbl_user_type' => 2,
                'email_verified_at'             => now(),
                'updatedatetime'                => now(),
                'created_at'                    => now(),
                'updated_at'                    => now(),
            ]);
        } else {
            $aId = $admin->id;
        }
        $this->command->info("✔ Users created: superadmin@gmail.com (admin@4321), admin@gmail.com (admin@123)");

        // 4. Assign privileges
        foreach ([$sId, $aId] as $uId) {
            foreach ($menus as $m) {
                DB::table('tbl_user_privilege')->updateOrInsert(
                    [
                        'tbl_user_idtbl_user'          => $uId,
                        'tbl_menu_list_idtbl_menu_list' => $m['idtbl_menu_list'],
                    ],
                    [
                        'access_status'  => '1',
                        'add'            => '1',
                        'edit'           => '1',
                        'statuschange'   => '1',
                        'remove'         => '1',
                        'status'         => 'active',
                        'approvestatus'  => '1',
                        'checkstatus'    => '1',
                        'updatedatetime' => now(),
                    ]
                );
            }
        }

        // 5. Material Units & Categories
        $units = [
            ['unit_name' => 'Inch', 'unit_symbol' => 'in', 'status' => 'Active'],
            ['unit_name' => 'Centimeter', 'unit_symbol' => 'cm', 'status' => 'Active'],
            ['unit_name' => 'Pieces', 'unit_symbol' => 'Pcs', 'status' => 'Active'],
            ['unit_name' => 'Meter', 'unit_symbol' => 'Mtr', 'status' => 'Active'],
            ['unit_name' => 'Yards', 'unit_symbol' => 'Yds', 'status' => 'Active'],
            ['unit_name' => 'Kilogram', 'unit_symbol' => 'Kg', 'status' => 'Active'],
            ['unit_name' => 'Pack', 'unit_symbol' => 'Pk', 'status' => 'Active'],
        ];
        foreach ($units as $u) {
            DB::table('material_units')->updateOrInsert(['unit_name' => $u['unit_name']], $u);
        }

        $cats = [
            ['name' => "Men's Sweatshirts", 'code' => 'SWT', 'status' => 'active'],
            ['name' => 'Hoodies', 'code' => 'HD', 'status' => 'active'],
            ['name' => 'Jackets', 'code' => 'JKT', 'status' => 'active'],
            ['name' => 'T-Shirts', 'code' => 'TSH', 'status' => 'active'],
            ['name' => 'Polos', 'code' => 'PLO', 'status' => 'active'],
            ['name' => 'Pants', 'code' => 'PNT', 'status' => 'active'],
            ['name' => 'Shorts', 'code' => 'SHR', 'status' => 'active'],
            ['name' => 'Outerwear', 'code' => 'OUT', 'status' => 'active'],
            ['name' => 'Activewear', 'code' => 'ACT', 'status' => 'active'],
        ];
        foreach ($cats as $c) {
            DB::table('material_categories')->updateOrInsert(['name' => $c['name']], $c);
        }

        // 6. Scales Master
        $scales = [
            ['name' => 'Adults Standard (S - XL)', 'percentage_value' => 100.00, 'status' => 'Active'],
            ['name' => 'Extended Sizes (XS - 6XL)', 'percentage_value' => 100.00, 'status' => 'Active'],
            ['name' => 'Kids & Youth (4 - 14)', 'percentage_value' => 85.00, 'status' => 'Active'],
            ['name' => 'Big & Tall (2XLT - 5XLT)', 'percentage_value' => 115.00, 'status' => 'Active'],
        ];
        foreach ($scales as $sc) {
            Scale::updateOrInsert(['name' => $sc['name']], $sc);
        }

        // 7. Colors Master
        $colors = [
            ['name' => 'Black', 'code' => 'BLK-01', 'hex_code' => '#18181b', 'status' => 'Active'],
            ['name' => 'Dark Grey Heather', 'code' => 'DGH-02', 'hex_code' => '#3f3f46', 'status' => 'Active'],
            ['name' => 'Navy', 'code' => 'NVY-03', 'hex_code' => '#1e3a8a', 'status' => 'Active'],
            ['name' => 'Safety Yellow', 'code' => 'SYL-04', 'hex_code' => '#eab308', 'status' => 'Active'],
            ['name' => 'Forest Green', 'code' => 'FGN-05', 'hex_code' => '#14532d', 'status' => 'Active'],
            ['name' => 'Red', 'code' => 'RED-06', 'hex_code' => '#b91c1c', 'status' => 'Active'],
            ['name' => 'White', 'code' => 'WHT-07', 'hex_code' => '#ffffff', 'status' => 'Active'],
            ['name' => 'Royal Blue', 'code' => 'RBL-08', 'hex_code' => '#2563eb', 'status' => 'Active'],
        ];
        foreach ($colors as $col) {
            Color::updateOrInsert(['name' => $col['name']], $col);
        }

        // 8. Sizes Master
        $sizes = [
            ['name' => 'XS', 'code' => 'XS', 'sort_order' => 1, 'status' => 'Active'],
            ['name' => 'S',  'code' => 'S',  'sort_order' => 2, 'status' => 'Active'],
            ['name' => 'M',  'code' => 'M',  'sort_order' => 3, 'status' => 'Active'],
            ['name' => 'L',  'code' => 'L',  'sort_order' => 4, 'status' => 'Active'],
            ['name' => 'XL', 'code' => 'XL', 'sort_order' => 5, 'status' => 'Active'],
            ['name' => '2XL','code' => '2XL','sort_order' => 6, 'status' => 'Active'],
            ['name' => '3XL','code' => '3XL','sort_order' => 7, 'status' => 'Active'],
            ['name' => '4XL','code' => '4XL','sort_order' => 8, 'status' => 'Active'],
            ['name' => '5XL','code' => '5XL','sort_order' => 9, 'status' => 'Active'],
            ['name' => '6XL','code' => '6XL','sort_order' => 10, 'status' => 'Active'],
        ];
        foreach ($sizes as $sz) {
            Size::updateOrInsert(['name' => $sz['name']], $sz);
        }

        // 9. Design Positions Master
        $positions = [
            ['name' => 'Center Chest', 'code' => 'POS-CC', 'status' => 'Active'],
            ['name' => 'Left Chest', 'code' => 'POS-LC', 'status' => 'Active'],
            ['name' => 'Full Back', 'code' => 'POS-FB', 'status' => 'Active'],
            ['name' => 'Back Neck', 'code' => 'POS-BN', 'status' => 'Active'],
            ['name' => 'Left Sleeve', 'code' => 'POS-LS', 'status' => 'Active'],
            ['name' => 'Right Sleeve', 'code' => 'POS-RS', 'status' => 'Active'],
            ['name' => 'Hood Center', 'code' => 'POS-HC', 'status' => 'Active'],
            ['name' => 'Kangaroo Pocket', 'code' => 'POS-KP', 'status' => 'Active'],
        ];
        foreach ($positions as $pos) {
            DesignPosition::updateOrInsert(['name' => $pos['name']], $pos);
        }

        // 10. Sample Clients
        $clients = [
            ['name' => 'AlphaBroder Inc.', 'code' => 'ABRO', 'email' => 'orders@alphabroder.com', 'phone' => '1-800-523-4585', 'address' => '600 Washington Ave, Trevose, PA', 'contact_person' => 'Mark Jenkins', 'status' => 'Active'],
            ['name' => 'SanMar Corporation', 'code' => 'SMAR', 'email' => 'sales@sanmar.com', 'phone' => '1-800-426-6399', 'address' => '22833 SE Black Nugget Rd, Issaquah, WA', 'contact_person' => 'Lisa Ray', 'status' => 'Active'],
            ['name' => 'Next Level Apparel', 'code' => 'NLVL', 'email' => 'support@nextlevel.com', 'phone' => '1-310-631-4155', 'address' => 'Gardena, CA', 'contact_person' => 'David Lee', 'status' => 'Active'],
        ];
        foreach ($clients as $cl) {
            Client::updateOrInsert(['name' => $cl['name']], $cl);
        }

        // 11. Stafford Data Bridge (Real DB Data -> PLM)
        if (class_exists(StaffordDataBridgeSeeder::class)) {
            $this->call(StaffordDataBridgeSeeder::class);
        }

        // 12. Spatie Permissions
        if (class_exists(SpatiePermissionSeeder::class)) {
            $this->call(SpatiePermissionSeeder::class);
        }

        // 13. Complete PLM Style Demo (TT96)
        if (class_exists(StylePlmSeeder::class)) {
            $this->call(StylePlmSeeder::class);
        }

        $this->command->info("🎉 STAF PLM Database seeding completed successfully!");
    }
}
