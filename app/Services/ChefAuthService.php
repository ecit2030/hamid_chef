<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChefAuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate a chef user
     *
     * @param array $credentials ['email' => string, 'password' => string, 'remember' => bool]
     * @param string $guard
     * @return array ['success' => bool, 'message' => string, 'user' => ?User]
     */
    public function authenticate(array $credentials, string $guard = 'chef'): array
    {
        // Find user by email
        $user = $this->userRepository->findByEmail($credentials['email'], ['chef']);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة.',
                'user' => null,
            ];
        }

        // Check if user type is 'chef'
        if ($user->user_type !== 'chef') {
            return [
                'success' => false,
                'message' => 'هذا الحساب غير مصرح له بالدخول كشيف.',
                'user' => null,
            ];
        }

        // Check if user account is active
        if (!$user->is_active) {
            return [
                'success' => false,
                'message' => 'هذا الحساب معطل. يرجى التواصل مع الدعم.',
                'user' => null,
            ];
        }

        // Check if chef profile exists
        if (!$user->chef) {
            return [
                'success' => false,
                'message' => 'لم يتم العثور على ملف الشيف. يرجى التواصل مع الدعم.',
                'user' => null,
            ];
        }

        // Check if chef profile is active
        if (!$user->chef->is_active) {
            return [
                'success' => false,
                'message' => 'ملف الشيف الخاص بك معطل. يرجى التواصل مع الدعم.',
                'user' => null,
            ];
        }

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة.',
                'user' => null,
            ];
        }

        // Login the user
        Auth::guard($guard)->login($user, $credentials['remember'] ?? false);

        return [
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح.',
            'user' => $user,
        ];
    }

    /**
     * Logout the chef user
     *
     * @param string $guard
     * @return void
     */
    public function logout(string $guard = 'chef'): void
    {
        Auth::guard($guard)->logout();
    }

    /**
     * Get the currently authenticated chef
     *
     * @param string $guard
     * @return User|null
     */
    public function user(string $guard = 'chef'): ?User
    {
        return Auth::guard($guard)->user();
    }

    /**
     * Check if a chef is authenticated
     *
     * @param string $guard
     * @return bool
     */
    public function check(string $guard = 'chef'): bool
    {
        return Auth::guard($guard)->check();
    }

    /**
     * Get the chef profile for the authenticated user
     *
     * @param string $guard
     * @return \App\Models\Chef|null
     */
    public function chef(string $guard = 'chef')
    {
        $user = $this->user($guard);
        return $user?->chef;
    }
}
