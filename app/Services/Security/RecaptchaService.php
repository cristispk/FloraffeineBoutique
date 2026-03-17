<?php

namespace App\Services\Security;

use Illuminate\Http\Client\Factory as HttpFactory;

class RecaptchaService
{
    public function __construct(
        protected HttpFactory $http,
    ) {
    }

    public function isEnabled(): bool
    {
        return (bool) config('recaptcha.enabled', false);
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->isEnabled()) {
            return true;
        }

        // If enabled but keys are missing, treat as a hard failure.
        if (! config('recaptcha.secret_key') || ! config('recaptcha.site_key')) {
            return false;
        }

        if (! $token) {
            return false;
        }

        $response = $this->http->asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]
        );

        if (! $response->ok()) {
            return false;
        }

        $data = $response->json();

        return (bool) ($data['success'] ?? false);
    }
}

