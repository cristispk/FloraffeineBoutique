<?php

namespace App\Http\Requests\Merchant\Auth;

use App\Services\Security\RecaptchaService;
use Illuminate\Foundation\Http\FormRequest;

class MerchantRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            /** @var \Illuminate\Http\Request $request */
            $request = $this;

            // When reCAPTCHA is disabled, do not add any g-recaptcha-response validation errors.
            if (! config('recaptcha.enabled')) {
                return;
            }

            /** @var RecaptchaService $recaptcha */
            $recaptcha = app(RecaptchaService::class);

            $token = $request->input('g-recaptcha-response');

            if (! $token) {
                $validator->errors()->add(
                    'g-recaptcha-response',
                    'Te rugăm să confirmi că nu ești robot.'
                );

                return;
            }

            if (! $recaptcha->verify($token, $request->ip())) {
                $validator->errors()->add(
                    'g-recaptcha-response',
                    'Verificarea reCAPTCHA a eșuat. Te rugăm să încerci din nou.'
                );
            }
        });
    }
}

