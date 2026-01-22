<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_update_their_profile()
    {
        $user = User::factory()->create([
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'email' => 'ahmed@example.com',
            'user_type' => 'customer',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'first_name' => 'علي',
                'last_name' => 'حسن',
                'phone_number' => '+966501234567',
                'address' => 'الرياض، المملكة العربية السعودية',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone_number',
                    'address',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'علي',
            'last_name' => 'حسن',
            'phone_number' => '+966501234567',
            'address' => 'الرياض، المملكة العربية السعودية',
        ]);
    }

    /** @test */
    public function user_can_upload_avatar()
    {
        $user = User::factory()->create([
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'user_type' => 'customer',
        ]);

        $avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'first_name' => 'أحمد',
                'avatar' => $avatar,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'first_name',
                    'avatar',
                ],
            ]);

        $user->refresh();

        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function user_can_update_avatar()
    {
        $user = User::factory()->create([
            'first_name' => 'أحمد',
            'user_type' => 'customer',
        ]);

        // Upload first avatar
        $oldAvatar = UploadedFile::fake()->image('old-avatar.jpg');
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'avatar' => $oldAvatar,
            ]);

        $user->refresh();
        $oldAvatarPath = $user->avatar;

        // Upload new avatar
        $newAvatar = UploadedFile::fake()->image('new-avatar.jpg');
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'avatar' => $newAvatar,
            ]);

        $response->assertStatus(200);

        $user->refresh();

        $this->assertNotEquals($oldAvatarPath, $user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
        Storage::disk('public')->assertMissing($oldAvatarPath);
    }

    /** @test */
    public function avatar_must_be_valid_image()
    {
        $user = User::factory()->create(['user_type' => 'customer']);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'avatar' => $invalidFile,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['avatar']);
    }

    /** @test */
    public function avatar_must_not_exceed_max_size()
    {
        $user = User::factory()->create(['user_type' => 'customer']);

        $largeFile = UploadedFile::fake()->image('large-avatar.jpg')->size(3000); // 3MB

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'avatar' => $largeFile,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['avatar']);
    }

    /** @test */
    public function user_can_update_profile_without_avatar()
    {
        $user = User::factory()->create([
            'first_name' => 'أحمد',
            'user_type' => 'customer',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/profile', [
                'first_name' => 'محمد',
                'last_name' => 'علي',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'محمد',
            'last_name' => 'علي',
        ]);
    }

    /** @test */
    public function email_must_be_unique_when_updating()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($user2, 'sanctum')
            ->postJson('/api/profile', [
                'email' => 'user1@example.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function phone_number_must_be_unique_when_updating()
    {
        $user1 = User::factory()->create(['phone_number' => '+966501111111']);
        $user2 = User::factory()->create(['phone_number' => '+966502222222']);

        $response = $this->actingAs($user2, 'sanctum')
            ->postJson('/api/profile', [
                'phone_number' => '+966501111111',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number']);
    }

    /** @test */
    public function unauthenticated_user_cannot_update_profile()
    {
        $response = $this->postJson('/api/profile', [
            'first_name' => 'أحمد',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_view_their_profile()
    {
        $user = User::factory()->create([
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'email' => 'ahmed@example.com',
            'user_type' => 'customer',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone_number',
                    'address',
                    'avatar',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'first_name' => 'أحمد',
                    'last_name' => 'محمد',
                    'email' => 'ahmed@example.com',
                ],
            ]);
    }
}
