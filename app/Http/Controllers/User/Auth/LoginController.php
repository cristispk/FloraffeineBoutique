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

        if (! $authService->attemptLoginForRole(User::ROLE_USER, $credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __('These credentials do not match our records.'),
                ]);
        }

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

