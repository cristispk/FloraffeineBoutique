<?php

namespace App\Http\Requests\Merchant\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MerchantLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}

