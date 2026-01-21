<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\Chef;
use App\Models\Kyc;
use App\Models\User;
use Database\Seeders\LocationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KycCertificateApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $chef;
    protected Kyc $kyc;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed locations to avoid foreign key constraint failures
        $this->seed(LocationSeeder::class);

        // Create a chef user with KYC record
        $this->chef = User::factory()->create([
            'user_type' => 'chef',
            'email' => 'chef@example.com',
        ]);

        Chef::factory()->create([
            'user_id' => $this->chef->id,
        ]);

        $this->kyc = Kyc::factory()->create([
            'user_id' => $this->chef->id,
            'status' => 'pending',
        ]);

        // Use fake storage for testing
        Storage::fake('local');
    }

    /** @test */
    public function it_uploads_identity_document_certificate()
    {
        $file = UploadedFile::fake()->create('identity.pdf', 1024);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'certificate_type',
                    'certificate' => ['path', 'uploaded_at', 'file_type'],
                ],
            ]);

        // Verify file was stored
        $this->kyc->refresh();
        $this->assertArrayHasKey('identity_document', $this->kyc->certificates);
        $this->assertStringContainsString('kyc/certificates/', $this->kyc->certificates['identity_document']['path']);
    }

    /** @test */
    public function it_uploads_health_certificate()
    {
        $file = UploadedFile::fake()->create('health.pdf', 1024);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'health_certificate',
                'file' => $file,
            ]);

        $response->assertStatus(201);

        $this->kyc->refresh();
        $this->assertArrayHasKey('health_certificate', $this->kyc->certificates);
    }

    /** @test */
    public function it_uploads_professional_certificate()
    {
        $file = UploadedFile::fake()->create('professional.pdf', 1024);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'professional_certificate',
                'file' => $file,
            ]);

        $response->assertStatus(201);

        $this->kyc->refresh();
        $this->assertArrayHasKey('professional_certificate', $this->kyc->certificates);
    }

    /** @test */
    public function it_lists_all_certificates()
    {
        // Upload multiple certificates
        $this->kyc->certificates = [
            'identity_document' => [
                'path' => 'kyc/certificates/identity.pdf',
                'uploaded_at' => now()->toIso8601String(),
                'file_type' => 'pdf',
            ],
            'health_certificate' => [
                'path' => 'kyc/certificates/health.jpg',
                'uploaded_at' => now()->toIso8601String(),
                'file_type' => 'jpg',
            ],
        ];
        $this->kyc->save();

        $response = $this->actingAs($this->chef, 'sanctum')
            ->getJson('/api/chef/kyc/certificates');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'identity_document' => ['path', 'uploaded_at', 'file_type', 'url'],
                    'health_certificate' => ['path', 'uploaded_at', 'file_type', 'url'],
                ],
            ]);

        $data = $response->json('data');
        $this->assertArrayHasKey('identity_document', $data);
        $this->assertArrayHasKey('health_certificate', $data);
        $this->assertNotNull($data['identity_document']['url']);
        $this->assertNotNull($data['health_certificate']['url']);
    }

    /** @test */
    public function it_deletes_specific_certificate_type()
    {
        // Setup certificates
        $this->kyc->certificates = [
            'identity_document' => [
                'path' => 'kyc/certificates/identity.pdf',
                'uploaded_at' => now()->toIso8601String(),
                'file_type' => 'pdf',
            ],
            'health_certificate' => [
                'path' => 'kyc/certificates/health.jpg',
                'uploaded_at' => now()->toIso8601String(),
                'file_type' => 'jpg',
            ],
        ];
        $this->kyc->save();

        // Create fake files in storage
        Storage::disk('local')->put('kyc/certificates/identity.pdf', 'fake content');
        Storage::disk('local')->put('kyc/certificates/health.jpg', 'fake content');

        $response = $this->actingAs($this->chef, 'sanctum')
            ->deleteJson('/api/chef/kyc/certificates/identity_document');

        $response->assertStatus(200)
            ->assertJson(['message' => __('Certificate deleted successfully')]);

        $this->kyc->refresh();
        $this->assertArrayNotHasKey('identity_document', $this->kyc->certificates);
        $this->assertArrayHasKey('health_certificate', $this->kyc->certificates);
    }

    /** @test */
    public function it_rejects_invalid_certificate_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'invalid_type',
                'file' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['certificate_type']);
    }

    /** @test */
    public function it_rejects_file_exceeding_size_limit()
    {
        // Create a file larger than 5MB
        $file = UploadedFile::fake()->create('large.pdf', 6000);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_rejects_invalid_file_type()
    {
        $file = UploadedFile::fake()->create('document.txt', 1024);

        $response = $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_requires_authentication_to_upload_certificate()
    {
        $file = UploadedFile::fake()->create('identity.pdf', 1024);

        $response = $this->postJson('/api/chef/kyc/certificates', [
            'certificate_type' => 'identity_document',
            'file' => $file,
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_requires_authentication_to_list_certificates()
    {
        $response = $this->getJson('/api/chef/kyc/certificates');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_requires_authentication_to_delete_certificate()
    {
        $response = $this->deleteJson('/api/chef/kyc/certificates/identity_document');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_prevents_non_chef_users_from_uploading_certificates()
    {
        $regularUser = User::factory()->create([
            'user_type' => 'customer',
        ]);

        $file = UploadedFile::fake()->create('identity.pdf', 1024);

        $response = $this->actingAs($regularUser, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_returns_404_when_kyc_record_not_found()
    {
        // Create chef without KYC record
        $chefWithoutKyc = User::factory()->create([
            'user_type' => 'chef',
        ]);

        Chef::factory()->create([
            'user_id' => $chefWithoutKyc->id,
        ]);

        $file = UploadedFile::fake()->create('identity.pdf', 1024);

        $response = $this->actingAs($chefWithoutKyc, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

        $response->assertStatus(404)
            ->assertJson(['message' => __('KYC record not found. Please create a KYC record first.')]);
    }

    /** @test */
    public function it_replaces_existing_certificate_of_same_type()
    {
        // Upload first certificate
        $file1 = UploadedFile::fake()->create('identity1.pdf', 1024);

        $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file1,
            ]);

        $this->kyc->refresh();
        $firstPath = $this->kyc->certificates['identity_document']['path'];

        // Upload second certificate of same type
        $file2 = UploadedFile::fake()->create('identity2.pdf', 1024);

        $this->actingAs($this->chef, 'sanctum')
            ->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file2,
            ]);

        $this->kyc->refresh();
        $secondPath = $this->kyc->certificates['identity_document']['path'];

        // Verify the path changed (new file uploaded)
        $this->assertNotEquals($firstPath, $secondPath);
    }

    /** @test */
    public function it_validates_certificate_type_when_deleting()
    {
        $response = $this->actingAs($this->chef, 'sanctum')
            ->deleteJson('/api/chef/kyc/certificates/invalid_type');

        $response->assertStatus(422)
            ->assertJson(['message' => __('Invalid certificate type.')]);
    }

    /** @test */
    public function it_returns_empty_array_when_no_certificates_exist()
    {
        $response = $this->actingAs($this->chef, 'sanctum')
            ->getJson('/api/chef/kyc/certificates');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }
}
