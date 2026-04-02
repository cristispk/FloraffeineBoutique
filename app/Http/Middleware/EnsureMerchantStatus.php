<?php

namespace App\Http\Middleware;

use App\Enums\MerchantStatus;
use App\Services\Merchant\MerchantProfileService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMerchantStatus
{
    public function __construct(
        protected MerchantProfileService $merchantProfileService,
    ) {
    }

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$statusParameters  e.g. "draft" or "draft,accepted_pending_subscription"
     */
    public function handle(Request $request, Closure $next, string ...$statusParameters): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isMerchant()) {
            return redirect()->route('login');
        }

        $merchant = $this->merchantProfileService->ensureMerchantRecordExists($user);

        $allowedStrings = collect($statusParameters)
            ->flatMap(fn (string $chunk) => explode(',', $chunk))
            ->map(fn (string $s) => trim($s))
            ->filter()
            ->values();

        $allowedStatuses = $allowedStrings
            ->map(fn (string $v) => MerchantStatus::tryFrom($v))
            ->filter();

        foreach ($allowedStatuses as $allowed) {
            if ($merchant->status === $allowed) {
                return $next($request);
            }
        }

        return redirect()
            ->route('merchant.dashboard')
            ->with('status', 'Acțiunea nu este disponibilă pentru starea contului tău.');
    }
}
