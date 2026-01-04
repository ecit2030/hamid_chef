<?php

namespace Tests\Feature;

use App\Models\Chef;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Chef Protected Routes Tests
 * 
 * Feature: chef-panel-authentication
 * Property 3: Protected Routes Redirect
 * Validates: Requirements 1.5, 3.2
 */
class ChefProtectedRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Protected routes that require authentication
     */
    protected array $protectedRoutes = [
        ['GET', '/chef/dashboard'],
        ['GET', '/chef/services'],
        ['GET', '/chef/services/create'],
        ['GET', '/chef/bookings'],
        ['GET', '/chef/profile'],
        ['GET', '/chef/vacations'],
        ['GET', '/chef/working-hours'],
        ['GET', '/chef/wallet'],
    ];

    /**
     * Property 3: Protected Routes Redirect
     * For any unauthenticated request to /chef/* routes (except login/password reset),
     * the system should redirect to /chef/login.
     * 
     * @test
     * @dataProvider protectedRoutesProvider
     */
    public function unauthenticated_users_are_redirected_to_chef_login(string $method, string $route): void
    {
        // Act: Access protected route without authentication
        $response = $this->call($method, $route);

        // Assert: Should redirect to chef login
        $response->assertRedirect(route('chef.login'));
    }

    /**
     * Data provider for protected routes
     */
    public static function protectedRoutesProvider(): array
    {
        return [
            'dashboard' => ['GET', '/chef/dashboard'],
            'services index' => ['GET', '/chef/services'],
            'services create' => ['GET', '/chef/services/create'],
            'bookings index' => ['GET', '/chef/bookings'],
            'profile edit' => ['GET', '/chef/profile'],
            'vacations index' => ['GET', '/chef/vacations'],
            'working hours' => ['GET', '/chef/working-hours'],
            'wallet' => ['GET', '/chef/wallet'],
        ];
    }

    /**
     * @test
     */
    public function authenticated_chef_can_access_dashboard(): void
    {
        // Arrange: Create and authenticate a chef
        $user = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Access dashboard as authenticated chef
        $response = $this->actingAs($user, 'chef')->get('/chef/dashboard');

        // Assert: Should be successful
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function customer_cannot_access_chef_routes(): void
    {
        // Arrange: Create a customer user
        $user = User::factory()->create([
            'user_type' => 'customer',
            'is_active' => true,
        ]);

        // Act: Try to access chef dashboard as customer
        $response = $this->actingAs($user, 'chef')->get('/chef/dashboard');

        // Assert: Should be redirected to login with error
        $response->assertRedirect(route('chef.login'));
    }

    /**
     * @test
     */
    public function chef_with_inactive_profile_cannot_access_routes(): void
    {
        // Arrange: Create a chef with inactive profile
        $user = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => false,
        ]);

        // Act: Try to access dashboard
        $response = $this->actingAs($user, 'chef')->get('/chef/dashboard');

        // Assert: Should be redirected to login
        $response->assertRedirect(route('chef.login'));
    }

    /**
     * @test
     */
    public function inactive_user_cannot_access_chef_routes(): void
    {
        // Arrange: Create an inactive chef user
        $user = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => false,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Try to access dashboard
        $response = $this->actingAs($user, 'chef')->get('/chef/dashboard');

        // Assert: Should be redirected to login
        $response->assertRedirect(route('chef.login'));
    }

    /**
     * @test
     */
    public function guest_routes_are_accessible_without_authentication(): void
    {
        // Act & Assert: Login page should be accessible
        $this->get('/chef/login')->assertStatus(200);
        
        // Forgot password page should be accessible
        $this->get('/chef/forgot-password')->assertStatus(200);
    }

    /**
     * @test
     */
    public function authenticated_chef_is_redirected_from_guest_routes(): void
    {
        // Arrange: Create and authenticate a chef
        $user = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Try to access login page while authenticated
        $response = $this->actingAs($user, 'chef')->get('/chef/login');

        // Assert: Should be redirected to dashboard
        $response->assertRedirect(route('chef.dashboard'));
    }
}
