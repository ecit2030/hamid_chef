<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\Chef;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Integration Tests for Chef Profile API
 *
 * Feature: system-enhancements
 * Tests: Chef Profile Update API endpoints
 */
class ChefProfileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed location data for all tests
        $this->seed(\Database\Seeders\LocationSeeder::class);
    }

    /**
     * Test successful chef profile update
     *
     * Validates: Requirements 6.3, 6.4
     */
    public function test_chef_can_update_profile_successfully(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Chef',
            'short_description' => 'Original description',
            'base_hourly_rate' => 100.00,
        ]);

        $updateData = [
            'name' => 'Updated Chef',
            'short_description' => 'Updated description',
            'long_description' => 'Updated long description',
            'base_hourly_rate' => 150.00,
        ];

        $response = $this->actingAs($user)->postJson('/api/chef/profile', $updateData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'short_description',
                'long_description',
                'base_hourly_rate',
            ],
        ]);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'name' => 'Updated Chef',
            'short_description' => 'Updated description',
            'base_hourly_rate' => 150.00,
        ]);
    }

    /**
     * Test partial chef profile update
     *
     * Validates: Requirements 6.3
     */
    public function test_chef_can_update_profile_partially(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original Chef',
            'short_description' => 'Original description',
        ]);

        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'name' => 'Updated Name Only',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'name' => 'Updated Name Only',
            'short_description' => 'Original description', // Unchanged
        ]);
    }

    /**
     * Test validation errors
     *
     * Validates: Requirements 6.1, 6.2
     */
    public function test_chef_profile_update_validation_errors(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        // Test invalid base_hourly_rate (negative)
        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'base_hourly_rate' => -10,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['base_hourly_rate']);

        // Test exceeding max length
        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test base_hourly_rate validation
     *
     * Validates: Requirements 6.2
     */
    public function test_base_hourly_rate_validation(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        // Test negative rate
        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'base_hourly_rate' => -50,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['base_hourly_rate']);

        // Test exceeding max
        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'base_hourly_rate' => 10000,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['base_hourly_rate']);

        // Test valid rate
        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'base_hourly_rate' => 200.50,
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test unauthorized access
     *
     * Validates: Requirements 6.5
     */
    public function test_unauthorized_user_cannot_update_chef_profile(): void
    {
        $response = $this->postJson('/api/chef/profile', [
            'name' => 'Unauthorized',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test non-chef user access
     *
     * Validates: Requirements 6.5
     */
    public function test_non_chef_user_cannot_update_chef_profile(): void
    {
        $user = User::factory()->create(['user_type' => 'customer']);

        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'name' => 'Not a chef',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test chef without profile
     */
    public function test_chef_user_without_profile_gets_404(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        // No chef profile created

        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'name' => 'No profile',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test get chef profile endpoint
     */
    public function test_chef_can_get_profile(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Chef',
            'short_description' => 'Test description',
        ]);

        $response = $this->actingAs($user)->getJson('/api/chef/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'short_description',
                'base_hourly_rate',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $chef->id,
                'name' => 'Test Chef',
                'short_description' => 'Test description',
            ],
        ]);
    }

    /**
     * Test location fields update
     *
     * Validates: Requirements 6.1
     */
    public function test_chef_can_update_location_fields(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        // Get actual IDs from seeded data
        $governorate = \App\Models\Governorate::first();
        $district = \App\Models\District::first();
        $area = \App\Models\Area::first();

        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'governorate_id' => $governorate->id,
            'district_id' => $district->id,
            'area_id' => $area->id,
            'address' => 'New Address',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'governorate_id' => $governorate->id,
            'district_id' => $district->id,
            'area_id' => $area->id,
            'address' => 'New Address',
        ]);
    }

    /**
     * Test contact info update
     *
     * Validates: Requirements 6.1
     */
    public function test_chef_can_update_contact_info(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson('/api/chef/profile', [
            'email' => 'newchef@example.com',
            'phone' => '+9876543210',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'email' => 'newchef@example.com',
            'phone' => '+9876543210',
        ]);
    }
}
