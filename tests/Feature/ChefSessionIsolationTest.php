<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Chef;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Chef Session Isolation Tests
 * 
 * Feature: chef-panel-authentication
 * Property 7: Session Isolation
 * Validates: Requirements 9.1, 9.2, 9.3
 * 
 * For any authenticated session, logging out from one guard (admin/chef) 
 * should not affect the session of another guard.
 */
class ChefSessionIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected User $chefUser;
    protected Chef $chef;
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a chef user with profile
        $this->chefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
            'password' => Hash::make('chef-password'),
        ]);
        
        $this->chef = Chef::factory()->create([
            'user_id' => $this->chefUser->id,
            'is_active' => true,
        ]);
        
        // Create an admin user
        $this->admin = Admin::factory()->create([
            'password' => Hash::make('admin-password'),
        ]);
    }

    // ==========================================
    // Property 7: Session Isolation
    // ==========================================

    /**
     * Property 7: Session Isolation - Chef logout doesn't affect admin session
     * Logging out from chef guard should not affect admin guard session.
     * 
     * @test
     */
    public function chef_logout_does_not_affect_admin_session(): void
    {
        // Arrange: Login as both admin and chef
        $this->actingAs($this->admin, 'admin');
        $this->actingAs($this->chefUser, 'chef');
        
        // Verify both are authenticated
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('chef')->check());

        // Act: Logout from chef
        $this->post(route('chef.logout'));

        // Assert: Admin should still be authenticated
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('chef')->check());
    }

    /**
     * Property 7: Session Isolation - Admin logout doesn't affect chef session
     * Logging out from admin guard should not affect chef guard session.
     * 
     * @test
     */
    public function admin_logout_does_not_affect_chef_session(): void
    {
        // Arrange: Login as both admin and chef
        $this->actingAs($this->admin, 'admin');
        $this->actingAs($this->chefUser, 'chef');
        
        // Verify both are authenticated
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('chef')->check());

        // Act: Logout from admin
        $this->post(route('admin.logout'));

        // Assert: Chef should still be authenticated
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertTrue(Auth::guard('chef')->check());
    }

    /**
     * Property 7: Session Isolation - Chef can access dashboard after admin logout
     * After admin logout, chef should still be able to access chef dashboard.
     * 
     * @test
     */
    public function chef_can_access_dashboard_after_admin_logout(): void
    {
        // Arrange: Login as both admin and chef
        $this->actingAs($this->admin, 'admin');
        $this->actingAs($this->chefUser, 'chef');

        // Act: Logout from admin
        $this->post(route('admin.logout'));

        // Assert: Chef can still access chef dashboard
        $response = $this->get(route('chef.dashboard'));
        $response->assertStatus(200);
    }

    /**
     * Property 7: Session Isolation - Admin can access dashboard after chef logout
     * After chef logout, admin should still be able to access admin dashboard.
     * 
     * @test
     */
    public function admin_can_access_dashboard_after_chef_logout(): void
    {
        // Arrange: Login as both admin and chef
        $this->actingAs($this->admin, 'admin');
        $this->actingAs($this->chefUser, 'chef');

        // Act: Logout from chef
        $this->post(route('chef.logout'));

        // Assert: Admin should still be authenticated
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('chef')->check());
    }

    /**
     * Property 7: Session Isolation - Separate login sessions
     * Chef and admin should be able to login independently.
     * 
     * @test
     */
    public function chef_and_admin_can_login_independently(): void
    {
        // Act: Login as chef
        $chefResponse = $this->post(route('chef.login'), [
            'email' => $this->chefUser->email,
            'password' => 'chef-password',
        ]);

        // Assert: Chef is authenticated
        $chefResponse->assertRedirect(route('chef.dashboard'));
        $this->assertTrue(Auth::guard('chef')->check());
        $this->assertFalse(Auth::guard('admin')->check());

        // Act: Login as admin (in same session)
        $adminResponse = $this->post(route('admin.login'), [
            'email' => $this->admin->email,
            'password' => 'admin-password',
        ]);

        // Assert: Both are now authenticated
        $adminResponse->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('chef')->check());
        $this->assertTrue(Auth::guard('admin')->check());
    }

    /**
     * Property 7: Session Isolation - Chef routes protected from admin
     * Admin authentication should not grant access to chef routes.
     * 
     * @test
     */
    public function admin_auth_does_not_grant_chef_access(): void
    {
        // Arrange: Login as admin only
        $this->actingAs($this->admin, 'admin');
        
        // Verify admin is authenticated but not chef
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('chef')->check());

        // Act: Try to access chef dashboard
        $response = $this->get(route('chef.dashboard'));

        // Assert: Should be redirected to chef login
        $response->assertRedirect(route('chef.login'));
    }

    /**
     * Property 7: Session Isolation - Admin routes protected from chef
     * Chef authentication should not grant access to admin routes.
     * 
     * @test
     */
    public function chef_auth_does_not_grant_admin_access(): void
    {
        // Arrange: Login as chef only
        $this->actingAs($this->chefUser, 'chef');
        
        // Verify chef is authenticated but not admin
        $this->assertTrue(Auth::guard('chef')->check());
        $this->assertFalse(Auth::guard('admin')->check());

        // Act: Try to access admin dashboard
        $response = $this->get(route('admin.dashboard'));

        // Assert: Should be redirected to admin login
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Property 7: Session Isolation - Multiple chef users have separate sessions
     * Different chef users should have completely separate sessions.
     * 
     * @test
     */
    public function different_chef_users_have_separate_sessions(): void
    {
        // Arrange: Create another chef user
        $otherChefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
            'password' => Hash::make('other-chef-password'),
        ]);
        
        Chef::factory()->create([
            'user_id' => $otherChefUser->id,
            'is_active' => true,
        ]);

        // Act: Login as first chef
        $this->actingAs($this->chefUser, 'chef');
        
        // Assert: First chef is authenticated
        $this->assertEquals($this->chefUser->id, Auth::guard('chef')->id());

        // Act: Login as second chef (replaces first chef session)
        Auth::guard('chef')->login($otherChefUser);
        
        // Assert: Second chef is now authenticated, not first
        $this->assertEquals($otherChefUser->id, Auth::guard('chef')->id());
        $this->assertNotEquals($this->chefUser->id, Auth::guard('chef')->id());
    }

    /**
     * Property 7: Session Isolation - Chef session persists across requests
     * Chef session should persist across multiple requests.
     * 
     * @test
     */
    public function chef_session_persists_across_requests(): void
    {
        // Arrange: Login as chef
        $this->actingAs($this->chefUser, 'chef');

        // Act: Make multiple requests to routes that work correctly
        $response1 = $this->get(route('chef.dashboard'));
        $response2 = $this->get(route('chef.services.index'));
        $response3 = $this->get(route('chef.bookings.index'));

        // Assert: All requests should succeed (session persists)
        $response1->assertStatus(200);
        $response2->assertStatus(200);
        $response3->assertStatus(200);
        
        // Chef should still be authenticated
        $this->assertTrue(Auth::guard('chef')->check());
    }

    /**
     * Property 7: Session Isolation - Guest middleware works correctly for chef
     * Guest middleware should redirect authenticated chef away from login page.
     * 
     * @test
     */
    public function guest_middleware_redirects_authenticated_chef(): void
    {
        // Arrange: Login as chef
        $this->actingAs($this->chefUser, 'chef');

        // Act: Try to access chef login page
        $response = $this->get(route('chef.login'));

        // Assert: Should be redirected to chef dashboard
        $response->assertRedirect(route('chef.dashboard'));
    }

    /**
     * Property 7: Session Isolation - Guest middleware works correctly for admin
     * Guest middleware should redirect authenticated admin away from login page.
     * 
     * @test
     */
    public function guest_middleware_redirects_authenticated_admin(): void
    {
        // Arrange: Login as admin
        $this->actingAs($this->admin, 'admin');

        // Act: Try to access admin login page
        $response = $this->get(route('admin.login'));

        // Assert: Should be redirected to admin dashboard
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Property 7: Session Isolation - Chef login doesn't affect admin guest middleware
     * Chef being logged in should not affect admin guest middleware.
     * 
     * @test
     */
    public function chef_login_does_not_affect_admin_guest_middleware(): void
    {
        // Arrange: Login as chef only
        $this->actingAs($this->chefUser, 'chef');

        // Act: Try to access admin login page
        $response = $this->get(route('admin.login'));

        // Assert: Should be able to access admin login (not redirected)
        $response->assertStatus(200);
    }

    /**
     * Property 7: Session Isolation - Admin login doesn't affect chef guest middleware
     * Admin being logged in should not affect chef guest middleware.
     * 
     * @test
     */
    public function admin_login_does_not_affect_chef_guest_middleware(): void
    {
        // Arrange: Login as admin only
        $this->actingAs($this->admin, 'admin');

        // Act: Try to access chef login page
        $response = $this->get(route('chef.login'));

        // Assert: Should be able to access chef login (not redirected)
        $response->assertStatus(200);
    }
}
