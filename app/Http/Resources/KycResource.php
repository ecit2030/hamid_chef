<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KycResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'rejected_reason' => $this->rejected_reason,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'address' => $this->address,
            'document_type' => $this->document_type,
            'certificates' => $this->formatCertificates(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Format certificates with URLs grouped by type
     *
     * @return array
     */
    protected function formatCertificates(): array
    {
        $certificates = $this->certificates ?? [];
        $formatted = [];

        foreach ($certificates as $type => $certificate) {
            $formatted[$type] = [
                'path' => $certificate['path'] ?? null,
                'uploaded_at' => $certificate['uploaded_at'] ?? null,
                'file_type' => $certificate['file_type'] ?? null,
                'url' => $this->getCertificateUrl($certificate['path'] ?? null),
            ];
        }

        return $formatted;
    }

    /**
     * Generate URL for certificate access
     *
     * @param string|null $path
     * @return string|null
     */
    protected function getCertificateUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return route('api.kyc.certificates.download', ['path' => base64_encode($path)]);
    }
}
