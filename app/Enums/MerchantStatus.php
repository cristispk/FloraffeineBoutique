<?php

namespace App\Enums;

enum MerchantStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case AcceptedPendingSubscription = 'accepted_pending_subscription';
    case Active = 'active';
    case Rejected = 'rejected';
    case Suspended = 'suspended';
}
