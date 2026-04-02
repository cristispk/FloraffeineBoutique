# Review — Task 005 Merchant Lifecycle Foundation

## Scope reviewed

- Task: `tasks/005-merchant-lifecycle-foundation.md`
- Inspected implementation: migrations, `Merchant` / `User`, `MerchantStatus` enum, `MerchantStatusService`, `MerchantProfileService`, `AuthService`, middleware, routes, merchant/admin controllers, form requests, and Blade views listed in the task.

---

## 1. Structural / architectural compliance

### Task fidelity and hard constraints

| Requirement | Verdict |
|---------------|---------|
| 1:1 User ↔ Merchant (`merchants.user_id` unique) | **Met.** Migration defines `foreignId('user_id')->unique()` with `cascadeOnDelete()`. |
| `merchants.status` single source of truth (no status on `users`) | **Met.** No `users.merchant_status` or similar; lifecycle reads/writes go through `merchants`. |
| Initial status `draft` for new rows | **Met.** `AuthService::registerMerchant` creates `Merchant` with `MerchantStatus::Draft`; backfill inserts `'draft'`. |
| All **transitions** via `MerchantStatusService` only | **Met.** Status mutations on existing lifecycle (draft → …) occur only in `MerchantStatusService` (assign + `save()` inside transactions). |
| Controllers do not assign `$merchant->status` | **Met.** Grep shows no controller writes status; only `MerchantStatusService`, initial `Merchant::create` in `AuthService`, `firstOrCreate` defaults in `MerchantProfileService`, and migration backfill (initial row creation, not a transition). |
| `rejected` terminal in v1 | **Met.** Onboarding routes use `merchant.status:draft` only; rejected merchants cannot reach onboarding. Rejected dashboard partial has **no** link to onboarding. |
| Activation confirmation-only, no payment UI | **Met.** `resources/views/merchant/activation/show.blade.php` states billing is future; single POST to `confirm`; `ActivationController@confirm` only calls `MerchantStatusService::confirmActivation`. |
| Thin controllers | **Met.** Controllers delegate to services; admin actions catch `InvalidArgumentException` and redirect with flash. |
| Routes / middleware names and stacking | **Met.** `routes/web.php` matches the task table: `merchant.onboarding`, `merchant.onboarding.store`, `merchant.activation`, `merchant.activation.confirm`, `merchant.dashboard` without `merchant.status`; admin routes and names match. Order: `auth` → `role:merchant` → `merchant.status:…` where applicable. |
| Laravel + Blade only; no out-of-scope domains | **Met.** No product/cart/checkout/payout/payment code introduced in reviewed files. |

### Deviations / clarifications (not automatic failures)

- **Task text vs implementation (dashboard / missing merchant):** The task asks for `load('merchant')` and redirect to `merchant.onboarding` if missing. Implementation uses `MerchantProfileService::ensureMerchantRecordExists()` on the dashboard, which **creates** a draft row if none exists (same helper used in `EnsureMerchantStatus`). This still satisfies the global invariant “exactly one merchant row per merchant user” and matches the middleware note “(or create flow)”. **Behavior is coherent;** only the literal “redirect without create” path is not implemented on dashboard.
- **Admin list query parameter:** Task controller section mentions optional query `status=all`; implementation and views use `filter=all`. Functionally equivalent; naming differs from the task wording.

---

## 2. Status transition integrity

- **`MerchantStatusService`** implements the allowed matrix: `submitForReview`, `approve`, `reject`, `confirmActivation`, `suspend`, `reactivate` with guards and `DB::transaction` per method.
- **Invalid transitions** throw `InvalidArgumentException` (not silent).
- **`approve`** clears `rejection_reason` when moving to `accepted_pending_subscription` (reasonable cleanup).
- **`confirmActivation`** sets `activated_at` only when null (first activation).
- **`reactivate`** clears suspension fields; does not overwrite `activated_at` (consistent with “first activation” semantics).

No controller or `MerchantProfileService` method performs lifecycle transitions—only profile field updates in draft (no status change there).

---

## 3. Registration, backfill, and 1:1 guarantee

- **Registration:** `AuthService::registerMerchant` wraps user + `Merchant::create` in `DB::transaction` with `status = draft` and `business_name` from registration name (fallback `—`).
- **Backfill:** Same migration file after `Schema::create` calls `backfillMerchantsForExistingMerchantUsers()`, inserting one row per existing `users.role = merchant` without a merchant, `status = draft`, `business_name` from `users.name` or `—`.
- **Safety:** Backfill is idempotent for “missing row only” via `whereNotExists` subquery; no duplicate `user_id` thanks to application logic and unique index.

---

## 4. Middleware (`EnsureMerchantStatus`)

- Registered as `merchant.status` in `bootstrap/app.php`.
- Resolves merchant via `ensureMerchantRecordExists` (provisions draft row if missing—aligns with “create flow” option in task).
- Compares `$merchant->status` to allowed enum list from route parameters (supports comma-separated values in one argument).
- On mismatch: redirects to `route('merchant.dashboard')` with flash message **„Acțiunea nu este disponibilă pentru starea contului tău.”** — matches the task example.
- Redirect to `login` if not merchant when middleware runs (defensive; routes should already be `role:merchant`).

---

## 5. Onboarding, admin review, activation, dashboard-by-status

### Onboarding

- `OnboardingController` uses `StoreMerchantOnboardingRequest`, `MerchantProfileService::updateDraftProfile`, and `MerchantStatusService::submitForReview` for `action === 'submit'`.
- View `merchant/onboarding/edit.blade.php` uses `action` `save` / `submit` on submit buttons and `x-ui` inputs; Romanian copy.

### Admin

- `Admin\MerchantController` delegates to `MerchantStatusService`; uses `RejectMerchantRequest` / `SuspendMerchantRequest` where required.
- **`admin/merchants/show.blade.php`:** Approve/reject only for `PendingReview`; suspend only for `Active`; reactivate only for `Suspended`; no actions for rejected (read-only). **Matches task UI rules.**

### Activation

- Confirmation-only; no billing fields.

### Dashboard partials

- `DashboardController` builds `status-{kebab}` from enum `value` via `str_replace('_', '-', …)` — matches mapping for `draft`, `pending_review`, `accepted_pending_subscription`, `active`, `rejected`, `suspended`.
- Partials follow CTA rules (draft → onboarding link; accepted_pending_subscription → activation; rejected without onboarding link; etc.).

---

## 6. Code quality notes

- Admin index/show display raw `$merchant->status->value` (English technical values). Acceptable for internal admin; user-facing merchant area is Romanian.

---

## Must-fix issues

- **None** for security, lifecycle integrity, or task failure conditions as written.

---

## Optional improvements

1. **Query parameter naming:** Support `?status=all` as alias for `?filter=all` (or rename to `status` everywhere) to match the task document wording exactly.
2. **DashboardController vs task literal:** If strict adherence to “redirect to onboarding when merchant missing” is required (without auto-create on dashboard), rely on registration + backfill guaranteeing a row and use `load('merchant')` + `abort(404)` or redirect-only—today’s `ensureMerchantRecordExists` is defensive and consistent with middleware.
3. **Admin UX:** Display human-readable Romanian labels for merchant status instead of raw enum strings in `admin/merchants/index.blade.php` and show page.
4. **Optional `ApproveMerchantRequest`:** Empty form request class for symmetry with reject/suspend (task listed as optional).
5. **Inline exception messages** in `MerchantStatusService` are English; only user-facing Blade/controller flash messages are Romanian—acceptable for internal exceptions, optional to localize if exceptions ever surface to users raw.

---

## Verdict

Implementation **matches the task’s intent and hard constraints**: status machine centralized in `MerchantStatusService`, single lifecycle column on `merchants`, 1:1 relationship, registration + backfill coverage, terminal `rejected`, confirmation-only activation without payment UI, correct middleware and route wiring, thin controllers, and dashboard partial strategy. Minor **documentation/naming** differences (`filter` vs `status`, dashboard missing-row behavior) are noted as optional alignment only.
