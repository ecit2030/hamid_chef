<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Tests for Profile Validation
 *
 * Feature: system-enhancements
 * Properties: 4 (Email Uniqueness), 5 (Phone Uniqueness)
 */
class ProfileValidationPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed location data for all tests
        $this->seed(\Database\Seeders\LocationSeeder::class);
    }

    /**
     * Property 4: Email Uniqueness
     *
     * For any two different users, they should not be able to have
     * the same email address in the system.
     *
     * Validates: Requirements 5.7
     */
    public function test_email_uniqueness_property(): void
    {
        // Create first user with a unique email
        $user1 = User::factory()->create([
            'email' => 'unique_' . uniqid() . '@example.com',
        ]);

        // Create second user with different email
        $user2 = User::factory()->create([
            'email' => 'different_' . uniqid() . '@example.com',
        ]);

        // Attempt to update user2's email to user1's email
        $response = $this->actingAs($user2)->postJson('/api/profile', [
            'email' => $user1->email,
        ]);

        // Should fail with validation error
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        // Verify user2's email remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'email' => $user2->email,
        ]);

        // Verify user1's email remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'email' => $user1->email,
        ]);
    }

    /**
     * Property 5: Phone Uniqueness
     *
     * For any two different users, they should not be able to have
     * the same phone number in the system.
     *
     * Validates: Requirements 5.8
     */
    public function test_phone_uniqueness_property(): void
    {
        // Create first user with a unique phone
        $user1 = User::factory()->create([
            'phone_number' => '+1234567' . rand(1000, 9999),
        ]);

        // Create second user with different phone
        $user2 = User::factory()->create([
            'phone_number' => '+9876543' . rand(1000, 9999),
        ]);

        // Attempt to update user2's phone to user1's phone
        $response = $this->actingAs($user2)->postJson('/api/profile', [
            'phone_number' => $user1->phone_number,
        ]);

        // Should fail with validation error
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone_number']);

        // Verify user2's phone remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'phone_number' => $user2->phone_number,
        ]);

        // Verify user1's phone remains unchanged
        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'phone_number' => $user1->phone_number,
        ]);
    }

    /**
     * Test that user can update their own email without uniqueness conflict
     */
    public function test_user_can_keep_same_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Update profile with same email (should succeed)
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'email' => 'test@example.com',
            'first_name' => 'Updated Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'test@example.com',
            'first_name' => 'Updated Name',
        ]);
    }

    /**
     * Test that user can update their own phone without uniqueness conflict
     */
    public function test_user_can_keep_same_phone(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+1234567890',
        ]);

        // Update profile with same phone (should succeed)
        $response = $this->actingAs($user)->postJson('/api/profile', [
            'phone_number' => '+1234567890',
            'first_name' => 'Updated Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone_number' => '+1234567890',
            'first_name' => 'Updated Name',
        ]);
    }

    /**
     * Test email format validation
     */
    public function test_email_format_validation(): void
    {
        $user = User::factory()->create();

        $invalidEmails = [
            'not-an-email',
            '@nodomain.com',
            'spaces in@email.com',
            'double@@domain.com',
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $response = $this->actingAs($user)->postJson('/api/profile', [
                'email' => $invalidEmail,
            ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email']);
        }
    }

    /**
     * Test phone format validation
     */
    public function test_phone_format_validation(): void
    {
        $user = User::factory()->create();

        $invalidPhones = [
            'abc123',
            'phone number',
            '123@456',
        ];

        foreach ($invalidPhones as $invalidPhone) {
            $response = $this->actingAs($user)->postJson('/api/profile', [
                'phone_number' => $invalidPhone,
            ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['phone_number']);
        }
    }
}
