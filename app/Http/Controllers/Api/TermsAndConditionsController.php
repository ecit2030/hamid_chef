<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TermsAndConditionsService;
use App\DTOs\TermsAndConditionsDTO;
use App\Http\Traits\SuccessResponse;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    use SuccessResponse;

    public function __construct(
        protected TermsAndConditionsService $service
    ) {}

    /**
     * Get the active terms and conditions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $terms = $this->service->getActive();

        if (!$terms) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد شروط وأحكام نشطة',
            ], 404);
        }

        $dto = TermsAndConditionsDTO::fromModel($terms);

        return $this->successResponse(
            $dto->toArray(),
            'تم جلب الشروط والأحكام بنجاح'
        );
    }

    /**
     * Get all versions (for history)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function versions(Request $request)
    {
        $terms = $this->service->all();

        $data = $terms->map(function ($term) {
            $dto = TermsAndConditionsDTO::fromModel($term);
            return $dto->toArray();
        });

        return $this->successResponse(
            $data,
            'تم جلب جميع إصدارات الشروط والأحكام بنجاح'
        );
    }

    /**
     * Get specific version by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $terms = $this->service->find($id);

        $dto = TermsAndConditionsDTO::fromModel($terms);

        return $this->successResponse(
            $dto->toArray(),
            'تم جلب الشروط والأحكام بنجاح'
        );
    }
}
