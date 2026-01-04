<?php

namespace App\Http\Controllers\Chef\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:chef'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $request->user('chef')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'تم تحديث كلمة المرور بنجاح.');
    }
}
