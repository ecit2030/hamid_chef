<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],

            'csrf_token' => fn () => csrf_token(),

            'locale' => fn () => app()->getLocale(),
            'dir'    => fn () => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',

            'auth' => [
                'user' => fn () => $this->resolveAuthUser($request),
                // Some guards (e.g., web users) don't implement Spatie HasRoles. Guard safely.
                'permissions' => fn () => ($u = $request->user()) && method_exists($u, 'getAllPermissions')
                    ? $u->getAllPermissions()->pluck('name')->all()
                    : [],
                'roles' => fn () => ($u = $request->user()) && method_exists($u, 'getRoleNames')
                    ? $u->getRoleNames()->all()
                    : [],
            ],
        ];
    }

    /**
     * Resolve the authenticated user from the appropriate guard (admin, chef, or web).
     */
    private function resolveAuthUser(Request $request): ?array
    {
        $user = $request->user('admin') ?? $request->user('chef') ?? $request->user();

        if (!$user) {
            return null;
        }

        $data = [
            'id' => $user->id,
            'email' => $user->email ?? null,
            'avatar' => $user->avatar ?? null,
            'name' => $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: null,
        ];

        foreach (['first_name', 'last_name', 'phone_number', 'whatsapp_number', 'address'] as $key) {
            if (isset($user->$key)) {
                $data[$key] = $user->$key;
            }
        }

        return $data;
    }
}
