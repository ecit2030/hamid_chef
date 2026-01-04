<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Chef;
use App\Models\ChefService;
use App\Models\ChefServiceRating;
use App\Models\ChefWallet;
use App\Models\User;
use App\Services\ChefDashboardService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Chef Dashboard Statistics Tests
 * 
 * Feature: chef-panel-authentication
 * Property 6: Dashboard Statistics Accuracy
 * Validates: Requirements 5.2, 5.3, 5.4, 5.5
 * 
 * For any chef, the dashboard statistics (total_bookings, pending_bookings, 
 * total_earnings, average_rating) should accurately reflect the aggregated 
 * data from the database for that specific chef.
 */
class ChefDashboardStatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected ChefDashboardService $dashboardService;
    protected User $chefUser;
    protected Chef $chef;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardService = app(ChefDashboardService::class);
        
        // Create a chef user with profile
        $this->chefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);
        
        $this->chef = Chef::factory()->create([
            'user_id' => $this->chefUser->id,
            'is_active' => true,
        ]);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Total Bookings
     * For any chef, total_bookings should equal the count of all active bookings for that chef.
     * 
     * @test
     */
    public function total_bookings_accurately_reflects_database_count(): void
    {
        // Arrange: Create bookings for this chef
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create 5 active bookings
        for ($i = 0; $i < 5; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'accepted',
            ]);
        }
        
        // Create 2 inactive bookings (should not be counted)
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => false,
                'booking_status' => 'accepted',
            ]);
        }

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Total bookings should be 5 (only active)
        $this->assertEquals(5, $stats['total_bookings']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Pending Bookings
     * For any chef, pending_bookings should equal the count of bookings with status 'pending'.
     * 
     * @test
     */
    public function pending_bookings_accurately_reflects_database_count(): void
    {
        // Arrange: Create bookings with different statuses
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create 3 pending bookings
        for ($i = 0; $i < 3; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'pending',
            ]);
        }
        
        // Create 2 accepted bookings (confirmed = accepted in this system)
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'accepted',
            ]);
        }

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Pending bookings should be 3
        $this->assertEquals(3, $stats['pending_bookings']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Total Earnings
     * For any chef, total_earnings should equal the sum of total_amount for completed bookings.
     * 
     * @test
     */
    public function total_earnings_accurately_reflects_completed_bookings_sum(): void
    {
        // Arrange: Create completed bookings with known amounts
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $amounts = [100.00, 150.50, 200.00, 75.25];
        $expectedTotal = array_sum($amounts);
        
        foreach ($amounts as $amount) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'completed',
                'total_amount' => $amount,
            ]);
        }
        
        // Create a pending booking (should not be counted in earnings)
        Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $service->id,
            'customer_id' => $customer->id,
            'is_active' => true,
            'booking_status' => 'pending',
            'total_amount' => 500.00,
        ]);

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Total earnings should equal sum of completed bookings only
        $this->assertEquals($expectedTotal, $stats['total_earnings']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Average Rating
     * For any chef, average_rating should equal the average of all active ratings.
     * 
     * @test
     */
    public function average_rating_accurately_reflects_database_average(): void
    {
        // Arrange: Create ratings with known values
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $ratings = [5, 4, 4, 5, 3]; // Average = 4.2
        $expectedAverage = round(array_sum($ratings) / count($ratings), 1);
        
        foreach ($ratings as $rating) {
            $booking = Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'completed',
            ]);
            
            ChefServiceRating::factory()->create([
                'booking_id' => $booking->id,
                'chef_id' => $this->chef->id,
                'customer_id' => $customer->id,
                'rating' => $rating,
                'is_active' => true,
            ]);
        }
        
        // Create an inactive rating (should not be counted)
        $booking = Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $service->id,
            'customer_id' => $customer->id,
        ]);
        
        ChefServiceRating::factory()->create([
            'booking_id' => $booking->id,
            'chef_id' => $this->chef->id,
            'customer_id' => $customer->id,
            'rating' => 1, // Low rating that should not affect average
            'is_active' => false,
        ]);

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Average rating should be 4.2
        $this->assertEquals($expectedAverage, $stats['average_rating']);
        $this->assertEquals(5, $stats['total_reviews']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Monthly Bookings
     * For any chef, monthly_bookings should equal bookings in the current month.
     * 
     * @test
     */
    public function monthly_bookings_accurately_reflects_current_month(): void
    {
        // Arrange: Create bookings in current and previous months
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $now = Carbon::now();
        
        // Create 4 bookings this month
        for ($i = 0; $i < 4; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'accepted',
                'date' => $now->copy()->startOfMonth()->addDays($i),
            ]);
        }
        
        // Create 2 bookings last month
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'accepted',
                'date' => $now->copy()->subMonth()->startOfMonth()->addDays($i),
            ]);
        }

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Monthly bookings should be 4
        $this->assertEquals(4, $stats['monthly_bookings']);
        $this->assertEquals(6, $stats['total_bookings']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Monthly Earnings
     * For any chef, monthly_earnings should equal completed bookings sum in current month.
     * 
     * @test
     */
    public function monthly_earnings_accurately_reflects_current_month(): void
    {
        // Arrange: Create completed bookings in current and previous months
        $service = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $now = Carbon::now();
        
        // Create completed bookings this month
        $thisMonthAmounts = [100.00, 200.00];
        foreach ($thisMonthAmounts as $i => $amount) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $service->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'completed',
                'total_amount' => $amount,
                'date' => $now->copy()->startOfMonth()->addDays($i),
            ]);
        }
        
        // Create completed booking last month
        Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $service->id,
            'customer_id' => $customer->id,
            'is_active' => true,
            'booking_status' => 'completed',
            'total_amount' => 500.00,
            'date' => $now->copy()->subMonth()->startOfMonth(),
        ]);

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Monthly earnings should be 300.00
        $this->assertEquals(300.00, $stats['monthly_earnings']);
        $this->assertEquals(800.00, $stats['total_earnings']);
    }

    /**
     * Property 6: Dashboard Statistics Accuracy - Services Count
     * For any chef, total_services and active_services should accurately reflect database.
     * 
     * @test
     */
    public function services_count_accurately_reflects_database(): void
    {
        // Arrange: Create services with different active states
        // Create 3 active services
        for ($i = 0; $i < 3; $i++) {
            ChefService::factory()->create([
                'chef_id' => $this->chef->id,
                'is_active' => true,
            ]);
        }
        
        // Create 2 inactive services
        for ($i = 0; $i < 2; $i++) {
            ChefService::factory()->create([
                'chef_id' => $this->chef->id,
                'is_active' => false,
            ]);
        }

        // Act: Get statistics
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Services counts should be accurate
        $this->assertEquals(5, $stats['total_services']);
        $this->assertEquals(3, $stats['active_services']);
    }

    /**
     * Property 6: Data Isolation - Statistics should only include chef's own data
     * For any chef, statistics should not include data from other chefs.
     * 
     * @test
     */
    public function statistics_only_include_own_chef_data(): void
    {
        // Arrange: Create another chef with bookings
        $otherChefUser = User::factory()->create(['user_type' => 'chef']);
        $otherChef = Chef::factory()->create(['user_id' => $otherChefUser->id]);
        
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create service and bookings for this chef
        $myService = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $myService->id,
            'customer_id' => $customer->id,
            'is_active' => true,
            'booking_status' => 'completed',
            'total_amount' => 100.00,
        ]);
        
        // Create service and bookings for other chef
        $otherService = ChefService::factory()->create(['chef_id' => $otherChef->id]);
        for ($i = 0; $i < 5; $i++) {
            Booking::factory()->create([
                'chef_id' => $otherChef->id,
                'chef_service_id' => $otherService->id,
                'customer_id' => $customer->id,
                'is_active' => true,
                'booking_status' => 'completed',
                'total_amount' => 200.00,
            ]);
        }

        // Act: Get statistics for this chef
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: Should only include this chef's data
        $this->assertEquals(1, $stats['total_bookings']);
        $this->assertEquals(100.00, $stats['total_earnings']);
        $this->assertEquals(1, $stats['total_services']);
    }

    /**
     * Property 6: Empty State - Statistics should handle zero data gracefully
     * For any chef with no data, statistics should return zeros without errors.
     * 
     * @test
     */
    public function statistics_handle_empty_data_gracefully(): void
    {
        // Act: Get statistics for chef with no data
        $stats = $this->dashboardService->getStatistics($this->chef->id);

        // Assert: All values should be zero
        $this->assertEquals(0, $stats['total_bookings']);
        $this->assertEquals(0, $stats['monthly_bookings']);
        $this->assertEquals(0, $stats['pending_bookings']);
        $this->assertEquals(0, $stats['confirmed_bookings']);
        $this->assertEquals(0, $stats['completed_bookings']);
        $this->assertEquals(0.0, $stats['total_earnings']);
        $this->assertEquals(0.0, $stats['monthly_earnings']);
        $this->assertEquals(0.0, $stats['average_rating']);
        $this->assertEquals(0, $stats['total_reviews']);
        $this->assertEquals(0, $stats['total_services']);
        $this->assertEquals(0, $stats['active_services']);
    }

    /**
     * Property 6: Wallet Balance Accuracy
     * For any chef, wallet_balance should accurately reflect the wallet balance.
     * 
     * @test
     */
    public function wallet_balance_accurately_reflects_database(): void
    {
        // Arrange: Create wallet with known balance
        ChefWallet::create([
            'chef_id' => $this->chef->id,
            'balance' => 1500.75,
            'is_active' => true,
        ]);

        // Act: Get full dashboard
        $dashboard = $this->dashboardService->getFullDashboard($this->chef->id);

        // Assert: Wallet balance should be accurate
        $this->assertEquals(1500.75, $dashboard->wallet_balance);
    }
}
