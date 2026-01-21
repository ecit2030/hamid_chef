<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Property-Based Test for Profile Update Idempotence
 *
 * Feature: system-enhancements
 * Property: 3 (Profile Update Idempotence)
 */
class ProfileUpdateIdempotencePropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed location data for all tests
        $this->seed(\Database\Seeders\LocationSeeder::class);
    }

    /**
     * Property 3: Profile Update Idempotence
     *
     * For any user profile, updating it with the same data twice should
     * result in the same final state as updating it once.
     *
     * Validates: Requirements 5.4
     */
    public function test_profile_update_idempotence_property(): void
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

        // First update
        $response1 = $this->actingAs($user)->postJson('/api/profile', $updateData);
        $response1->assertStatus(200);

        // Get state after first update
        $user->refresh();
        $stateAfterFirstUpdate = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
        ];

        // Second update with same data
        $response2 = $this->actingAs($user)->postJson('/api/profile', $updateData);
        $response2->assertStatus(200);

        // Get state after second update
        $user->refresh();
        $stateAfterSecondUpdate = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
        ];

        // Assert idempotence: both states should be identical
        $this->assertEquals($stateAfterFirstUpdate, $stateAfterSecondUpdate);

        // Verify final state matches update data
        $this->assertEquals($updateData['first_name'], $user->first_name);
        $this->assertEquals($updateData['last_name'], $user->last_name);
        $this->assertEquals($updateData['email'], $user->email);
        $this->assertEquals($updateData['phone_number'], $user->phone_number);
        $this->assertEquals($updateData['address'], $user->address);
    }

    /**
     * Test idempotence with partial updates
     */
    public function test_partial_update_idempotence(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Original',
            'last_name' => 'Name',
            'email' => 'original@example.com',
        ]);

        $partialUpdate = [
            'first_name' => 'Updated First',
        ];

        // First update
        $response1 = $this->actingAs($user)->postJson('/api/profile', $partialUpdate);
        $response1->assertStatus(200);

        $user->refresh();
        $stateAfterFirst = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];

        // Second update with same data
        $response2 = $this->actingAs($user)->postJson('/api/profile', $partialUpdate);
        $response2->assertStatus(200);

        $user->refresh();
        $stateAfterSecond = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];

        // Assert idempotence
        $this->assertEquals($stateAfterFirst, $stateAfterSecond);

        // Verify only first_name changed
        $this->assertEquals('Updated First', $user->first_name);
        $this->assertEquals('Name', $user->last_name);
        $this->assertEquals('original@example.com', $user->email);
    }

    /**
     * Test idempotence with multiple iterations
     */
    public function test_multiple_iterations_idempotence(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'first_name' => 'Consistent',
            'last_name' => 'Update',
        ];

        $states = [];

        // Perform 5 updates with same data
        for ($i = 0; $i < 5; $i++) {
            $response = $this->actingAs($user)->postJson('/api/profile', $updateData);
            $response->assertStatus(200);

            $user->refresh();
            $states[] = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ];
        }

        // All states should be identical
        $firstState = $states[0];
        foreach ($states as $state) {
            $this->assertEquals($firstState, $state);
        }
    }
}
