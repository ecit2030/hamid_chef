<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FbTokenController extends Controller
{
    /**
     * Store or update the authenticated user's Facebook / device token for notifications.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fb_token' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();
        $user->fb_token = $validated['fb_token'];
        $user->save();

        return response()->json([
            'message' => __('FB token updated successfully'),
        ]);
    }
}
