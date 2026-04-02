# Test Report — Task 005 Merchant Lifecycle Foundation

## References

- Task: `tasks/005-merchant-lifecycle-foundation.md`
- Review: `tasks/reviews/005-merchant-lifecycle-foundation.md`
- Environment: Docker app + Nginx on `http://localhost:8080` (when HTTP tests were run)
- Date: 2026-04-02

---

## 1. Acceptance criteria (task §243–255)

| # | Criterion | Result | Evidence |
|---|-----------|--------|----------|
| 1 | DB: `merchants` schema, `user_id` unique, FKs | **Pass** | Migration `2026_04_02_000001_create_merchants_table.php` defines unique `user_id`, cascade delete, `reviewed_by` → `users` nullOnDelete, indexed `status`. |
| 2 | Every `users.role = merchant` has exactly one `merchants` row | **Pass** | Runtime check: `merchant_users == merchant_rows`, `merchant_users_without_row == 0`, `duplicate_user_id_groups == 0`. |
| 3 | Status only on `merchants.status`; transitions via `MerchantStatusService` + transactions | **Pass** | Code review + lifecycle script exercised `submitForReview`, `approve`, `reject`, `confirmActivation`, `suspend`, `reactivate`; each uses `DB::transaction` in service. No `users.merchant_status`. |
| 4 | `merchant.status` middleware registered and enforces allowed statuses | **Pass** | `bootstrap/app.php` alias `merchant.status`; HTTP: `pending_review` and `rejected` merchants receive **302** to `/merchant/dashboard` on `GET /merchant/onboarding` (draft-only route). |
| 5 | Onboarding (`draft`): save + submit → `pending_review` + `submitted_at` | **Pass** | Service script: after `updateDraftProfile` + `submitForReview`, `status === pending_review` and `submitted_at !== null`. |
| 6 | Admin approve → `accepted_pending_subscription`; reject with reason → `rejected` (terminal) | **Pass** | Service script: approve path; reject path sets `rejected`; `MerchantProfileService::updateDraftProfile` throws on rejected merchant. |
| 7 | Activation: confirm only → `active`, no payment UI | **Pass** | Service `confirmActivation` only; Blade `merchant/activation/show.blade.php` states billing is future—no card/checkout fields (static review + behavior). |
| 8 | Admin suspend / reactivate | **Pass** | Service script: `active` → `suspended` → `active` via `suspend` / `reactivate`. |
| 9 | Dashboard: correct partials/CTAs; missing merchant handling | **Partial note** | Partials mapped by `status` kebab-case; CTAs match task (draft → onboarding, etc.). **Literal task text** says redirect to onboarding if merchant missing; implementation uses `ensureMerchantRecordExists()` (provisions draft row). Invariant “one row per merchant user” still holds; see review. |
| 10 | New user-facing strings in Romanian | **Pass** | Spot check: onboarding title „Profil comerciant”, buttons „Salvează ciorna” / „Trimite spre verificare”, activation copy excludes payment; merchant dashboard titles Romanian. Admin list shows raw status **values** (e.g. `pending_review`)—technical, not user merchant area. |
| 11 | No product/cart/checkout/payout/billing/payment code added | **Pass** | Targeted search under `app/` for cart/checkout/product/payout/payment/billing in new feature scope: no new merchant-lifecycle feature code introducing those domains. |

---

## 2. Merchant table & 1:1 integrity

- **Counts (runtime):** `merchant_users = merchant_rows`; **0** users without merchant row; **0** duplicate `user_id` groups.
- **Registration path:** `AuthService::registerMerchant` creates paired `Merchant` (verified via lifecycle integration script).
- **Backfill:** Migration includes post-create backfill for existing merchant-role users without a row.

---

## 3. Service-layer lifecycle (integration script)

Automated script (Docker `php`) created disposable merchants and drove:

1. Register → `draft`
2. Profile + `submitForReview` → `pending_review` + `submitted_at`
3. `approve` → `accepted_pending_subscription` + `reviewed_at`
4. `confirmActivation` → `active` + `activated_at`
5. `suspend` → `suspended`
6. `reactivate` → `active`
7. Separate merchant: `reject` → `rejected`; confirmed `updateDraftProfile` **throws** (terminal / no edit)

**Result:** `ALL_SERVICE_TRANSITIONS_OK`

---

## 4. HTTP / middleware behavior

| Scenario | Expected | Result |
|----------|----------|--------|
| Merchant in **`draft`**, `GET /merchant/onboarding` | 200, onboarding UI (Romanian) | **Pass** — page contains „Profil comerciant” |
| Merchant in **`pending_review`**, `GET /merchant/onboarding` | Redirect away (not draft) | **Pass** — **302** `Location: …/merchant/dashboard` |
| Merchant in **`rejected`**, `GET /merchant/onboarding` | Cannot use onboarding (terminal) | **Pass** — **302** to dashboard |

Flash message on blocked access: middleware uses session `status` with Romanian text per implementation (not asserted byte-for-byte in this run).

---

## 5. Routes

- `php artisan route:list` confirms admin merchant resource routes and merchant `onboarding`, `onboarding.store`, `activation`, `activation.confirm`, `dashboard` under `merchant` prefix with expected middleware stacking for status-restricted routes.

---

## 6. Controllers (observable thin behavior)

- Merchant and admin controllers delegate to `MerchantProfileService` / `MerchantStatusService`; no direct `$merchant->status =` in controllers (aligned with review).

---

## 7. Rejected terminal (v1)

- Onboarding route blocked for `rejected` (HTTP).
- Profile update blocked in service for non-draft (`rejected`).
- Dashboard rejected partial has **no** link back to onboarding.

---

## 8. Out-of-scope regression

- No cart, checkout, product catalog, payout, or payment gateway flows introduced in verified paths.

---

## Must-fix issues

- **None** identified against task acceptance criteria and failure conditions.

---

## Optional improvements

1. **Acceptance criterion 9 wording:** If product owner insists on “redirect only” when merchant row is missing (no auto-provision on dashboard/middleware), align `DashboardController` / middleware with task text; current behavior preserves data invariant.
2. **Admin UI:** Display Romanian labels for merchant status instead of raw enum strings in lists/detail.
3. **Flash assertion:** Explicitly assert session flash text after middleware redirect in automated feature tests (future).

---

## Overall verdict

Task **005 merchant lifecycle foundation** behavior **satisfies** the listed acceptance criteria: schema and 1:1 integrity, centralized status transitions, middleware enforcement (verified with draft / pending_review / rejected HTTP checks), onboarding and admin-driven flows, confirmation-only activation, suspend/reactivate, dashboard composition by status, Romanian copy in merchant-facing flows, and no out-of-scope commerce features in scope of testing.
