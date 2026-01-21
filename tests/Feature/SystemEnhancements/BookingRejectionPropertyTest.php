<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\Booking;
use App\Models\Chef;
use App\Models\User;
use App\Models\ChefService;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRejectionPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Feature: system-enhancements, Property 1: Rejection Reason Persistence
     *
     * For any booking that is rejected with a reason, retrieving that booking
     * should return the same rejection reason that was provided during rejection.
     *
     * Validates: Requirements 1.2, 2.1
     */
    public function test_rejection_reason_persistence(): void
    {
        // Run this test 100 times with different random data
        for ($i = 0; $i < 100; $i++) {
            // Create necessary related models
            $customer = User::factory()->create(['user_type' => 'customer']);
            $chefUser = User::factory()->create(['user_type' => 'chef']);
            $chef = Chef::factory()->create(['user_id' => $chefUser->id]);
            $service = ChefService::factory()->create(['chef_id' => $chef->id]);
            $address = Address::factory()->create(['user_id' => $customer->id]);

            // Generate random rejection reason (5-500 characters)
            $rejectionReason = fake()->text(rand(5, 500));

            // Create a booking
            $booking = Booking::factory()->create([
                'customer_id' => $customer->id,
                'chef_id' => $chef->id,
                'chef_service_id' => $service->id,
                'address_id' => $address->id,
                'booking_status' => 'pending',
                'rejection_reason' => null,
            ]);

            // Update booking with rejection reason
            $booking->update([
                'booking_status' => 'rejected',
                'rejection_reason' => $rejectionReason,
            ]);

            // Retrieve the booking from database
            $retrievedBooking = Booking::find($booking->id);

            // Assert: The retrieved rejection reason should match the original
            $this->assertEquals(
                $rejectionReason,
                $retrievedBooking->rejection_reason,
                "Rejection reason persistence failed on iteration {$i}"
            );

            // Assert: Booking status should be rejected
            $this->assertEquals('rejected', $retrievedBooking->booking_status);
        }
    }

    /**
     * Test that rejection_reason can be null for non-rejected bookings
     */
    public function test_rejection_reason_can_be_null(): void
    {
        $customer = User::factory()->create(['user_type' => 'customer']);
        $chefUser = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $chefUser->id]);
        $service = ChefService::factory()->create(['chef_id' => $chef->id]);
        $address = Address::factory()->create(['user_id' => $customer->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'chef_id' => $chef->id,
            'chef_service_id' => $service->id,
            'address_id' => $address->id,
            'booking_status' => 'pending',
            'rejection_reason' => null,
        ]);

        $this->assertNull($booking->rejection_reason);
    }

    /**
     * Test that rejection_reason persists through model refresh
     */
    public function test_rejection_reason_persists_through_refresh(): void
    {
        $customer = User::factory()->create(['user_type' => 'customer']);
        $chefUser = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $chefUser->id]);
        $service = ChefService::factory()->create(['chef_id' => $chef->id]);
        $address = Address::factory()->create(['user_id' => $customer->id]);

        $rejectionReason = 'Test rejection reason';

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'chef_id' => $chef->id,
            'chef_service_id' => $service->id,
            'address_id' => $address->id,
            'booking_status' => 'rejected',
            'rejection_reason' => $rejectionReason,
        ]);

        // Refresh the model from database
        $booking->refresh();

        $this->assertEquals($rejectionReason, $booking->rejection_reason);
    }
}
