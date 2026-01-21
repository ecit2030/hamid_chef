<?php

namespace Tests\Feature\SystemEnhancements;

use App\Models\Kyc;
use App\Models\User;
use App\Services\KycService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Property-Based Tests for Certificate Management
 *
 * Feature: system-enhancements
 * Properties: 8 (Certificate Retrieval Consistency), 9 (Certificate Deletion Isolation)
 */
class CertificateManagementPropertyTest extends TestCase
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
     * Property 8: Certificate Retrieval Consistency
     *
     * For any KYC record with certificates, retrieving the certificates
     * should return all certificates that were successfully uploaded.
     *
     * Validates: Requirements 8.2
     */
    public function test_certificate_retrieval_consistency_property(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Upload multiple certificates
        $certificateTypes = ['identity_document', 'health_certificate', 'professional_certificate'];
        $uploadedCertificates = [];

        foreach ($certificateTypes as $type) {
            $file = UploadedFile::fake()->create("certificate_{$type}.pdf", 1024);
            $updatedKyc = $this->kycService->addCertificate($kyc, $type, $file);

            $uploadedCertificates[$type] = true;
            $kyc = $updatedKyc;
        }

        // Retrieve all certificates
        $retrievedCertificates = $this->kycService->getCertificates($kyc);

        // Verify all uploaded certificates are present
        foreach ($certificateTypes as $type) {
            $this->assertArrayHasKey($type, $retrievedCertificates,
                "Certificate type '{$type}' should be present in retrieved certificates");

            $this->assertArrayHasKey('path', $retrievedCertificates[$type],
                "Certificate '{$type}' should have a path");

            $this->assertArrayHasKey('uploaded_at', $retrievedCertificates[$type],
                "Certificate '{$type}' should have an upload timestamp");

            $this->assertArrayHasKey('file_type', $retrievedCertificates[$type],
                "Certificate '{$type}' should have a file type");
        }

        // Verify count matches
        $this->assertCount(count($certificateTypes), $retrievedCertificates,
            "Retrieved certificates count should match uploaded certificates count");
    }

    /**
     * Property 9: Certificate Deletion Isolation
     *
     * For any KYC record with multiple certificates, deleting one certificate
     * should not affect the other certificates.
     *
     * Validates: Requirements 8.4
     */
    public function test_certificate_deletion_isolation_property(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Upload three different certificates
        $identityFile = UploadedFile::fake()->create('identity.pdf', 1024);
        $healthFile = UploadedFile::fake()->create('health.pdf', 1024);
        $professionalFile = UploadedFile::fake()->create('professional.pdf', 1024);

        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $identityFile);
        $kyc = $this->kycService->addCertificate($kyc, 'health_certificate', $healthFile);
        $kyc = $this->kycService->addCertificate($kyc, 'professional_certificate', $professionalFile);

        // Verify all three certificates exist
        $certificatesBefore = $this->kycService->getCertificates($kyc);
        $this->assertCount(3, $certificatesBefore);
        $this->assertArrayHasKey('identity_document', $certificatesBefore);
        $this->assertArrayHasKey('health_certificate', $certificatesBefore);
        $this->assertArrayHasKey('professional_certificate', $certificatesBefore);

        // Delete one certificate (health_certificate)
        $kyc = $this->kycService->removeCertificate($kyc, 'health_certificate');

        // Verify the deleted certificate is gone
        $certificatesAfter = $this->kycService->getCertificates($kyc);
        $this->assertCount(2, $certificatesAfter,
            "After deleting one certificate, count should be 2");

        $this->assertArrayNotHasKey('health_certificate', $certificatesAfter,
            "Deleted certificate should not be present");

        // Verify other certificates are still present and unchanged
        $this->assertArrayHasKey('identity_document', $certificatesAfter,
            "Identity document should still be present after deleting health certificate");

        $this->assertArrayHasKey('professional_certificate', $certificatesAfter,
            "Professional certificate should still be present after deleting health certificate");

        // Verify the remaining certificates have the same data
        $this->assertEquals(
            $certificatesBefore['identity_document']['path'],
            $certificatesAfter['identity_document']['path'],
            "Identity document path should remain unchanged"
        );

        $this->assertEquals(
            $certificatesBefore['professional_certificate']['path'],
            $certificatesAfter['professional_certificate']['path'],
            "Professional certificate path should remain unchanged"
        );
    }

    /**
     * Test adding the same certificate type multiple times (should replace)
     */
    public function test_adding_same_certificate_type_replaces_previous(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Upload first identity document
        $file1 = UploadedFile::fake()->create('identity1.pdf', 1024);
        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $file1);

        $certificates1 = $this->kycService->getCertificates($kyc);
        $firstPath = $certificates1['identity_document']['path'];

        // Upload second identity document (should replace)
        $file2 = UploadedFile::fake()->create('identity2.pdf', 1024);
        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $file2);

        $certificates2 = $this->kycService->getCertificates($kyc);
        $secondPath = $certificates2['identity_document']['path'];

        // Verify only one identity document exists
        $this->assertCount(1, $certificates2);
        $this->assertArrayHasKey('identity_document', $certificates2);

        // Verify the path changed (new file replaced old one)
        $this->assertNotEquals($firstPath, $secondPath,
            "New certificate should have a different path than the old one");
    }

    /**
     * Test getting certificates by specific type
     */
    public function test_get_certificates_by_type(): void
    {
        $user = User::factory()->create(['user_type' => 'chef']);
        $kyc = Kyc::factory()->create(['user_id' => $user->id]);

        // Upload multiple certificates
        $identityFile = UploadedFile::fake()->create('identity.pdf', 1024);
        $healthFile = UploadedFile::fake()->create('health.pdf', 1024);

        $kyc = $this->kycService->addCertificate($kyc, 'identity_document', $identityFile);
        $kyc = $this->kycService->addCertificate($kyc, 'health_certificate', $healthFile);

        // Get specific certificate type
        $identityCert = $this->kycService->getCertificatesByType($kyc, 'identity_document');
        $healthCert = $this->kycService->getCertificatesByType($kyc, 'health_certificate');
        $professionalCert = $this->kycService->getCertificatesByType($kyc, 'professional_certificate');

        // Verify correct certificates are returned
        $this->assertNotNull($identityCert);
        $this->assertNotNull($healthCert);
        $this->assertNull($professionalCert);

        $this->assertArrayHasKey('path', $identityCert);
        $this->assertArrayHasKey('path', $healthCert);
    }
}
