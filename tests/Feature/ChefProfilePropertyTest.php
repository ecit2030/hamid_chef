<?php

namespace Tests\Feature;

use App\Models\Chef;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Chef Profile Property Tests
 * 
 * Feature: chef-panel-authentication
 * Property 8: Profile Update Persistence
 * Property 9: Password Change Validity
 * Validates: Requirements 8.2, 8.3
 * 
 * For any valid profile update request, the changes should be persisted to the database 
 * and reflected in subsequent reads.
 * For any successful password change, the new password should work for subsequent login 
 * attempts and the old password should be rejected.
 */
class ChefProfilePropertyTest extends TestCase
{
    use RefreshDatabase;

    protected User $chefUser;
    protected Chef $chef;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a chef user with profile
        $this->chefUser = User::factory()->create([
            'user_type' => 'chef',
            'is_active' => true,
            'password' => Hash::make('original-password'),
        ]);
        
        $this->chef = Chef::factory()->create([
            'user_id' => $this->chefUser->id,
            'is_active' => true,
        ]);
    }

    // ==========================================
    // Property 8: Profile Update Persistence
    // ==========================================

    /**
     * Property 8: Profile Update Persistence - Basic Fields
     * For any valid profile update, changes should be persisted to the database.
     * 
     * @test
     */
    public function profile_update_persists_basic_fields(): void
    {
        // Arrange: Prepare update data
        $updateData = [
            'name' => 'Updated Chef Name',
            'short_description' => 'Updated short description',
            'email' => 'updated@example.com',
            'phone' => '0501234567',
        ];

        // Act: Update profile
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Assert: Changes should be persisted
        $response->assertRedirect();
        
        $this->chef->refresh();
        $this->assertEquals('Updated Chef Name', $this->chef->name);
        $this->assertEquals('Updated short description', $this->chef->short_description);
        $this->assertEquals('updated@example.com', $this->chef->email);
        $this->assertEquals('0501234567', $this->chef->phone);
    }

    /**
     * Property 8: Profile Update Persistence - Long Description
     * For any valid profile update, long_description should be persisted.
     * 
     * @test
     */
    public function profile_update_persists_long_description(): void
    {
        // Arrange: Prepare update data with long description (no trailing space)
        $longDescription = 'This is a long description that spans multiple sentences. It contains detailed information about the chef and their services. The chef has many years of experience.';
        $updateData = [
            'name' => $this->chef->name,
            'email' => $this->chef->email,
            'phone' => $this->chef->phone,
            'long_description' => $longDescription,
        ];

        // Act: Update profile
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Assert: Long description should be persisted
        $response->assertRedirect();
        
        $this->chef->refresh();
        $this->assertEquals($longDescription, $this->chef->long_description);
    }

    /**
     * Property 8: Profile Update Persistence - Short Description
     * For any valid profile update, short_description should be persisted.
     * 
     * @test
     */
    public function profile_update_persists_short_description(): void
    {
        // Arrange: Prepare update data with short description
        $updateData = [
            'name' => $this->chef->name,
            'email' => $this->chef->email,
            'phone' => $this->chef->phone,
            'short_description' => 'Professional chef specializing in Italian cuisine.',
        ];

        // Act: Update profile
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Assert: Short description should be persisted
        $response->assertRedirect();
        
        $this->chef->refresh();
        $this->assertEquals('Professional chef specializing in Italian cuisine.', $this->chef->short_description);
    }

    /**
     * Property 8: Profile Update Persistence - Address Fields
     * For any valid profile update, address should be persisted.
     * 
     * @test
     */
    public function profile_update_persists_address(): void
    {
        // Arrange: Prepare update data with address
        $updateData = [
            'name' => $this->chef->name,
            'email' => $this->chef->email,
            'phone' => $this->chef->phone,
            'address' => '123 Main Street, Building 5, Floor 2',
        ];

        // Act: Update profile
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Assert: Address should be persisted
        $response->assertRedirect();
        
        $this->chef->refresh();
        $this->assertEquals('123 Main Street, Building 5, Floor 2', $this->chef->address);
    }

    /**
     * Property 8: Profile Update Persistence - Hourly Rate
     * For any valid profile update, base_hourly_rate should be persisted.
     * 
     * @test
     */
    public function profile_update_persists_hourly_rate(): void
    {
        // Arrange: Prepare update data with hourly rate
        $updateData = [
            'name' => $this->chef->name,
            'email' => $this->chef->email,
            'phone' => $this->chef->phone,
            'base_hourly_rate' => 75.50,
        ];

        // Act: Update profile
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Assert: Hourly rate should be persisted
        $response->assertRedirect();
        
        $this->chef->refresh();
        $this->assertEquals(75.50, $this->chef->base_hourly_rate);
    }

    /**
     * Property 8: Profile Update Persistence - Reflected in Subsequent Reads
     * For any valid profile update, changes should be reflected in subsequent database reads.
     * 
     * @test
     */
    public function profile_update_reflected_in_subsequent_reads(): void
    {
        // Arrange: Update profile
        $updateData = [
            'name' => 'New Chef Name',
            'short_description' => 'New description',
            'email' => 'new@example.com',
            'phone' => '0509876543',
        ];

        $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), $updateData);

        // Act: Refresh the model from database
        $this->chef->refresh();

        // Assert: Updated values should be in database
        $this->assertEquals('New Chef Name', $this->chef->name);
        $this->assertEquals('New description', $this->chef->short_description);
        $this->assertEquals('new@example.com', $this->chef->email);
        $this->assertEquals('0509876543', $this->chef->phone);
        
        // Also verify via fresh query
        $freshChef = \App\Models\Chef::find($this->chef->id);
        $this->assertEquals('New Chef Name', $freshChef->name);
        $this->assertEquals('New description', $freshChef->short_description);
    }

    /**
     * Property 8: Profile Update Validation - Required Fields
     * For any profile update missing required fields, validation should fail.
     * 
     * @test
     */
    public function profile_update_validates_required_fields(): void
    {
        // Act: Try to update with missing required fields
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), [
                'name' => '', // Required but empty
                'email' => '', // Required but empty
                'phone' => '', // Required but empty
            ]);

        // Assert: Validation should fail
        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }

    /**
     * Property 8: Profile Update Validation - Email Format
     * For any profile update with invalid email, validation should fail.
     * 
     * @test
     */
    public function profile_update_validates_email_format(): void
    {
        // Act: Try to update with invalid email
        $response = $this->actingAs($this->chefUser, 'chef')
            ->patch(route('chef.profile.update'), [
                'name' => $this->chef->name,
                'email' => 'invalid-email',
                'phone' => $this->chef->phone,
            ]);

        // Assert: Validation should fail
        $response->assertSessionHasErrors(['email']);
    }

    // ==========================================
    // Property 9: Password Change Validity
    // ==========================================

    /**
     * Property 9: Password Change Validity - New Password Works
     * For any successful password change, the new password should work for login.
     * 
     * @test
     */
    public function new_password_works_after_change(): void
    {
        // Arrange: Change password
        $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'original-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

        // Act: Logout and try to login with new password
        $this->post(route('chef.logout'));
        
        $response = $this->post(route('chef.login'), [
            'email' => $this->chefUser->email,
            'password' => 'new-secure-password',
        ]);

        // Assert: Login should succeed
        $response->assertRedirect(route('chef.dashboard'));
        $this->assertAuthenticatedAs($this->chefUser, 'chef');
    }

    /**
     * Property 9: Password Change Validity - Old Password Rejected
     * For any successful password change, the old password should be rejected.
     * 
     * @test
     */
    public function old_password_rejected_after_change(): void
    {
        // Arrange: Change password
        $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'original-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

        // Act: Logout and try to login with old password
        $this->post(route('chef.logout'));
        
        $response = $this->post(route('chef.login'), [
            'email' => $this->chefUser->email,
            'password' => 'original-password',
        ]);

        // Assert: Login should fail
        $response->assertSessionHasErrors();
        $this->assertGuest('chef');
    }

    /**
     * Property 9: Password Change Requires Current Password
     * For any password change attempt, current password must be correct.
     * 
     * @test
     */
    public function password_change_requires_correct_current_password(): void
    {
        // Act: Try to change password with wrong current password
        $response = $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

        // Assert: Should fail validation
        $response->assertSessionHasErrors(['current_password']);
        
        // Verify password was not changed
        $this->chefUser->refresh();
        $this->assertTrue(Hash::check('original-password', $this->chefUser->password));
    }

    /**
     * Property 9: Password Change Requires Confirmation
     * For any password change, password confirmation must match.
     * 
     * @test
     */
    public function password_change_requires_matching_confirmation(): void
    {
        // Act: Try to change password with mismatched confirmation
        $response = $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'original-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'different-password',
            ]);

        // Assert: Should fail validation
        $response->assertSessionHasErrors(['password']);
        
        // Verify password was not changed
        $this->chefUser->refresh();
        $this->assertTrue(Hash::check('original-password', $this->chefUser->password));
    }

    /**
     * Property 9: Password Change Persists to Database
     * For any successful password change, the hash should be updated in database.
     * 
     * @test
     */
    public function password_change_persists_to_database(): void
    {
        // Arrange: Get original password hash
        $originalHash = $this->chefUser->password;

        // Act: Change password
        $response = $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'original-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

        // Assert: Password hash should be different
        $response->assertRedirect();
        
        $this->chefUser->refresh();
        $this->assertNotEquals($originalHash, $this->chefUser->password);
        $this->assertTrue(Hash::check('new-secure-password', $this->chefUser->password));
    }

    /**
     * Property 9: Password Change Returns Success Status
     * For any successful password change, a success status should be returned.
     * 
     * @test
     */
    public function password_change_returns_success_status(): void
    {
        // Act: Change password
        $response = $this->actingAs($this->chefUser, 'chef')
            ->put(route('chef.password.update'), [
                'current_password' => 'original-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

        // Assert: Should have success status in session
        $response->assertSessionHas('status');
    }
}
