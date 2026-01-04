<?php

namespace Tests\Feature;

use App\Models\Chef;
use App\Models\User;
use App\Services\ChefAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Chef Authentication Tests
 * 
 * Feature: chef-panel-authentication
 * Property 1: Chef Authentication Validation
 * Property 2: Non-Chef User Rejection
 * Validates: Requirements 1.3, 1.6, 2.4
 */
class ChefAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected ChefAuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(ChefAuthService::class);
    }

    /**
     * Property 1: Chef Authentication Validation
     * For any valid chef credentials (correct email, password, user_type='chef', is_active=true),
     * the system should authenticate successfully.
     * 
     * @test
     */
    public function valid_chef_credentials_authenticate_successfully(): void
    {
        // Arrange: Create a valid chef user with chef profile
        $user = User::factory()->create([
            'email' => 'chef@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Attempt authentication
        $result = $this->authService->authenticate([
            'email' => 'chef@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should succeed
        $this->assertTrue($result['success']);
        $this->assertEquals('تم تسجيل الدخول بنجاح.', $result['message']);
        $this->assertNotNull($result['user']);
        $this->assertEquals($user->id, $result['user']->id);
    }

    /**
     * Property 2: Non-Chef User Rejection
     * For any user with user_type != 'chef', attempting to login should be rejected.
     * 
     * @test
     */
    public function customer_user_cannot_authenticate_as_chef(): void
    {
        // Arrange: Create a customer user
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'customer',
            'is_active' => true,
        ]);

        // Act: Attempt authentication
        $result = $this->authService->authenticate([
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('هذا الحساب غير مصرح له بالدخول كشيف.', $result['message']);
        $this->assertNull($result['user']);
    }

    /**
     * @test
     */
    public function invalid_email_returns_error(): void
    {
        // Act: Attempt authentication with non-existent email
        $result = $this->authService->authenticate([
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('بيانات الدخول غير صحيحة.', $result['message']);
    }

    /**
     * @test
     */
    public function invalid_password_returns_error(): void
    {
        // Arrange: Create a valid chef user
        $user = User::factory()->create([
            'email' => 'chef@example.com',
            'password' => Hash::make('correctpassword'),
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Attempt authentication with wrong password
        $result = $this->authService->authenticate([
            'email' => 'chef@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('بيانات الدخول غير صحيحة.', $result['message']);
    }

    /**
     * @test
     */
    public function inactive_user_cannot_authenticate(): void
    {
        // Arrange: Create an inactive chef user
        $user = User::factory()->create([
            'email' => 'chef@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'chef',
            'is_active' => false,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        // Act: Attempt authentication
        $result = $this->authService->authenticate([
            'email' => 'chef@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('هذا الحساب معطل. يرجى التواصل مع الدعم.', $result['message']);
    }

    /**
     * @test
     */
    public function chef_without_profile_cannot_authenticate(): void
    {
        // Arrange: Create a chef user without chef profile
        $user = User::factory()->create([
            'email' => 'chef@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        // Act: Attempt authentication (no Chef profile created)
        $result = $this->authService->authenticate([
            'email' => 'chef@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('لم يتم العثور على ملف الشيف. يرجى التواصل مع الدعم.', $result['message']);
    }

    /**
     * @test
     */
    public function chef_with_inactive_profile_cannot_authenticate(): void
    {
        // Arrange: Create a chef user with inactive chef profile
        $user = User::factory()->create([
            'email' => 'chef@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'chef',
            'is_active' => true,
        ]);

        Chef::factory()->create([
            'user_id' => $user->id,
            'is_active' => false,
        ]);

        // Act: Attempt authentication
        $result = $this->authService->authenticate([
            'email' => 'chef@example.com',
            'password' => 'password123',
        ]);

        // Assert: Authentication should fail
        $this->assertFalse($result['success']);
        $this->assertEquals('ملف الشيف الخاص بك معطل. يرجى التواصل مع الدعم.', $result['message']);
    }

    /**
     * @test
     */
    public function chef_login_page_is_accessible(): void
    {
        // Act: Visit chef login page
        $response = $this->get(route('chef.login'));

        // Assert: Page should be accessible
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function authenticated_chef_is_redirected_from_login_page(): void
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

        // Act: Visit login page while authenticated
        $response = $this->actingAs($user, 'chef')->get(route('chef.login'));

        // Assert: Should be redirected to dashboard
        $response->assertRedirect(route('chef.dashboard'));
    }
}
