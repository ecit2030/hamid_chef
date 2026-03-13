<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => __('Name is required'),
            'email.required' => __('Email is required'),
            'email.email' => __('Please enter a valid email'),
            'message.required' => __('Message is required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        ContactMessage::create($validator->validated());

        return response()->json([
            'message' => __('Your message has been sent successfully. We will contact you soon.'),
        ], 200);
    }
}
