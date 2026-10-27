<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\DesignPosition;
use App\Models\Scale;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MastersTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permissions = [
            'view-scales', 'create-scales', 'edit-scales', 'delete-scales',
            'view-colors', 'create-colors', 'edit-colors', 'delete-colors',
            'view-sizes', 'create-sizes', 'edit-sizes', 'delete-sizes',
            'view-design-positions', 'create-design-positions', 'edit-design-positions', 'delete-design-positions',
        ];
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }
        $adminRole->givePermissionTo($permissions);

        $this->adminUser = User::factory()->create([
            'tbl_user_type_idtbl_user_type' => 1
        ]);
        $this->adminUser->assignRole($adminRole);
    }

    public function test_can_manage_scales(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('masters.scales.index'));
        $response->assertStatus(200);

        $createRes = $this->actingAs($this->adminUser)->postJson(route('masters.scales.store'), [
            'name'             => 'Test Alpha Scale',
            'percentage_value' => 100,
            'status'           => 'Active',
        ]);
        $createRes->assertStatus(200);
        $this->assertDatabaseHas('scales', ['name' => 'Test Alpha Scale']);
    }

    public function test_can_manage_colors(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('masters.colors.index'));
        $response->assertStatus(200);

        $createRes = $this->actingAs($this->adminUser)->postJson(route('masters.colors.store'), [
            'name'     => 'Crimson Red',
            'code'     => 'CR-01',
            'hex_code' => '#dc2626',
            'status'   => 'Active',
        ]);
        $createRes->assertStatus(200);
        $this->assertDatabaseHas('colors', ['name' => 'Crimson Red']);
    }

    public function test_can_manage_sizes(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('masters.sizes.index'));
        $response->assertStatus(200);

        $createRes = $this->actingAs($this->adminUser)->postJson(route('masters.sizes.store'), [
            'name'       => '3XL',
            'code'       => '3XL',
            'sort_order' => 7,
            'status'     => 'Active',
        ]);
        $createRes->assertStatus(200);
        $this->assertDatabaseHas('sizes', ['name' => '3XL']);
    }

    public function test_can_manage_design_positions(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('masters.design-positions.index'));
        $response->assertStatus(200);

        $createRes = $this->actingAs($this->adminUser)->postJson(route('masters.design-positions.store'), [
            'name'   => 'Left Chest Pocket',
            'code'   => 'POS-LCP',
            'status' => 'Active',
        ]);
        $createRes->assertStatus(200);
        $this->assertDatabaseHas('design_positions', ['name' => 'Left Chest Pocket']);
    }
}
