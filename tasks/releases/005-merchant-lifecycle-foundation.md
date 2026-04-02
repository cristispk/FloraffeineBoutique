# Release / Closure — Task 005 Merchant Lifecycle Foundation

## Commit message proposal

`feat(merchant): merchant lifecycle foundation — status machine, onboarding, admin review, activation`

## Short release summary

This release adds the **merchant (creator) lifecycle foundation**: a `merchants` table with **`merchants.status`** as the single source of truth, **1:1** `users` ↔ `merchants`, all status transitions through **`MerchantStatusService`** (DB transactions), **onboarding** for draft merchants, **admin review** (approve / reject / suspend / reactivate), **activation** by merchant confirmation only (no payment or billing UI), **`merchant.status`** middleware for route gating, and a **merchant dashboard** that renders **status-specific** Blade partials. User-facing merchant screens use **Romanian** copy.

## Workflow artifacts produced

- Planning: `tasks/planning/005-merchant-lifecycle-foundation.md`
- Architecture: `tasks/architecture/005-merchant-lifecycle-foundation.md`
- Code review: `tasks/reviews/005-merchant-lifecycle-foundation.md` (passed)
- Runtime test report: `tasks/tests/005-merchant-lifecycle-foundation.md` (passed)
- Visual review: `tasks/visual-reviews/005-merchant-lifecycle-foundation.md` (passed, no must-fix)
- Completion documentation: `tasks/done/005-merchant-lifecycle-foundation.md`
- Product documentation: `docs/06-merchant-lifecycle.md`

## Final closure status

- Task is completed and closed based on passing review, test, and visual-audit artifacts and completed documentation.
- Active task file removed from `tasks/005-merchant-lifecycle-foundation.md`.
- Workflow artifacts retained under `tasks/planning/`, `tasks/architecture/`, `tasks/reviews/`, `tasks/tests/`, `tasks/visual-reviews/`, and `tasks/done/`.

## Main implementation scope included in release

- **Schema & model:** `database/migrations/2026_04_02_000001_create_merchants_table.php`, `app/Models/Merchant.php`, `app/Enums/MerchantStatus.php`
- **Services:** `app/Services/Merchant/MerchantStatusService.php`, `app/Services/Merchant/MerchantProfileService.php`, `app/Services/Auth/AuthService.php` (merchant registration pairing)
- **Middleware:** `app/Http/Middleware/EnsureMerchantStatus.php`, alias in `bootstrap/app.php`
- **Controllers:** `app/Http/Controllers/Merchant/OnboardingController.php`, `ActivationController.php`, `DashboardController.php`, `app/Http/Controllers/Admin/MerchantController.php`
- **Requests:** `app/Http/Requests/Merchant/StoreMerchantOnboardingRequest.php`, `app/Http/Requests/Admin/RejectMerchantRequest.php`, `SuspendMerchantRequest.php`
- **Routes:** `routes/web.php`
- **Views:** `resources/views/merchant/onboarding/`, `merchant/activation/`, `merchant/dashboard/` (+ `partials/status-*.blade.php`), `resources/views/admin/merchants/`
- **Docs:** `docs/06-merchant-lifecycle.md`

## Non-blocking follow-up

- Optional polish on admin merchants list (Romanian status labels, filter UI, pagination styling) per visual review notes.
