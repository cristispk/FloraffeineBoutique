# Done — Task 005 Merchant Lifecycle Foundation

## What was implemented

Task 005 introduced the **merchant** domain as a first-class entity with a **persisted status machine** on `merchants.status`, **1:1** linkage to merchant users, **centralized transitions** in `MerchantStatusService`, **onboarding** (draft → pending review), **admin review** (approve / reject / suspend / reactivate), **activation** via merchant confirmation only (no billing UI), **`merchant.status` middleware**, and a **dashboard that switches UI by status** (Blade partials per state).

Stack: Laravel + Blade. User-facing copy on merchant flows is Romanian.

## Authoritative documentation

Product and technical behavior are documented in:

- **`docs/06-merchant-lifecycle.md`** — lifecycle states, services, flows, middleware, dashboard-by-status, and constraints.

## Quality gates status

- **Review:** Passed (`tasks/reviews/005-merchant-lifecycle-foundation.md`).
- **Runtime test:** Passed (`tasks/tests/005-merchant-lifecycle-foundation.md`).
- **Visual audit:** Passed (`tasks/visual-reviews/005-merchant-lifecycle-foundation.md`) — no must-fix UI issues; optional polish noted for admin labels/filters.

## Main implementation areas

- **Domain:** `App\Enums\MerchantStatus`, `App\Models\Merchant`, migration `create_merchants_table` with backfill for existing merchant-role users.
- **Services:** `MerchantStatusService` (all lifecycle transitions in DB transactions), `MerchantProfileService` (profile updates in `draft` only; `ensureMerchantRecordExists`).
- **Auth integration:** `AuthService::registerMerchant` creates paired `Merchant` with `draft`.
- **HTTP:** `EnsureMerchantStatus` middleware (`merchant.status` alias), merchant onboarding/activation/dashboard controllers, `Admin\MerchantController`.
- **Routes:** `routes/web.php` — merchant onboarding and activation gated by `merchant.status`; dashboard without it; admin merchant routes under `role:admin`.
- **Views:** Merchant onboarding, activation, dashboard + status partials; admin merchants index/show.

## Out of scope (unchanged)

Products, cart, checkout, orders, payouts, and real subscription billing are not part of this task.

## Optional follow-up

- Admin UI: Romanian labels for status values, richer filter affordances, pagination styling (see visual review optional items).
