<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Chef;
use App\Models\ChefService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Chef Data Isolation Tests
 * 
 * Feature: chef-panel-authentication
 * Property 4: Data Isolation - Services
 * Property 5: Data Isolation - Bookings
 * Property 10: Booking Filter Accuracy
 * Validates: Requirements 6.1, 6.5, 7.1, 7.2
 * 
 * For any authenticated chef, the services/bookings list should only contain 
 * items where chef_id matches the authenticated chef's ID.
 */
class ChefDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected User $chefUser;
    protected Chef $chef;
    protected User $otherChefUser;
    protected Chef $otherChef;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create first chef
        $this->chefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);
        
        $this->chef = Chef::factory()->create([
            'user_id' => $this->chefUser->id,
            'is_active' => true,
        ]);
        
        // Create second chef
        $this->otherChefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);
        
        $this->otherChef = Chef::factory()->create([
            'user_id' => $this->otherChefUser->id,
            'is_active' => true,
        ]);
    }

    // ==========================================
    // Property 4: Data Isolation - Services
    // ==========================================

    /**
     * Property 4: Data Isolation - Services
     * For any authenticated chef, the services list should only contain services 
     * where chef_id matches the authenticated chef's ID.
     * 
     * @test
     */
    public function chef_can_only_see_own_services_in_list(): void
    {
        // Arrange: Create services for both chefs
        $myServices = ChefService::factory()->count(3)->create([
            'chef_id' => $this->chef->id,
        ]);
        
        $otherServices = ChefService::factory()->count(5)->create([
            'chef_id' => $this->otherChef->id,
        ]);

        // Act: Access services list as authenticated chef
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.services.index'));

        // Assert: Should only see own services
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chef/ChefService/Index')
            ->has('services.data', 3)
        );
    }

    /**
     * Property 4: Chef cannot view another chef's service details
     * 
     * @test
     */
    public function chef_cannot_view_other_chef_service(): void
    {
        // Arrange: Create service for other chef
        $otherService = ChefService::factory()->create([
            'chef_id' => $this->otherChef->id,
        ]);

        // Act: Try to view other chef's service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.services.show', $otherService));

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Property 4: Chef cannot edit another chef's service
     * 
     * @test
     */
    public function chef_cannot_edit_other_chef_service(): void
    {
        // Arrange: Create service for other chef
        $otherService = ChefService::factory()->create([
            'chef_id' => $this->otherChef->id,
        ]);

        // Act: Try to access edit page for other chef's service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.services.edit', $otherService));

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Property 4: Chef cannot update another chef's service
     * 
     * @test
     */
    public function chef_cannot_update_other_chef_service(): void
    {
        // Arrange: Create service for other chef
        $otherService = ChefService::factory()->create([
            'chef_id' => $this->otherChef->id,
        ]);

        // Act: Try to update other chef's service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.services.update', $otherService), [
                'name' => 'Hacked Service Name',
                'description' => 'Hacked description',
                'service_type' => 'hourly',
                'hourly_rate' => 100,
                'min_hours' => 2,
            ]);

        // Assert: Should be forbidden
        $response->assertStatus(403);
        
        // Verify service was not modified
        $this->assertDatabaseMissing('chef_services', [
            'id' => $otherService->id,
            'name' => 'Hacked Service Name',
        ]);
    }

    /**
     * Property 4: Chef cannot delete another chef's service
     * 
     * @test
     */
    public function chef_cannot_delete_other_chef_service(): void
    {
        // Arrange: Create service for other chef
        $otherService = ChefService::factory()->create([
            'chef_id' => $this->otherChef->id,
        ]);

        // Act: Try to delete other chef's service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->delete(route('chef.services.destroy', $otherService));

        // Assert: Should be forbidden
        $response->assertStatus(403);
        
        // Verify service still exists
        $this->assertDatabaseHas('chef_services', [
            'id' => $otherService->id,
        ]);
    }

    /**
     * Property 4: Chef can view own service
     * 
     * @test
     */
    public function chef_can_view_own_service(): void
    {
        // Arrange: Create service for this chef
        $myService = ChefService::factory()->create([
            'chef_id' => $this->chef->id,
        ]);

        // Act: View own service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.services.show', $myService));

        // Assert: Should be successful
        $response->assertStatus(200);
    }

    /**
     * Property 4: Chef can edit own service
     * 
     * @test
     */
    public function chef_can_edit_own_service(): void
    {
        // Arrange: Create service for this chef
        $myService = ChefService::factory()->create([
            'chef_id' => $this->chef->id,
        ]);

        // Act: Access edit page for own service
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.services.edit', $myService));

        // Assert: Should be successful
        $response->assertStatus(200);
    }

    /**
     * Property 4: Service creation forces chef_id to authenticated chef
     * 
     * @test
     */
    public function service_creation_forces_authenticated_chef_id(): void
    {
        // Act: Create service (even if trying to set different chef_id)
        $response = $this->actingAs($this->chefUser, 'chef')
            ->post(route('chef.services.store'), [
                'chef_id' => $this->otherChef->id, // Trying to create for other chef
                'name' => 'My New Service',
                'description' => 'Service description',
                'service_type' => 'hourly',
                'hourly_rate' => 100,
                'min_hours' => 2,
            ]);

        // Assert: Service should be created for authenticated chef, not the one in request
        $this->assertDatabaseHas('chef_services', [
            'name' => 'My New Service',
            'chef_id' => $this->chef->id, // Should be authenticated chef's ID
        ]);
        
        $this->assertDatabaseMissing('chef_services', [
            'name' => 'My New Service',
            'chef_id' => $this->otherChef->id,
        ]);
    }

    // ==========================================
    // Property 5: Data Isolation - Bookings
    // ==========================================

    /**
     * Property 5: Data Isolation - Bookings
     * For any authenticated chef, the bookings list should only contain bookings 
     * where chef_id matches the authenticated chef's ID.
     * 
     * @test
     */
    public function chef_can_only_see_own_bookings_in_list(): void
    {
        // Arrange: Create services and bookings for both chefs
        $myService = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $otherService = ChefService::factory()->create(['chef_id' => $this->otherChef->id]);
        
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create bookings for this chef
        for ($i = 0; $i < 3; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $myService->id,
                'customer_id' => $customer->id,
                'is_active' => true,
            ]);
        }
        
        // Create bookings for other chef
        for ($i = 0; $i < 5; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->otherChef->id,
                'chef_service_id' => $otherService->id,
                'customer_id' => $customer->id,
                'is_active' => true,
            ]);
        }

        // Act: Access bookings list as authenticated chef
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.bookings.index'));

        // Assert: Should only see own bookings
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chef/Bookings/Index')
            ->has('bookings.data', 3)
        );
    }

    /**
     * Property 5: Chef cannot view another chef's booking details
     * 
     * @test
     */
    public function chef_cannot_view_other_chef_booking(): void
    {
        // Arrange: Create booking for other chef
        $otherService = ChefService::factory()->create(['chef_id' => $this->otherChef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $otherBooking = Booking::factory()->create([
            'chef_id' => $this->otherChef->id,
            'chef_service_id' => $otherService->id,
            'customer_id' => $customer->id,
        ]);

        // Act: Try to view other chef's booking
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.bookings.show', $otherBooking));

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Property 5: Chef cannot accept another chef's booking
     * 
     * @test
     */
    public function chef_cannot_accept_other_chef_booking(): void
    {
        // Arrange: Create pending booking for other chef
        $otherService = ChefService::factory()->create(['chef_id' => $this->otherChef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $otherBooking = Booking::factory()->create([
            'chef_id' => $this->otherChef->id,
            'chef_service_id' => $otherService->id,
            'customer_id' => $customer->id,
            'booking_status' => 'pending',
        ]);

        // Act: Try to accept other chef's booking
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.bookings.accept', $otherBooking));

        // Assert: Should be forbidden
        $response->assertStatus(403);
        
        // Verify booking status was not changed
        $this->assertDatabaseHas('bookings', [
            'id' => $otherBooking->id,
            'booking_status' => 'pending',
        ]);
    }

    /**
     * Property 5: Chef can view own booking
     * 
     * @test
     */
    public function chef_can_view_own_booking(): void
    {
        // Arrange: Create booking for this chef
        $myService = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        $myBooking = Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $myService->id,
            'customer_id' => $customer->id,
        ]);

        // Act: View own booking
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.bookings.show', $myBooking));

        // Assert: Should be successful
        $response->assertStatus(200);
    }

    // ==========================================
    // Property 10: Booking Filter Accuracy
    // ==========================================

    /**
     * Property 10: Booking Filter Accuracy
     * For any booking filter by status, the returned bookings should all have 
     * the specified status and belong to the authenticated chef.
     * 
     * @test
     */
    public function booking_filter_by_status_returns_correct_results(): void
    {
        // Arrange: Create bookings with different statuses
        $myService = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create pending bookings
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $myService->id,
                'customer_id' => $customer->id,
                'booking_status' => 'pending',
                'is_active' => true,
            ]);
        }
        
        // Create accepted bookings
        for ($i = 0; $i < 3; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->chef->id,
                'chef_service_id' => $myService->id,
                'customer_id' => $customer->id,
                'booking_status' => 'accepted',
                'is_active' => true,
            ]);
        }
        
        // Create completed bookings
        Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $myService->id,
            'customer_id' => $customer->id,
            'booking_status' => 'completed',
            'is_active' => true,
        ]);

        // Act: Filter by pending status
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.bookings.index', ['status' => 'pending']));

        // Assert: Should only see pending bookings
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chef/Bookings/Index')
            ->has('bookings.data', 2)
        );
    }

    /**
     * Property 10: Filtered bookings only include chef's own data
     * 
     * @test
     */
    public function filtered_bookings_exclude_other_chef_data(): void
    {
        // Arrange: Create pending bookings for both chefs
        $myService = ChefService::factory()->create(['chef_id' => $this->chef->id]);
        $otherService = ChefService::factory()->create(['chef_id' => $this->otherChef->id]);
        $customer = User::factory()->create(['user_type' => 'customer']);
        
        // Create pending booking for this chef
        Booking::factory()->create([
            'chef_id' => $this->chef->id,
            'chef_service_id' => $myService->id,
            'customer_id' => $customer->id,
            'booking_status' => 'pending',
            'is_active' => true,
        ]);
        
        // Create pending bookings for other chef
        for ($i = 0; $i < 5; $i++) {
            Booking::factory()->create([
                'chef_id' => $this->otherChef->id,
                'chef_service_id' => $otherService->id,
                'customer_id' => $customer->id,
                'booking_status' => 'pending',
                'is_active' => true,
            ]);
        }

        // Act: Filter by pending status
        $response = $this->actingAs($this->chefUser, 'chef')
            ->get(route('chef.bookings.index', ['status' => 'pending']));

        // Assert: Should only see own pending booking (1), not other chef's (5)
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Chef/Bookings/Index')
            ->has('bookings.data', 1)
        );
    }
}
