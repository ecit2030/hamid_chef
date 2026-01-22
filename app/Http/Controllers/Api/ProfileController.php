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

        // Get all validated data including files
        $data = $request->validated();

        // Ensure avatar file is included if present in request
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $data['avatar'] = $request->file('avatar');
        }

        // Log for debugging
        \Log::info('ProfileController::update', [
            'user_id' => $user->id,
            'request_all_keys' => array_keys($request->all()),
            'validated_keys' => array_keys($data),
            'has_file_avatar' => $request->hasFile('avatar'),
            'files' => array_keys($request->allFiles()),
        ]);

        $updatedUser = $this->userService->updateProfile($user, $data);

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
