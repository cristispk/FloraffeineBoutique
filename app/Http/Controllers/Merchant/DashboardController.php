<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Services\Merchant\MerchantProfileService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, MerchantProfileService $profileService): View
    {
        $merchant = $profileService->ensureMerchantRecordExists($request->user());

        $partialSuffix = str_replace('_', '-', $merchant->status->value);

        return view('merchant.dashboard.index', [
            'merchant' => $merchant,
            'statusPartial' => 'merchant.dashboard.partials.status-'.$partialSuffix,
        ]);
    }
}

