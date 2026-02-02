<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DiscountCodeService;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function __construct(
        private DiscountCodeService $discountCodeService
    ) {}

    /**
     * التحقق من كود الخصم
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $result = $this->discountCodeService->validateCode(
                $request->code,
                $request->user()->id,
                $request->amount
            );

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'الكود صالح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
