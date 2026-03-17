<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('public.auth.register');
    }

    public function register(UserRegisterRequest $request, AuthService $authService): RedirectResponse
    {
        $user = $authService->registerUser($request->validated());

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}

