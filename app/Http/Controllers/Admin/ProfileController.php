<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function show(): Response
    {
        return Inertia::render('Others/UserProfile');
    }

    /**
     * Update the authenticated admin's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone_number' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $filename = (string) Str::uuid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $validated['avatar'] = $request->file('avatar')->storeAs('admins', $filename, 'public');
        }

        $admin->update($validated);

        return redirect()->route('admin.profile')->with('success', __('تم تحديث الملف الشخصي بنجاح'));
    }
}
