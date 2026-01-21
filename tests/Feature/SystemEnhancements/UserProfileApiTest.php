<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Integration Tests for User Profile API
 *
 * Feature: system-enhancements
 * Tests: Profile Update API endpoints
 */
class UserProfileApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed location data for all tests
        $this->seed(\Database\Seeders\LocationSeeder::class);
    }

    /**
     * Test successful profile update
     *
     * Validates: Requirements 5.4, 5.5
     */
    public function test_user_can_update_profile_successfully(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Original',
            'last_name' => 'Name',
            'email' => 'original@example.com',
            'phone_number' => '+1234567890',
            'address' => 'Original Address',
        ]);

        $updateData = [
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => 'updated@example.com',
            'phone_number' => '+9876543210',
            'address' => 'Updated Address',
        ];

        $response = $this->actingAs($user)->postJson('/api/profile', $updateData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'address',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => 'updated@example.com',
            'phone_number' => '+9876543210',
            'address' => 'Updated Address',
        ]);
    }

    /**
     * Test partial profile update
     *
     * Validates: Requirements 5.4
     */
    public function test_user_can_update_profile_partially(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Original',
            'last_name' => 'Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->postJson('/api/profile', [
            'first_name' => 'Updated Only',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated Only',
            'last_name' => 'Name', // Unchanged
            'email' => 'original@example.com', // Unchanged
        ]);
    }

    /**
     * Test validation errors for invalid data
     *
     * Validates: Requirements 5.1, 5.2, 5.3, 5.6
     */
    public function test_profile_update_validation_errors(): void
    {
        $user = User::factory()->create();

        // Test invalid email format
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        // Test invalid phone format
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'phone_number' => 'invalid-phone',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone_number']);

        // Test exceeding max length
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'first_name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Test email uniqueness validation
     *
     * Validates: Requirements 5.7
     */
    public function test_email_uniqueness_validation(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        // Attempt to update to existing email
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        // Verify user's email remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'user@example.com',
        ]);
    }

    /**
     * Test phone uniqueness validation
     *
     * Validates: Requirements 5.8
     */
    public function test_phone_uniqueness_validation(): void
    {
        $existingUser = User::factory()->create([
            'phone_number' => '+1234567890',
        ]);

        $user = User::factory()->create([
            'phone_number' => '+9876543210',
        ]);

        // Attempt to update to existing phone
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'phone_number' => '+1234567890',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone_number']);

        // Verify user's phone remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone_number' => '+9876543210',
        ]);
    }

    /**
     * Test unauthorized access
     *
     * Validates: Requirements 5.6
     */
    public function test_unauthorized_user_cannot_update_profile(): void
    {
        $response = $this->postJson('/api/profile', [
            'first_name' => 'Unauthorized',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test user can keep same email when updating other fields
     *
     * Validates: Requirements 5.7
     */
    public function test_user_can_keep_same_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'first_name' => 'Original',
        ]);

        $response = $this->actingAs($user)->postJson('/api/profile', [
            'email' => 'test@example.com',
            'first_name' => 'Updated',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'first_name' => 'Updated',
        ]);
    }

    /**
     * Test user can keep same phone when updating other fields
     *
     * Validates: Requirements 5.8
     */
    public function test_user_can_keep_same_phone(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+1234567890',
            'first_name' => 'Original',
        ]);

        $response = $this->actingAs($user)->postJson('/api/profile', [
            'phone_number' => '+1234567890',
            'first_name' => 'Updated',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone_number' => '+1234567890',
            'first_name' => 'Updated',
        ]);
    }

    /**
     * Test get profile endpoint
     */
    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        $response = $this->actingAs($user)->getJson('/api/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'address',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ],
        ]);
    }
}
