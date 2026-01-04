<?php

namespace App\Http\Controllers\Chef\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chef\LoginRequest;
use App\Services\ChefAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    protected ChefAuthService $authService;

    public function __construct(ChefAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Chef/Auth/Login', [
            'canResetPassword' => Route::has('chef.password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->authenticate([
            'email' => $request->email,
            'password' => $request->password,
            'remember' => $request->boolean('remember'),
        ]);

        if (!$result['success']) {
            throw ValidationException::withMessages([
                'email' => $result['message'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('chef.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('chef.login');
    }
}
