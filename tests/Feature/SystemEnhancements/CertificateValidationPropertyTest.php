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

/**
 * Property-Based Tests for Certificate Validation
 *
 * Feature: system-enhancements
 * Properties: 6 (Certificate Type Validation), 7 (Certificate File Size Limit)
 */
class CertificateValidationPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed locations to avoid foreign key constraint failures
        $this->seed(LocationSeeder::class);

        Storage::fake('local');
    }

    /**
     * Property 6: Certificate Type Validation
     *
     * For any certificate upload, the system should only accept certificate types
     * from the predefined set: identity_document, health_certificate, professional_certificate.
     *
     * Validates: Requirements 7.2
     */
    public function test_certificate_type_validation_property(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        Chef::factory()->create(['user_id' => $user->id]);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Valid certificate types
        $validTypes = ['identity_document', 'health_certificate', 'professional_certificate'];

        foreach ($validTypes as $type) {
            $file = UploadedFile::fake()->create('certificate.pdf', 1024);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => $type,
                'file' => $file,
            ]);

            // Should accept valid types
            $this->assertContains($response->status(), [200, 201],
                "Valid certificate type '{$type}' should be accepted");
        }

        // Invalid certificate types
        $invalidTypes = [
            'invalid_type',
            'random_certificate',
            'document',
            'certificate',
            '',
            'identity',
            'health',
            'professional',
        ];

        foreach ($invalidTypes as $type) {
            $file = UploadedFile::fake()->create('certificate.pdf', 1024);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => $type,
                'file' => $file,
            ]);

            // Should reject invalid types
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['certificate_type']);
        }
    }

    /**
     * Property 7: Certificate File Size Limit
     *
     * For any certificate file upload, if the file size exceeds 5MB,
     * the system should reject the upload.
     *
     * Validates: Requirements 7.4
     */
    public function test_certificate_file_size_limit_property(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        Chef::factory()->create(['user_id' => $user->id]);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Test files within size limit (should pass)
        $validSizes = [100, 1024, 2048, 4096, 5120]; // Up to 5MB

        foreach ($validSizes as $sizeInKb) {
            $file = UploadedFile::fake()->create('certificate.pdf', $sizeInKb);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

            // Should accept files within size limit
            $this->assertContains($response->status(), [200, 201],
                "File size {$sizeInKb}KB should be accepted (within 5MB limit)");
        }

        // Test files exceeding size limit (should fail)
        $invalidSizes = [5121, 6000, 10000, 20000]; // Over 5MB

        foreach ($invalidSizes as $sizeInKb) {
            $file = UploadedFile::fake()->create('certificate.pdf', $sizeInKb);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

            // Should reject files exceeding size limit
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['file']);
        }
    }

    /**
     * Test file type validation
     */
    public function test_certificate_file_type_validation(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        Chef::factory()->create(['user_id' => $user->id]);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Valid file types
        $validTypes = ['jpg', 'jpeg', 'png', 'pdf'];

        foreach ($validTypes as $extension) {
            $file = UploadedFile::fake()->create("certificate.{$extension}", 1024);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

            // Should accept valid file types
            $this->assertContains($response->status(), [200, 201],
                "File type '{$extension}' should be accepted");
        }

        // Invalid file types
        $invalidTypes = ['doc', 'docx', 'txt', 'zip', 'exe'];

        foreach ($invalidTypes as $extension) {
            $file = UploadedFile::fake()->create("certificate.{$extension}", 1024);

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
                'certificate_type' => 'identity_document',
                'file' => $file,
            ]);

            // Should reject invalid file types
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['file']);
        }
    }

    /**
     * Test missing required fields
     */
    public function test_certificate_upload_requires_all_fields(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        Chef::factory()->create(['user_id' => $user->id]);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Missing certificate_type
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
            'file' => UploadedFile::fake()->create('certificate.pdf', 1024),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['certificate_type']);

        // Missing file
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/chef/kyc/certificates', [
            'certificate_type' => 'identity_document',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }
}
