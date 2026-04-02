<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\StoreMerchantOnboardingRequest;
use App\Services\Merchant\MerchantProfileService;
use App\Services\Merchant\MerchantStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class OnboardingController extends Controller
{
    public function edit(Request $request, MerchantProfileService $profileService): View
    {
        $merchant = $profileService->ensureMerchantRecordExists($request->user());

        return view('merchant.onboarding.edit', [
            'merchant' => $merchant,
        ]);
    }

    public function update(
        StoreMerchantOnboardingRequest $request,
        MerchantProfileService $profileService,
        MerchantStatusService $statusService
    ): RedirectResponse {
        $merchant = $profileService->ensureMerchantRecordExists($request->user());

        $data = $request->safe()->only(['business_name', 'description', 'phone', 'city', 'website']);
        $profileService->updateDraftProfile($merchant, $data);
        $merchant->refresh();

        if ($request->input('action') === 'submit') {
            try {
                $statusService->submitForReview($merchant);
            } catch (InvalidArgumentException $e) {
                return back()->withErrors(['submit' => $e->getMessage()])->withInput();
            }

            return redirect()->route('merchant.dashboard')
                ->with('status', 'Cererea ta a fost trimisă spre verificare.');
        }

        return redirect()->route('merchant.onboarding')
            ->with('status', 'Modificările au fost salvate.');
    }
}
