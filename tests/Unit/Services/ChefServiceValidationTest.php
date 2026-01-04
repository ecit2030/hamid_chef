<?php

namespace Tests\Unit\Services;

use App\Models\Chef;
use App\Models\User;
use App\Models\Governorate;
use App\Models\District;
use App\Models\Area;
use App\Services\ChefService;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ChefServiceValidationTest extends TestCase
{
    use RefreshDatabase;

    protected ChefService $chefService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chefService = app(ChefService::class);
        
        // Create required location data for tests
        $governorate = Governorate::factory()->create();
        $district = District::factory()->create(['governorate_id' => $governorate->id]);
        Area::factory()->create(['district_id' => $district->id]);
    }

    /**
     * **Feature: chef-duplicate-validation, Property 4: Proactive validation prevents database violations**
     * 
     * Test that ChefService proactively validates user uniqueness before database operations.
     */
    public function test_service_prevents_duplicate_chef_creation()
    {
        // Property: For any chef creation attempt, the system should verify user_id uniqueness
        // Create a random user with existing chef
        $user = User::factory()->create();
        Chef::factory()->create(['user_id' => $user->id]);
        
        // Authenticate the user
        Auth::login($user);
        
        // Prepare chef creation data
        $chefData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'governorate_id' => Governorate::first()->id,
            'district_id' => District::first()->id,
            'area_id' => Area::first()->id,
        ];
        
        // Should throw ValidationException
        $this->expectException(ValidationException::class);
        $this->chefService->create($chefData);
    }

    public function test_service_allows_chef_creation_for_new_users()
    {
        // Property: For any user without a chef profile, service should allow creation
        for ($i = 0; $i < 3; $i++) {
            // Create a random user without chef
            $user = User::factory()->create();
            
            // Authenticate the user
            Auth::login($user);
            
            // Prepare chef creation data
            $chefData = [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'governorate_id' => Governorate::first()->id,
                'district_id' => District::first()->id,
                'area_id' => Area::first()->id,
            ];
            
            // Should successfully create chef
            $chef = $this->chefService->create($chefData);
            
            $this->assertInstanceOf(Chef::class, $chef);
            $this->assertEquals($user->id, $chef->user_id);
            $this->assertEquals($chefData['name'], $chef->name);
        }
    }

    public function test_check_user_has_chef_method()
    {
        // Test the checkUserHasChef method directly
        $userWithChef = User::factory()->create();
        $userWithoutChef = User::factory()->create();
        
        Chef::factory()->create(['user_id' => $userWithChef->id]);
        
        // Should throw exception for user with chef
        $this->expectException(ValidationException::class);
        $this->chefService->checkUserHasChef($userWithChef->id);
        
        // Should not throw exception for user without chef
        $this->chefService->checkUserHasChef($userWithoutChef->id);
        $this->assertTrue(true); // If we reach here, no exception was thrown
    }

    public function test_service_respects_soft_deleted_chefs()
    {
        // The database schema uses a composite unique index on (user_id, deleted_at)
        // This allows creating a new chef for the same user after soft-delete
        // because (user_id, NULL) is different from (user_id, timestamp)
        
        // Create user with soft-deleted chef
        $user = User::factory()->create();
        $chef = Chef::factory()->create(['user_id' => $user->id]);
        $chef->delete(); // Soft delete
        
        // Verify the chef is soft-deleted
        $this->assertSoftDeleted('chefs', ['id' => $chef->id]);
        
        Auth::login($user);
        
        // Prepare chef creation data
        $chefData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'governorate_id' => Governorate::first()->id,
            'district_id' => District::first()->id,
            'area_id' => Area::first()->id,
        ];
        
        // Should successfully create a new chef because the composite unique constraint
        // (user_id, deleted_at) allows this - the soft-deleted chef has a timestamp
        // while the new chef will have NULL for deleted_at
        $newChef = $this->chefService->create($chefData);
        
        $this->assertInstanceOf(Chef::class, $newChef);
        $this->assertEquals($user->id, $newChef->user_id);
        $this->assertNull($newChef->deleted_at);
        
        // Verify both chefs exist in the database
        $this->assertDatabaseCount('chefs', 2);
    }

    public function test_service_handles_explicit_user_id()
    {
        // Test with explicit user_id in attributes
        $user = User::factory()->create();
        Chef::factory()->create(['user_id' => $user->id]);
        
        // Don't authenticate any user
        Auth::logout();
        
        // Prepare chef creation data with explicit user_id
        $chefData = [
            'user_id' => $user->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'governorate_id' => Governorate::first()->id,
            'district_id' => District::first()->id,
            'area_id' => Area::first()->id,
        ];
        
        // Should throw ValidationException
        $this->expectException(ValidationException::class);
        $this->chefService->create($chefData);
    }

    public function test_validation_exception_has_correct_structure()
    {
        // Test that ValidationException has the expected structure
        $user = User::factory()->create();
        Chef::factory()->create(['user_id' => $user->id]);
        
        try {
            $this->chefService->checkUserHasChef($user->id);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            
            $this->assertArrayHasKey('user_id', $errors);
            $this->assertContains('المستخدم لديه بالفعل ملف طاهي', $errors['user_id']);
        }
    }
}