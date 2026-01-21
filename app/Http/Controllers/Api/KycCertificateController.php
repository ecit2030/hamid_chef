<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycCertificateRequest;
use App\Models\Kyc;
use App\Services\KycService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class KycCertificateController extends Controller
{
    protected KycService $kycService;

    public function __construct(KycService $kycService)
    {
        $this->kycService = $kycService;
    }

    /**
     * Upload a certificate for the authenticated user's KYC
     *
     * @param KycCertificateRequest $request
     * @return JsonResponse
     */
    public function store(KycCertificateRequest $request): JsonResponse
    {
        $user = $request->user();

        // Get or create KYC record for the user
        $kyc = Kyc::where('user_id', $user->id)->first();

        if (!$kyc) {
            return response()->json([
                'message' => __('KYC record not found. Please create a KYC record first.'),
            ], 404);
        }

        // Verify user owns the KYC record
        if ($kyc->user_id !== $user->id) {
            return response()->json([
                'message' => __('Unauthorized. You do not own this KYC record.'),
            ], 403);
        }

        $certificateType = $request->input('certificate_type');
        $file = $request->file('file');

        // Add certificate using service
        $updatedKyc = $this->kycService->addCertificate($kyc, $certificateType, $file);

        return response()->json([
            'message' => __('Certificate uploaded successfully'),
            'data' => [
                'certificate_type' => $certificateType,
                'certificate' => $updatedKyc->certificates[$certificateType] ?? null,
            ],
        ], 201);
    }

    /**
     * Get all certificates for the authenticated user's KYC
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $kyc = Kyc::where('user_id', $user->id)->first();

        if (!$kyc) {
            return response()->json([
                'message' => __('KYC record not found.'),
            ], 404);
        }

        $certificates = $this->kycService->getCertificates($kyc);

        // Add URLs to certificates
        $certificatesWithUrls = [];
        foreach ($certificates as $type => $certificate) {
            $certificatesWithUrls[$type] = [
                'path' => $certificate['path'],
                'uploaded_at' => $certificate['uploaded_at'],
                'file_type' => $certificate['file_type'],
                'url' => $this->getCertificateUrl($certificate['path']),
            ];
        }

        return response()->json([
            'data' => $certificatesWithUrls,
        ]);
    }

    /**
     * Delete a specific certificate type
     *
     * @param string $type
     * @return JsonResponse
     */
    public function destroy(string $type): JsonResponse
    {
        $user = auth()->user();

        $kyc = Kyc::where('user_id', $user->id)->first();

        if (!$kyc) {
            return response()->json([
                'message' => __('KYC record not found.'),
            ], 404);
        }

        // Verify user owns the KYC record
        if ($kyc->user_id !== $user->id) {
            return response()->json([
                'message' => __('Unauthorized. You do not own this KYC record.'),
            ], 403);
        }

        // Validate certificate type
        $validTypes = ['identity_document', 'health_certificate', 'professional_certificate'];
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'message' => __('Invalid certificate type.'),
            ], 422);
        }

        // Remove certificate using service
        $this->kycService->removeCertificate($kyc, $type);

        return response()->json([
            'message' => __('Certificate deleted successfully'),
        ]);
    }

    /**
     * Generate a temporary URL for certificate access
     *
     * @param string $path
     * @return string|null
     */
    protected function getCertificateUrl(string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // For private storage, we would typically generate a signed URL
        // For now, return a placeholder or route to a download endpoint
        return route('api.kyc.certificates.download', ['path' => base64_encode($path)]);
    }

    /**
     * Download a certificate file
     *
     * @param string $encodedPath
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(string $encodedPath)
    {
        $user = auth()->user();
        $path = base64_decode($encodedPath);

        // Verify the file belongs to the user's KYC
        $kyc = Kyc::where('user_id', $user->id)->first();

        if (!$kyc) {
            abort(404, __('KYC record not found.'));
        }

        $certificates = $this->kycService->getCertificates($kyc);
        $fileExists = false;

        foreach ($certificates as $certificate) {
            if ($certificate['path'] === $path) {
                $fileExists = true;
                break;
            }
        }

        if (!$fileExists) {
            abort(403, __('Unauthorized access to certificate.'));
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404, __('Certificate file not found.'));
        }

        $fileName = basename($path);
        $mimeType = Storage::disk('local')->mimeType($path);

        return Storage::disk('local')->download($path, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }
}
