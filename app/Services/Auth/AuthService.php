<?php

namespace App\Services\Auth;

use App\Enums\MerchantStatus;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function __construct(
        protected AuthManager $auth,
    ) {
    }

    public function registerUser(array $data): User
    {
        return $this->createUser($data, User::ROLE_USER);
    }

    public function registerMerchant(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createUser($data, User::ROLE_MERCHANT);

            $businessName = ($data['name'] ?? '') !== '' ? $data['name'] : '—';

            Merchant::create([
                'user_id' => $user->id,
                'status' => MerchantStatus::Draft,
                'business_name' => $businessName,
            ]);

            return $user;
        });
    }

    /**
     * Attempt to authenticate a user for a specific role.
     *
     * Wrong-role attempts are treated as invalid credentials.
     */
    public function attemptLoginForRole(string $role, array $credentials): bool
    {
        $guard = $this->auth->guard('web');

        if (! $guard->attempt([
            'email' => $credentials['email'] ?? null,
            'password' => $credentials['password'] ?? null,
        ], $credentials['remember'] ?? false)) {
            return false;
        }

        /** @var \App\Models\User|null $user */
        $user = $guard->user();

        if (! $user || $user->role !== $role) {
            $guard->logout();

            return false;
        }

        return true;
    }

    /**
     * Attempt to authenticate a non-admin user (user or merchant).
     *
     * Admin/wrong-role attempts are treated as invalid credentials.
     */
    public function attemptLoginForNonAdmin(array $credentials): ?User
    {
        $guard = $this->auth->guard('web');

        if (! $guard->attempt([
            'email' => $credentials['email'] ?? null,
            'password' => $credentials['password'] ?? null,
        ], $credentials['remember'] ?? false)) {
            return null;
        }

        /** @var User|null $user */
        $user = $guard->user();

        if (! $user || ! in_array($user->role, [User::ROLE_USER, User::ROLE_MERCHANT], true)) {
            $guard->logout();

            return null;
        }

        return $user;
    }

    public function logout(): void
    {
        $guard = $this->auth->guard('web');

        if ($guard->check()) {
            $guard->logout();
        }
    }

    protected function createUser(array $data, string $role): User
    {
        return User::create([
            'name' => $data['name'] ?? '',
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $role,
        ]);
    }
}

