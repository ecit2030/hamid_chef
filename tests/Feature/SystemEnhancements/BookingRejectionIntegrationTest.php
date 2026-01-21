<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\Booking;
use App\Models\Chef;
use App\Models\User;
use App\Models\ChefService;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRejectionIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $chefUser;
    protected Chef $chef;
    protected User $customer;
    protected ChefService $service;
    protected Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->customer = User::factory()->create(['user_type' => 'customer']);
        $this->chefUser = User::factory()->create(['user_type' => 'chef']);
        $this->chef = Chef::factory()->create([
            'user_id' => $this->chefUser->id,
            'is_active' => true,
        ]);
        $this->service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $this->address = Address::factory()->create(['user_id' => $this->customer->id]);
    }

    /**
     * Test rejection with valid reason
     *
     * Requirements: 1.1, 1.2
     */
    public function test_rejection_with_valid_reason(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $rejectionReason = 'I am not available on this date due to a family emergency.';

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => $rejectionReason,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify booking was rejected with reason
        $booking->refresh();
        $this->assertEquals('rejected', $booking->booking_status);
        $this->assertEquals($rejectionReason, $booking->rejection_reason);
        $this->assertEquals(0, $booking->is_active); // Database stores false as 0
    }

    /**
     * Test rejection with empty reason (should fail)
     *
     * Requirements: 1.3, 1.4
     */
    public function test_rejection_with_empty_reason_fails(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => '',
            ]);

        $response->assertSessionHasErrors('rejection_reason');

        // Verify booking was NOT rejected
        $booking->refresh();
        $this->assertEquals('pending', $booking->booking_status);
        $this->assertNull($booking->rejection_reason);
    }

    /**
     * Test rejection with whitespace-only reason (should fail)
     *
     * Requirements: 1.5
     */
    public function test_rejection_with_whitespace_only_reason_fails(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $whitespaceReasons = [
            ' ',
            '  ',
            "\t",
            "\n",
            " \t\n ",
        ];

        foreach ($whitespaceReasons as $whitespaceReason) {
            $response = $this->actingAs($this->chefUser, 'chef')
                ->patch(route('chef.bookings.reject', $booking->id), [
                    'rejection_reason' => $whitespaceReason,
                ]);

            $response->assertSessionHasErrors('rejection_reason');

            // Verify booking was NOT rejected
            $booking->refresh();
            $this->assertEquals('pending', $booking->booking_status);
            $this->assertNull($booking->rejection_reason);
        }
    }

    /**
     * Test rejection with reason exceeding 500 chars (should fail)
     *
     * Requirements: 1.4
     */
    public function test_rejection_with_reason_exceeding_max_length_fails(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        // Generate a string with 501 characters
        $longReason = str_repeat('a', 501);

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => $longReason,
            ]);

        $response->assertSessionHasErrors('rejection_reason');

        // Verify booking was NOT rejected
        $booking->refresh();
        $this->assertEquals('pending', $booking->booking_status);
        $this->assertNull($booking->rejection_reason);
    }

    /**
     * Test that chef cannot reject another chef's booking
     */
    public function test_chef_cannot_reject_another_chefs_booking(): void
    {
        // Create another chef
        $otherChefUser = User::factory()->create(['user_type' => 'chef']);
        $otherChef = Chef::factory()->create(['user_id' => $otherChefUser->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $otherChef->id,
            'chef_service_id' => ChefService::factory()->create(['chef_id' => $otherChef->id])->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => 'Valid reason',
            ]);

        $response->assertForbidden();

        // Verify booking was NOT rejected
        $booking->refresh();
        $this->assertEquals('pending', $booking->booking_status);
        $this->assertNull($booking->rejection_reason);
    }

    /**
     * Test that unauthenticated user cannot reject booking
     */
    public function test_unauthenticated_user_cannot_reject_booking(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $response = $this->patch(route('chef.bookings.reject', $booking->id), [
            'rejection_reason' => 'Valid reason',
        ]);

        $response->assertRedirect(route('chef.login'));

        // Verify booking was NOT rejected
        $booking->refresh();
        $this->assertEquals('pending', $booking->booking_status);
        $this->assertNull($booking->rejection_reason);
    }

    /**
     * Test rejection with valid reason at minimum length (1 char)
     */
    public function test_rejection_with_minimum_length_reason(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $rejectionReason = 'a'; // 1 character

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => $rejectionReason,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify booking was rejected with reason
        $booking->refresh();
        $this->assertEquals('rejected', $booking->booking_status);
        $this->assertEquals($rejectionReason, $booking->rejection_reason);
    }

    /**
     * Test rejection with valid reason at maximum length (500 chars)
     */
    public function test_rejection_with_maximum_length_reason(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'chef_id' => $this->chef->id,
            'chef_service_id' => $this->service->id,
            'address_id' => $this->address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $rejectionReason = str_repeat('a', 500); // 500 characters

        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.reject', $booking->id), [
                'rejection_reason' => $rejectionReason,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify booking was rejected with reason
        $booking->refresh();
        $this->assertEquals('rejected', $booking->booking_status);
        $this->assertEquals($rejectionReason, $booking->rejection_reason);
    }
}
