<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Merchant\Auth\ForgotPasswordController as MerchantForgotPasswordController;
use App\Http\Controllers\Merchant\Auth\LoginController as MerchantLoginController;
use App\Http\Controllers\Merchant\Auth\RegisterController as MerchantRegisterController;
use App\Http\Controllers\Merchant\ActivationController as MerchantActivationController;
use App\Http\Controllers\Merchant\Auth\ResetPasswordController as MerchantResetPasswordController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\OnboardingController as MerchantOnboardingController;
use App\Http\Controllers\User\Auth\ForgotPasswordController as UserForgotPasswordController;
use App\Http\Controllers\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\User\Auth\ResetPasswordController as UserResetPasswordController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Public (user) authentication
 */
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserRegisterController::class, 'register']);

    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login']);

    Route::get('/password/forgot', [UserForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [UserForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [UserResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [UserResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard')
        ->middleware('role:user');
});

/**
 * Merchant authentication
 */
Route::prefix('merchant')->name('merchant.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [MerchantRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [MerchantRegisterController::class, 'register']);

        Route::get('/login', fn () => redirect()->route('login'))->name('login');
        Route::post('/login', fn () => redirect()->route('login'));

        Route::get('/password/forgot', [MerchantForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [MerchantForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/password/reset/{token}', [MerchantResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [MerchantResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [MerchantLoginController::class, 'logout'])->name('logout');

        Route::middleware('role:merchant')->group(function () {
            Route::get('/onboarding', [MerchantOnboardingController::class, 'edit'])
                ->name('onboarding')
                ->middleware('merchant.status:draft');
            Route::post('/onboarding', [MerchantOnboardingController::class, 'update'])
                ->name('onboarding.store')
                ->middleware('merchant.status:draft');

            Route::get('/activation', [MerchantActivationController::class, 'show'])
                ->name('activation')
                ->middleware('merchant.status:accepted_pending_subscription');
            Route::post('/activation/confirm', [MerchantActivationController::class, 'confirm'])
                ->name('activation.confirm')
                ->middleware('merchant.status:accepted_pending_subscription');

            Route::get('/dashboard', [MerchantDashboardController::class, 'index'])
                ->name('dashboard');
        });
    });
});

/**
 * Admin authentication
 */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('role:admin');

        Route::middleware('role:admin')->group(function () {
            Route::get('/merchants', [AdminMerchantController::class, 'index'])->name('merchants.index');
            Route::get('/merchants/{merchant}', [AdminMerchantController::class, 'show'])->name('merchants.show');
            Route::post('/merchants/{merchant}/approve', [AdminMerchantController::class, 'approve'])->name('merchants.approve');
            Route::post('/merchants/{merchant}/reject', [AdminMerchantController::class, 'reject'])->name('merchants.reject');
            Route::post('/merchants/{merchant}/suspend', [AdminMerchantController::class, 'suspend'])->name('merchants.suspend');
            Route::post('/merchants/{merchant}/reactivate', [AdminMerchantController::class, 'reactivate'])->name('merchants.reactivate');
        });
    });
});

