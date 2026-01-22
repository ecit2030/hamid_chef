<?php

namespace Tests\Feature\Api;

use App\Models\Chef;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChefServiceCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chef_can_create_service()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي المنزلي',
                'description' => 'خدمة طهي احترافية في منزلك',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
                'min_hours' => 3,
                'rest_hours_required' => 2,
                'is_active' => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'service_type',
                    'hourly_rate',
                    'min_hours',
                ],
            ]);

        $this->assertDatabaseHas('chef_services', [
            'chef_id' => $chef->id,
            'name' => 'خدمة الطهي المنزلي',
            'service_type' => 'hourly',
            'hourly_rate' => 150.00,
        ]);
    }

    /** @test */
    public function chef_can_create_service_with_tags()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $tag1 = Tag::factory()->create(['name' => 'مأكولات عربية']);
        $tag2 = Tag::factory()->create(['name' => 'حلويات']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي المنزلي',
                'description' => 'خدمة طهي احترافية',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
                'min_hours' => 3,
                'tags' => [$tag1->id, $tag2->id],
            ]);

        $response->assertStatus(201);

        $service = $chef->services()->first();

        $this->assertCount(2, $service->tags);
        $this->assertTrue($service->tags->contains($tag1));
        $this->assertTrue($service->tags->contains($tag2));
    }

    /** @test */
    public function chef_can_create_service_with_equipment()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي المنزلي',
                'description' => 'خدمة طهي احترافية',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
                'min_hours' => 3,
                'equipment' => [
                    [
                        'name' => 'سكاكين احترافية',
                        'is_included' => true,
                    ],
                    [
                        'name' => 'فرن',
                        'is_included' => false,
                    ],
                ],
            ]);

        $response->assertStatus(201);

        $service = $chef->services()->first();

        $this->assertCount(2, $service->equipment);
        $this->assertDatabaseHas('chef_service_equipment', [
            'chef_service_id' => $service->id,
            'name' => 'سكاكين احترافية',
            'is_included' => true,
        ]);
        $this->assertDatabaseHas('chef_service_equipment', [
            'chef_service_id' => $service->id,
            'name' => 'فرن',
            'is_included' => false,
        ]);
    }

    /** @test */
    public function chef_can_create_package_service()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'باقة العشاء العائلي',
                'description' => 'باقة شاملة للعشاء العائلي',
                'service_type' => 'package',
                'package_price' => 500.00,
                'max_guests_included' => 10,
                'allow_extra_guests' => true,
                'extra_guest_price' => 30.00,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('chef_services', [
            'chef_id' => $chef->id,
            'name' => 'باقة العشاء العائلي',
            'service_type' => 'package',
            'package_price' => 500.00,
            'max_guests_included' => 10,
            'allow_extra_guests' => true,
            'extra_guest_price' => 30.00,
        ]);
    }

    /** @test */
    public function service_name_is_required()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'description' => 'خدمة طهي احترافية',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function service_type_must_be_valid()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي',
                'service_type' => 'invalid_type',
                'hourly_rate' => 150.00,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['service_type']);
    }

    /** @test */
    public function hourly_rate_is_required_for_hourly_service()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي',
                'service_type' => 'hourly',
                'min_hours' => 3,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['hourly_rate']);
    }

    /** @test */
    public function package_price_is_required_for_package_service()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'باقة العشاء',
                'service_type' => 'package',
                'max_guests_included' => 10,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['package_price']);
    }

    /** @test */
    public function non_chef_user_cannot_create_service()
    {
        $user = User::factory()->create(['user_type' => 'customer']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'name' => 'خدمة الطهي',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_service()
    {
        $response = $this->postJson('/api/chef/chef-services', [
            'name' => 'خدمة الطهي',
            'service_type' => 'hourly',
            'hourly_rate' => 150.00,
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function slug_is_generated_automatically()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي المنزلي الفاخر',
                'description' => 'خدمة طهي احترافية',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
                'min_hours' => 3,
            ]);

        $response->assertStatus(201);

        $service = $chef->services()->first();

        $this->assertNotNull($service->slug);
    }

    /** @test */
    public function rest_hours_required_defaults_to_two()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/chef-services', [
                'chef_id' => $chef->id,
                'name' => 'خدمة الطهي',
                'service_type' => 'hourly',
                'hourly_rate' => 150.00,
                'min_hours' => 3,
            ]);

        $response->assertStatus(201);

        $service = $chef->services()->first();

        $this->assertEquals(2, $service->rest_hours_required);
    }
}
