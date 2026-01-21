<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChefProfileRequest;
use App\Http\Resources\ChefResource;
use App\Services\ChefService;
use Illuminate\Http\JsonResponse;

class ChefProfileController extends Controller
{
    protected ChefService $chefService;

    public function __construct(ChefService $chefService)
    {
        $this->chefService = $chefService;
    }

    /**
     * Update the authenticated chef's profile
     *
     * @param UpdateChefProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateChefProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        // Verify user is a chef
        if ($user->user_type !== 'chef') {
            return response()->json([
                'message' => __('Unauthorized. User is not a chef.'),
            ], 403);
        }

        // Get chef profile
        $chef = $user->chef;

        if (!$chef) {
            return response()->json([
                'message' => __('Chef profile not found.'),
            ], 404);
        }

        $updatedChef = $this->chefService->updateChefProfile($chef, $request->validated());

        return response()->json([
            'message' => __('Chef profile updated successfully'),
            'data' => new ChefResource($updatedChef),
        ]);
    }

    /**
     * Get the authenticated chef's profile
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();

        // Verify user is a chef
        if ($user->user_type !== 'chef') {
            return response()->json([
                'message' => __('Unauthorized. User is not a chef.'),
            ], 403);
        }

        $chef = $user->chef;

        if (!$chef) {
            return response()->json([
                'message' => __('Chef profile not found.'),
            ], 404);
        }

        return response()->json([
            'data' => new ChefResource($chef),
        ]);
    }
}
