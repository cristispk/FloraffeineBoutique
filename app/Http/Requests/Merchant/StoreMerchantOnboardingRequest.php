<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMerchantOnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMerchant() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $action = $this->input('action');

        return [
            'action' => ['required', Rule::in(['save', 'submit'])],
            'business_name' => [
                $action === 'submit' ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'business_name' => 'numele afacerii',
            'description' => 'descrierea',
            'phone' => 'telefonul',
            'city' => 'orașul',
            'website' => 'site-ul web',
            'action' => 'acțiunea',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'business_name.required' => 'Numele afacerii este obligatoriu înainte de trimiterea cererii.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $website = $this->input('website');
        if ($website === '') {
            $this->merge(['website' => null]);
        }
    }
}
