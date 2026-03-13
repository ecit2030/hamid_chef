<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\CanFilter;
use App\Http\Traits\ExceptionHandler;
use App\Http\Traits\SuccessResponse;
use App\Services\LandingPageSectionService;
use App\DTOs\LandingPageSectionDTO;

/**
 * Landing Page Sections API Controller
 * 
 * Provides public access to landing page sections with translations
 * 
 * Endpoints:
 * - GET /api/landing-page-sections - Get all active sections (with optional filtering)
 * - GET /api/landing-page-sections/{sectionKey} - Get a specific section by key
 * 
 * Query Parameters for index:
 * - section_key: Filter by single section key (e.g., ?section_key=hero)
 * - section_keys: Filter by multiple section keys (e.g., ?section_keys=hero,features,contact)
 * - active_only: Set to 'false' to include inactive sections (default: true)
 */
class LandingPageSectionController extends Controller
{
    use CanFilter, ExceptionHandler, SuccessResponse;

    /**
     * Get all active landing page sections
     * 
     * @param Request $request
     * @param LandingPageSectionService $service
     * @return \Illuminate\Http\JsonResponse
     * 
     * @queryParam section_key string Filter by single section key. Example: hero
     * @queryParam section_keys string Filter by multiple section keys (comma-separated). Example: hero,features,contact
     * @queryParam active_only boolean Include inactive sections if set to false. Default: true
     * 
     * @response {
     *   "success": true,
     *   "message": "تم جلب أقسام الصفحة الرئيسية بنجاح",
     *   "status_code": 200,
     *   "data": {
     *     "sections": [...],
     *     "total": 10
     *   }
     * }
     */
    public function index(Request $request, LandingPageSectionService $service)
    {
        $query = $service->builder();

        // Filter active sections only (default behavior)
        $activeOnly = $request->input('active_only', 'true');
        if ($activeOnly !== 'false' && $activeOnly !== false) {
            $query->where('is_active', true);
        }

        // Filter by single section_key if provided
        if ($request->has('section_key') && $request->input('section_key')) {
            $query->where('section_key', $request->input('section_key'));
        }

        // Filter by multiple section_keys if provided (comma-separated)
        if ($request->has('section_keys') && $request->input('section_keys')) {
            $keys = array_map('trim', explode(',', $request->input('section_keys')));
            $query->whereIn('section_key', $keys);
        }

        $query = $this->applyFilters(
            $query,
            $request,
            $this->getSearchableFields(),
            $this->getForeignKeyFilters()
        );

        // Order by display_order
        $query->orderBy('display_order');

        $sections = $query->get();

        $data = $sections->map(function ($section) {
            $arr = LandingPageSectionDTO::fromModel($section)->toArray();
            return $this->transformBannersForApi($arr);
        });

        return $this->successResponse(
            [
                'sections' => $data,
                'total' => $data->count(),
            ],
            'تم جلب أقسام الصفحة الرئيسية بنجاح'
        );
    }

    /**
     * Get a specific landing page section by key
     * 
     * @param string $sectionKey
     * @param LandingPageSectionService $service
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response {
     *   "success": true,
     *   "message": "تم جلب القسم بنجاح",
     *   "status_code": 200,
     *   "data": {
     *     "id": 1,
     *     "section_key": "hero",
     *     "title_ar": "...",
     *     "title_en": "...",
     *     ...
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "القسم غير موجود",
     *   "status_code": 404
     * }
     */
    public function show(string $sectionKey, LandingPageSectionService $service)
    {
        $section = $service->builder()
            ->where('section_key', $sectionKey)
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'القسم غير موجود',
                'message_en' => 'Section not found',
                'status_code' => 404,
            ], 404);
        }

        $data = LandingPageSectionDTO::fromModel($section)->toArray();
        $data = $this->transformBannersForApi($data);

        return $this->successResponse(
            $data,
            'تم جلب القسم بنجاح'
        );
    }

    /**
     * Transform banners section images to include full URLs for API consumers
     */
    private function transformBannersForApi(array $sectionData): array
    {
        if (($sectionData['section_key'] ?? '') !== 'banners') {
            return $sectionData;
        }

        $images = $sectionData['additional_data']['images'] ?? [];
        $sectionData['additional_data']['images'] = array_map(function ($img) {
            $path = $img['image'] ?? '';
            return [
                'image' => $path,
                'image_url' => $path ? asset('storage/' . $path) : null,
            ];
        }, $images);

        return $sectionData;
    }
}
