<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\Auth\MerchantLoginRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('merchant.auth.login');
    }

    public function login(MerchantLoginRequest $request, AuthService $authService): RedirectResponse
    {
        $credentials = $request->validated();

        if (! $authService->attemptLoginForRole(User::ROLE_MERCHANT, $credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __('These credentials do not match our records.'),
                ]);
        }

        return redirect()->route('merchant.dashboard');
    }

    public function logout(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/merchant/login');
    }
}

