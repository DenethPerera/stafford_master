<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Style;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Permission::firstOrCreate(['name' => 'view-clients']);
        Permission::firstOrCreate(['name' => 'create-clients']);
        Permission::firstOrCreate(['name' => 'edit-clients']);
        Permission::firstOrCreate(['name' => 'delete-clients']);

        $this->user->assignRole($adminRole);

        DB::table('tbl_menu_list')->updateOrInsert(
            ['idtbl_menu_list' => 3],
            ['menu' => 'Clients', 'status' => 1]
        );

        DB::table('tbl_user_privilege')->updateOrInsert(
            [
                'tbl_user_idtbl_user' => $this->user->id,
                'tbl_menu_list_idtbl_menu_list' => 3,
            ],
            [
                'access_status' => 1,
                'add' => 1,
                'edit' => 1,
                'statuschange' => 1,
                'remove' => 1,
                'status' => 1,
                'updatedatetime' => now(),
            ]
        );
    }

    public function test_can_access_clients_index_and_create_client(): void
    {
        $response = $this->actingAs($this->user)->get(route('clients.index'));
        $response->assertStatus(200);

        $storeResponse = $this->actingAs($this->user)->postJson(route('clients.store'), [
            'name'    => 'Test Client Apparel',
            'phone'   => '0771234567',
            'address' => 'Colombo, Sri Lanka',
            'status'  => 'Active',
        ]);

        $storeResponse->assertStatus(200);
        $storeResponse->assertJsonPath('success', true);
        $this->assertDatabaseHas('clients', ['name' => 'Test Client Apparel']);
    }

    public function test_cannot_delete_client_with_downstream_dependencies(): void
    {
        $client = Client::create([
            'name'   => 'Protected Enterprise Client',
            'phone'  => '0779998887',
            'status' => 'Active',
        ]);

        $style = Style::create([
            'client_id'  => $client->id,
            'style_no'   => 'STYLE-DEP-001',
            'style_name' => 'Sample Style for Dependency Test',
            'status'     => 'DEVELOPMENT',
        ]);

        $this->assertTrue($client->hasDownstreamDependencies());

        // Deleting client with downstream Style dependency must be blocked with HTTP 422
        $deleteResponse = $this->actingAs($this->user)->deleteJson(route('clients.destroy', $client));
        $deleteResponse->assertStatus(422);
        $deleteResponse->assertJsonPath('success', false);
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'deleted_at' => null]);
    }

    public function test_can_soft_delete_clean_client_and_toggle_status(): void
    {
        $client = Client::create([
            'name'   => 'Clean Client No Dependency',
            'phone'  => '0770001122',
            'status' => 'Active',
        ]);

        $this->assertFalse($client->hasDownstreamDependencies());

        // Test status toggle to Inactive (Archival)
        $toggleResponse = $this->actingAs($this->user)->patchJson(route('clients.toggle-status', $client));
        $toggleResponse->assertStatus(200);
        $toggleResponse->assertJsonPath('status', 'Inactive');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'status' => 'Inactive']);

        // Test Soft Delete
        $deleteResponse = $this->actingAs($this->user)->deleteJson(route('clients.destroy', $client));
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJsonPath('success', true);

        // Record soft deleted (deleted_at is set, record preserved in database)
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
