<?php

namespace Tests\Feature\Api;

use App\Models\Chef;
use App\Models\User;
use App\Models\Governorate;
use App\Models\District;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChefProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function chef_can_update_their_profile()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'name' => 'الطاهي أحمد',
            'short_description' => 'طاهي محترف',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'name' => 'الطاهي محمد',
                'long_description' => 'طاهي متخصص في المأكولات العربية',
                'short_description' => 'خبرة 10 سنوات',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'short_description',
                ],
            ]);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'name' => 'الطاهي محمد',
            'long_description' => 'طاهي متخصص في المأكولات العربية',
            'short_description' => 'خبرة 10 سنوات',
        ]);
    }

    /** @test */
    public function chef_can_update_avatar_through_profile()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $avatar = UploadedFile::fake()->image('chef-avatar.jpg', 300, 300);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'name' => 'الطاهي أحمد',
                'avatar' => $avatar,
            ]);

        $response->assertStatus(200);

        $user->refresh();

        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function chef_can_update_location_details()
    {
        $governorate = Governorate::factory()->create([
            'name_ar' => 'الرياض',
            'name_en' => 'Riyadh',
        ]);
        $district = District::factory()->create([
            'governorate_id' => $governorate->id,
            'name_ar' => 'العليا',
            'name_en' => 'Al Olaya',
        ]);
        $area = Area::factory()->create([
            'district_id' => $district->id,
            'name_ar' => 'حي العليا',
            'name_en' => 'Al Olaya District',
        ]);

        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'governorate_id' => $governorate->id,
                'district_id' => $district->id,
                'area_id' => $area->id,
                'address' => 'شارع الملك فهد',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'governorate_id' => $governorate->id,
            'district_id' => $district->id,
            'area_id' => $area->id,
        ]);
    }

    /** @test */
    public function chef_can_update_base_hourly_rate()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'base_hourly_rate' => 100.00,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'base_hourly_rate' => 150.50,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chefs', [
            'id' => $chef->id,
            'base_hourly_rate' => 150.50,
        ]);
    }

    /** @test */
    public function non_chef_user_cannot_update_chef_profile()
    {
        $user = User::factory()->create(['user_type' => 'customer']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'name' => 'الطاهي أحمد',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function chef_without_profile_gets_404()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        // No chef profile created

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'name' => 'الطاهي أحمد',
            ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function chef_can_view_their_profile()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create([
            'user_id' => $user->id,
            'name' => 'الطاهي أحمد',
            'short_description' => 'طاهي محترف',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/chef/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'short_description',
                    'base_hourly_rate',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $chef->id,
                    'name' => 'الطاهي أحمد',
                    'short_description' => 'طاهي محترف',
                ],
            ]);
    }

    /** @test */
    public function base_hourly_rate_must_be_positive()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'base_hourly_rate' => -50,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['base_hourly_rate']);
    }

    /** @test */
    public function base_hourly_rate_cannot_exceed_maximum()
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $chef = Chef::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/chef/profile', [
                'base_hourly_rate' => 10000,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['base_hourly_rate']);
    }

    /** @test */
    public function unauthenticated_user_cannot_update_chef_profile()
    {
        $response = $this->postJson('/api/chef/profile', [
            'name' => 'الطاهي أحمد',
        ]);

        $response->assertStatus(401);
    }
}
