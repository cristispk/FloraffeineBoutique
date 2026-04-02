<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Services\Merchant\MerchantProfileService;
use App\Services\Merchant\MerchantStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class ActivationController extends Controller
{
    public function show(Request $request, MerchantProfileService $profileService): View
    {
        $merchant = $profileService->ensureMerchantRecordExists($request->user());

        return view('merchant.activation.show', [
            'merchant' => $merchant,
        ]);
    }

    public function confirm(
        Request $request,
        MerchantProfileService $profileService,
        MerchantStatusService $statusService
    ): RedirectResponse {
        $merchant = $profileService->ensureMerchantRecordExists($request->user());

        try {
            $statusService->confirmActivation($merchant);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('merchant.dashboard')
                ->with('status', 'Activarea nu a putut fi confirmată în acest moment.');
        }

        return redirect()->route('merchant.dashboard')
            ->with('status', 'Contul creator a fost activat. Planul Creator (facturare) va fi disponibil într-o versiune viitoare.');
    }
}
