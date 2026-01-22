<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateChefProfileRequest;
use App\Http\Resources\ChefResource;
use App\Services\ChefService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Separate user data from chef data
            $userData = [];
            $chefData = [];

            // User fields that should be updated in users table
            $userFields = ['avatar'];

            foreach ($data as $key => $value) {
                if (in_array($key, $userFields)) {
                    $userData[$key] = $value;
                } else {
                    $chefData[$key] = $value;
                }
            }

            // Update user data if present (handle avatar upload)
            if (!empty($userData)) {
                foreach ($userData as $key => $value) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        // Store file
                        $filename = Str::uuid() . '.' . $value->getClientOriginalExtension();
                        $path = $value->storeAs('users', $filename, 'public');

                        // Delete old file if exists
                        if ($user->$key && Storage::disk('public')->exists($user->$key)) {
                            Storage::disk('public')->delete($user->$key);
                        }

                        $userData[$key] = $path;
                    }
                }

                $user->update($userData);
            }

            // Update chef data if present
            if (!empty($chefData)) {
                $updatedChef = $this->chefService->updateChefProfile($chef, $chefData);
            } else {
                $updatedChef = $chef->fresh();
            }

            DB::commit();

            return response()->json([
                'message' => __('Chef profile updated successfully'),
                'data' => new ChefResource($updatedChef),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
