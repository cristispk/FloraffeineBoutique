<?php

namespace App\Services\Merchant;

use App\Enums\MerchantStatus;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class MerchantStatusService
{
    public function submitForReview(Merchant $merchant): void
    {
        if ($merchant->status !== MerchantStatus::Draft) {
            throw new InvalidArgumentException('Invalid transition: only draft can be submitted for review.');
        }

        if (trim($merchant->business_name) === '' || trim($merchant->business_name) === '—') {
            throw new InvalidArgumentException('Business name is required before submission.');
        }

        DB::transaction(function () use ($merchant): void {
            $merchant->status = MerchantStatus::PendingReview;
            $merchant->submitted_at = now();
            $merchant->save();
        });
    }

    public function approve(Merchant $merchant, User $admin): void
    {
        if (! $admin->isAdmin()) {
            throw new InvalidArgumentException('Only an administrator can approve.');
        }

        if ($merchant->status !== MerchantStatus::PendingReview) {
            throw new InvalidArgumentException('Invalid transition: merchant is not pending review.');
        }

        DB::transaction(function () use ($merchant, $admin): void {
            $merchant->status = MerchantStatus::AcceptedPendingSubscription;
            $merchant->reviewed_at = now();
            $merchant->reviewed_by = $admin->id;
            $merchant->rejection_reason = null;
            $merchant->save();
        });
    }

    /**
     * @param  string  $reason  Rejection reason (required for audit)
     */
    public function reject(Merchant $merchant, User $admin, string $reason): void
    {
        if (! $admin->isAdmin()) {
            throw new InvalidArgumentException('Only an administrator can reject.');
        }

        if ($merchant->status !== MerchantStatus::PendingReview) {
            throw new InvalidArgumentException('Invalid transition: merchant is not pending review.');
        }

        $trimmed = trim($reason);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Rejection reason is required.');
        }

        DB::transaction(function () use ($merchant, $admin, $trimmed): void {
            $merchant->status = MerchantStatus::Rejected;
            $merchant->reviewed_at = now();
            $merchant->reviewed_by = $admin->id;
            $merchant->rejection_reason = $trimmed;
            $merchant->save();
        });
    }

    public function confirmActivation(Merchant $merchant): void
    {
        if ($merchant->status !== MerchantStatus::AcceptedPendingSubscription) {
            throw new InvalidArgumentException('Invalid transition: merchant is not awaiting activation confirmation.');
        }

        DB::transaction(function () use ($merchant): void {
            $merchant->status = MerchantStatus::Active;
            if ($merchant->activated_at === null) {
                $merchant->activated_at = now();
            }
            $merchant->save();
        });
    }

    /**
     * @param  string  $reason  Suspension reason (required)
     */
    public function suspend(Merchant $merchant, User $admin, string $reason): void
    {
        if (! $admin->isAdmin()) {
            throw new InvalidArgumentException('Only an administrator can suspend.');
        }

        if ($merchant->status !== MerchantStatus::Active) {
            throw new InvalidArgumentException('Invalid transition: only active merchants can be suspended.');
        }

        $trimmed = trim($reason);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Suspension reason is required.');
        }

        DB::transaction(function () use ($merchant, $admin, $trimmed): void {
            $merchant->status = MerchantStatus::Suspended;
            $merchant->suspended_at = now();
            $merchant->suspension_reason = $trimmed;
            $merchant->save();
        });
    }

    public function reactivate(Merchant $merchant, User $admin): void
    {
        if (! $admin->isAdmin()) {
            throw new InvalidArgumentException('Only an administrator can reactivate.');
        }

        if ($merchant->status !== MerchantStatus::Suspended) {
            throw new InvalidArgumentException('Invalid transition: merchant is not suspended.');
        }

        DB::transaction(function () use ($merchant): void {
            $merchant->status = MerchantStatus::Active;
            $merchant->suspended_at = null;
            $merchant->suspension_reason = null;
            $merchant->save();
        });
    }
}
