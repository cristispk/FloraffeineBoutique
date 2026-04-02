<?php

namespace App\Services\Merchant;

use App\Enums\MerchantStatus;
use App\Models\Merchant;
use App\Models\User;
use InvalidArgumentException;

class MerchantProfileService
{
    /**
     * Ensure a merchant row exists for this user (draft). Used for edge cases and middleware.
     */
    public function ensureMerchantRecordExists(User $user): Merchant
    {
        if (! $user->isMerchant()) {
            throw new InvalidArgumentException('User is not a merchant.');
        }

        /** @var Merchant $merchant */
        $merchant = Merchant::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status' => MerchantStatus::Draft,
                'business_name' => $user->name !== '' && $user->name !== null ? $user->name : '—',
            ]
        );

        return $merchant;
    }

    /**
     * Update profile fields only while status is draft.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateDraftProfile(Merchant $merchant, array $data): void
    {
        if ($merchant->status !== MerchantStatus::Draft) {
            throw new InvalidArgumentException('Profile can only be edited in draft state.');
        }

        $merchant->fill([
            'business_name' => $data['business_name'] ?? $merchant->business_name,
            'description' => $data['description'] ?? $merchant->description,
            'phone' => $data['phone'] ?? $merchant->phone,
            'city' => $data['city'] ?? $merchant->city,
            'website' => $data['website'] ?? $merchant->website,
        ]);

        $merchant->save();
    }
}
