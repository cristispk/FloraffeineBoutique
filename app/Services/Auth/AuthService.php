<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

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
        return $this->createUser($data, User::ROLE_MERCHANT);
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
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);
    }
}

