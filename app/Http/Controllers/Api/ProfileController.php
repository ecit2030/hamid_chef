<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Update the authenticated user's profile
     *
     * @param UpdateUserProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $updatedUser = $this->userService->updateProfile($user, $request->validated());

        return response()->json([
            'message' => __('Profile updated successfully'),
            'data' => new UserResource($updatedUser),
        ]);
    }

    /**
     * Get the authenticated user's profile
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }
}
