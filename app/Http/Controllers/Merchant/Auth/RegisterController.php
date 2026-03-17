<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\Auth\MerchantRegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('merchant.auth.register');
    }

    public function register(MerchantRegisterRequest $request, AuthService $authService): RedirectResponse
    {
        $merchant = $authService->registerMerchant($request->validated());

        Auth::login($merchant);

        return redirect()->route('merchant.dashboard');
    }
}

