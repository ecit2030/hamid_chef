<?php

namespace Tests\Unit\Services;

use App\Models\Kyc;
use App\Models\User;
use App\Services\KycService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Unit Tests for KycService Certificate Methods
 *
 * Feature: system-enhancements
 * Tests: Certificate management methods in KycService
 */
class KycServiceTest extends TestCase
{
    use RefreshDatabase;

    protected KycService $kycService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->kycService = app(KycService::class);
    }

    /**
     * Test adding identity document certificate
     */
    public function test_add_identity_document_certificate(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('identity.pdf', 1024);
        $updatedKyc = $this->kycService->addCertificate($kyc, 'identity_document', $file);

        $this->assertNotNull($updatedKyc->certificates);
        $this->assertArrayHasKey('identity_document', $updatedKyc->certificates);
        $this->assertArrayHasKey('path', $updatedKyc->certificates['identity_document']);
        $this->assertArrayHasKey('uploaded_at', $updatedKyc->certificates['identity_document']);
        $this->assertArrayHasKey('file_type', $updatedKyc->certificates['identity_document']);
    }

    /**
     * Test adding health certificate
     */
    public function test_add_health_certificate(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('health.jpg', 1024);

        $updatedKyc = $this->kycService->addCertificate($kyc, 'health_certificate', $file);

        $this->assertNotNull($updatedKyc->certificates);
        $this->assertArrayHasKey('health_certificate', $updatedKyc->certificates);
        $this->assertEquals('jpg', $updatedKyc->certificates['health_certificate']['file_type']);
    }

    /**
     * Test adding professional certificate
     */
    public function test_add_professional_certificate(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('professional.png', 1024);
        $updatedKyc = $this->kycService->addCertificate($kyc, 'professional_certificate', $file);

        $this->assertNotNull($updatedKyc->certificates);
        $this->assertArrayHasKey('professional_certificate', $updatedKyc->certificates);
        $this->assertEquals('png', $updatedKyc->certificates['professional_certificate']['file_type']);
    }

    /**
     * Test removing certificate
     */
    public function test_remove_certificate(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Add certificate
        $file = UploadedFile::fake()->create('identity.pdf', 1024);
        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $file);

        $this->assertArrayHasKey('identity_document', $kyc->certificates);

        // Remove certificate
        $updatedKyc = $this->kycService->removeCertificate($kyc, 'identity_document');

        $this->assertArrayNotHasKey('identity_document', $updatedKyc->certificates);
    }

    /**
     * Test getting all certificates
     */
    public function test_get_all_certificates(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Add multiple certificates
        $identityFile = UploadedFile::fake()->create('identity.pdf', 1024);
        $healthFile = UploadedFile::fake()->create('health.pdf', 1024);

        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $identityFile);
        $kyc = $this->kycService->addCertificate($kyc, 'health_certificate', $healthFile);

        // Get all certificates
        $certificates = $this->kycService->getCertificates($kyc);

        $this->assertIsArray($certificates);
        $this->assertCount(2, $certificates);
        $this->assertArrayHasKey('identity_document', $certificates);
        $this->assertArrayHasKey('health_certificate', $certificates);
    }

    /**
     * Test getting certificates by type
     */
    public function test_get_certificates_by_type(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Add certificate
        $file = UploadedFile::fake()->create('identity.pdf', 1024);
        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $file);

        // Get specific type
        $identityCert = $this->kycService->getCertificatesByType($kyc, 'identity_document');
        $healthCert = $this->kycService->getCertificatesByType($kyc, 'health_certificate');

        $this->assertNotNull($identityCert);
        $this->assertNull($healthCert);
        $this->assertArrayHasKey('path', $identityCert);
    }

    /**
     * Test JSON encoding/decoding of certificates
     */
    public function test_certificates_json_encoding_decoding(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Add certificate
        $file = UploadedFile::fake()->create('identity.pdf', 1024);
        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $file);

        // Refresh from database to test JSON encoding/decoding
        $kyc->refresh();

        $certificates = $kyc->certificates;
        $this->assertIsArray($certificates);
        $this->assertArrayHasKey('identity_document', $certificates);
        $this->assertArrayHasKey('path', $certificates['identity_document']);
        $this->assertArrayHasKey('uploaded_at', $certificates['identity_document']);
        $this->assertArrayHasKey('file_type', $certificates['identity_document']);
    }

    /**
     * Test file is stored in correct location
     */
    public function test_certificate_file_stored_in_correct_location(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('identity.pdf', 1024);
        $updatedKyc = $this->kycService->addCertificate($kyc, 'identity_document', $file);

        $path = $updatedKyc->certificates['identity_document']['path'];

        // Verify file exists in storage
        Storage::disk('local')->assertExists($path);

        // Verify path starts with kyc/certificates
        $this->assertStringStartsWith('kyc/certificates/', $path);
    }

    /**
     * Test removing non-existent certificate does not cause error
     */
    public function test_remove_non_existent_certificate_does_not_error(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Try to remove certificate that doesn't exist
        $updatedKyc = $this->kycService->removeCertificate($kyc, 'identity_document');

        $this->assertNotNull($updatedKyc);
        $this->assertEmpty($updatedKyc->certificates ?? []);
    }

    /**
     * Test getting certificates from KYC with no certificates
     */
    public function test_get_certificates_from_empty_kyc(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        $certificates = $this->kycService->getCertificates($kyc);

        $this->assertIsArray($certificates);
        $this->assertEmpty($certificates);
    }
}
