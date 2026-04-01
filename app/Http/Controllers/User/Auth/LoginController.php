<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('public.auth.login');
    }

    public function login(UserLoginRequest $request, AuthService $authService): RedirectResponse
    {
        $credentials = $request->validated();

        $user = $authService->attemptLoginForNonAdmin($credentials);

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __('These credentials do not match our records.'),
                ]);
        }

        if ($user->role === User::ROLE_MERCHANT) {
            return redirect()->route('merchant.dashboard');
        }

        if ($user->role === User::ROLE_USER) {
            return redirect()->route('user.dashboard');
        }

        $authService->logout();

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => __('These credentials do not match our records.'),
            ]);
    }

    public function logout(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

