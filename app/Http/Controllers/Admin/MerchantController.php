<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MerchantStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectMerchantRequest;
use App\Http\Requests\Admin\SuspendMerchantRequest;
use App\Models\Merchant;
use App\Services\Merchant\MerchantStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class MerchantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Merchant::query()->with('user')->orderByDesc('updated_at');

        if ($request->query('filter') !== 'all') {
            $query->where('status', MerchantStatus::PendingReview);
        }

        $merchants = $query->paginate(20)->withQueryString();

        return view('admin.merchants.index', [
            'merchants' => $merchants,
            'filter' => $request->query('filter', 'pending'),
        ]);
    }

    public function show(Merchant $merchant): View
    {
        $merchant->load('user', 'reviewer');

        return view('admin.merchants.show', [
            'merchant' => $merchant,
        ]);
    }

    public function approve(Merchant $merchant, Request $request, MerchantStatusService $statusService): RedirectResponse
    {
        try {
            $statusService->approve($merchant, $request->user());
        } catch (InvalidArgumentException $e) {
            return redirect()->route('admin.merchants.show', $merchant)
                ->with('status', 'Aprobarea nu a putut fi efectuată.');
        }

        return redirect()->route('admin.merchants.show', $merchant)
            ->with('status', 'Comerciantul a fost aprobat.');
    }

    public function reject(
        RejectMerchantRequest $request,
        Merchant $merchant,
        MerchantStatusService $statusService
    ): RedirectResponse {
        try {
            $statusService->reject($merchant, $request->user(), $request->validated('rejection_reason'));
        } catch (InvalidArgumentException $e) {
            return redirect()->route('admin.merchants.show', $merchant)
                ->with('status', 'Respingerea nu a putut fi efectuată.');
        }

        return redirect()->route('admin.merchants.show', $merchant)
            ->with('status', 'Comerciantul a fost respins.');
    }

    public function suspend(
        SuspendMerchantRequest $request,
        Merchant $merchant,
        MerchantStatusService $statusService
    ): RedirectResponse {
        try {
            $statusService->suspend($merchant, $request->user(), $request->validated('suspension_reason'));
        } catch (InvalidArgumentException $e) {
            return redirect()->route('admin.merchants.show', $merchant)
                ->with('status', 'Suspendarea nu a putut fi efectuată.');
        }

        return redirect()->route('admin.merchants.show', $merchant)
            ->with('status', 'Comerciantul a fost suspendat.');
    }

    public function reactivate(
        Request $request,
        Merchant $merchant,
        MerchantStatusService $statusService
    ): RedirectResponse {
        try {
            $statusService->reactivate($merchant, $request->user());
        } catch (InvalidArgumentException $e) {
            return redirect()->route('admin.merchants.show', $merchant)
                ->with('status', 'Reactivarea nu a putut fi efectuată.');
        }

        return redirect()->route('admin.merchants.show', $merchant)
            ->with('status', 'Comerciantul a fost reactivat.');
    }
}
