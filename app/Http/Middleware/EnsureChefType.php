<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureChefType
{
    /**
     * Handle an incoming request.
     *
     * Verify that the authenticated user is a chef (user_type = 'chef')
     * and has an associated Chef profile.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('chef')->user();

        // Check if user is authenticated
        if (!$user) {
            return $this->redirectToLogin('يجب تسجيل الدخول أولاً.');
        }

        // Check if user type is 'chef'
        if ($user->user_type !== 'chef') {
            Auth::guard('chef')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return $this->redirectToLogin('هذا الحساب غير مصرح له بالدخول كشيف.');
        }

        // Check if user account is active
        if (!$user->is_active) {
            Auth::guard('chef')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return $this->redirectToLogin('هذا الحساب معطل. يرجى التواصل مع الدعم.');
        }

        // Check if chef profile exists
        if (!$user->chef) {
            Auth::guard('chef')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return $this->redirectToLogin('لم يتم العثور على ملف الشيف. يرجى التواصل مع الدعم.');
        }

        // Check if chef profile is active
        if (!$user->chef->is_active) {
            Auth::guard('chef')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return $this->redirectToLogin('ملف الشيف الخاص بك معطل. يرجى التواصل مع الدعم.');
        }

        return $next($request);
    }

    /**
     * Redirect to chef login page with error message.
     */
    protected function redirectToLogin(string $message): Response
    {
        return redirect()->route('chef.login')
            ->withErrors(['email' => $message]);
    }
}
