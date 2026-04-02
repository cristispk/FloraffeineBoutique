# Architecture — Task 005: Merchant Lifecycle Foundation

## 1. Scope and alignment

This document implements the intent of `tasks/planning/005-merchant-lifecycle-foundation.md` as **implementation-ready** technical architecture.

**Stack:** Laravel + Blade only. Existing auth (shared login, `role:merchant`, base UI) remains; this task adds **domain data**, **status machine**, **services**, **middleware**, and **Blade** for onboarding, admin review, and status-aware merchant UX.

---

## 2. Core architectural decisions (explicit)

| Decision | Choice |
|----------|--------|
| User ↔ Merchant cardinality | **Strict 1:1**: one `merchants` row per merchant user; `merchants.user_id` is **unique**. |
| Initial status after registration | **`draft`**: merchant record is created at registration time (see §3.3). |
| Single source of truth for lifecycle | **`merchants.status` only**. No `users.merchant_status` or duplicated status columns in v1. |
| Where status changes | **Only** via `MerchantStatusService` (see §5). Controllers and other classes **must not** call `$merchant->update(['status' => ...])` except inside that service. |
| `accepted_pending_subscription` before billing | Merchant completes a **non-payment “activation confirmation”** that transitions to `active` (see §5.3). **No** card fields, **no** fake checkout, **no** payment provider. UI copy (Romanian) states that Creator Plan / billing will be integrated later. |
| `rejected` in v1 | **Terminal**: no self-service re-onboarding in this task; rejected merchants see explanation only. Future task may add re-application. |
| Scattered status logic | **Forbidden** outside `MerchantStatusService` + `MerchantStatus` enum (allowed comparisons for display/middleware lists only). |

---

## 3. Database structure — `merchants`

### 3.1 Table: `merchants`

| Column | Type | Notes |
|--------|------|--------|
| `id` | bigint PK | |
| `user_id` | FK → `users.id`, **unique**, `cascade` on delete | Enforces 1:1 |
| `status` | string(50) | Indexed; values **must** match `App\Enums\MerchantStatus` backed values |
| `business_name` | string(255) | Studio / brand display name (required for submit to review) |
| `description` | text, nullable | Short business description |
| `phone` | string(50), nullable | |
| `city` | string(120), nullable | Minimal location; expand in later tasks if source-of-truth requires full address |
| `website` | string(255), nullable | URL optional |
| `submitted_at` | timestamp, nullable | Set when transitioning `draft` → `pending_review` |
| `reviewed_at` | timestamp, nullable | Set when admin leaves `pending_review` (approve or reject) |
| `reviewed_by` | FK → `users.id`, nullable, `nullOnDelete` | Admin user who approved/rejected |
| `rejection_reason` | text, nullable | Required when status becomes `rejected` |
| `suspended_at` | timestamp, nullable | |
| `suspension_reason` | text, nullable | Required when admin suspends |
| `activated_at` | timestamp, nullable | Set when entering `active` |
| `created_at`, `updated_at` | timestamps | |

**Indexes:** `user_id` (unique), `status`, composite `(status, updated_at)` optional for admin lists.

**Rationale:** Field set is a **minimal foundation** aligned with onboarding + admin review. Additional legal/tax columns from `docs/source-of-truth/` are added in a follow-up if required by compliance review—not blocking the state machine.

### 3.2 Status storage implementation

- **DB:** `string` column (not MySQL ENUM) for simpler migrations and Laravel enum mapping.
- **App:** `App\Enums\MerchantStatus` backed enum (string values identical to DB):
  - `draft`
  - `pending_review`
  - `accepted_pending_subscription`
  - `active`
  - `rejected`
  - `suspended`

Cast on `Merchant` model: `'status' => MerchantStatus::class`.

---

## 4. User ↔ Merchant relationship

### 4.1 Rules

- Only users with `users.role = merchant` may have a `merchants` row.
- Relationship: `User` **hasOne** `Merchant`; `Merchant` **belongsTo** `User`.
- Enforcement:
  - **Registration path** (`MerchantRegisterController` + `AuthService::registerMerchant`): after user creation, create `Merchant` with `status = draft` and default `business_name` from registration `name` (or empty string if not allowed—prefer copy from `name` for display consistency).
  - **Backfill migration** (or dedicated artisan command run once): for every existing `users.role = merchant` without `merchants`, insert row with `status = draft` and `business_name = users.name`.

### 4.2 Model API

- `User::merchant(): HasOne<Merchant>`
- `Merchant::user(): BelongsTo<User>`
- `User::isMerchant()` unchanged; add optional `User::merchantProfile(): ?Merchant` usage in controllers.

**No** status field on `User`.

---

## 5. Merchant status machine

### 5.1 Allowed transitions (v1)

```
draft                          → pending_review
pending_review                 → accepted_pending_subscription | rejected
accepted_pending_subscription  → active
active                         → suspended
suspended                      → active
rejected                       → (none - terminal)
```

**Not allowed in v1:**

- Any transition into `draft` after submission (no admin “send back to draft” unless added later).
- `rejected` → anything.
- Skipping states (e.g. `draft` → `active`).

### 5.2 Transition logic ownership

**Single class:** `App\Services\Merchant\MerchantStatusService`

Public methods (names illustrative; implementer matches exactly):

| Method | From → To | Actor |
|--------|-----------|--------|
| `submitForReview(Merchant $m): void` | `draft` → `pending_review` | Merchant (onboarding complete) |
| `approve(Merchant $m, User $admin): void` | `pending_review` → `accepted_pending_subscription` | Admin |
| `reject(Merchant $m, User $admin, string $reason): void` | `pending_review` → `rejected` | Admin |
| `confirmActivation(Merchant $m): void` | `accepted_pending_subscription` → `active` | Merchant (no payment) |
| `suspend(Merchant $m, User $admin, string $reason): void` | `active` → `suspended` | Admin |
| `reactivate(Merchant $m, User $admin): void` | `suspended` → `active` | Admin |

**Internal helpers:**

- `assertTransition(Merchant $m, MerchantStatus $to): void` — throws domain exception or validation exception if invalid.
- All writes to `merchants.status` and audit fields happen **inside this service** in a **DB transaction** per transition.

**Forbidden:** Controllers calling `$merchant->status = ...` or `update([...])` for lifecycle changes.

### 5.3 `accepted_pending_subscription` → `active` without billing

- **Route:** e.g. `GET /merchant/activation`, `POST /merchant/activation/confirm` (names finalized in task file).
- **Behavior:** `ActivationController@confirm` calls `MerchantStatusService::confirmActivation($merchant)` only if current status is `accepted_pending_subscription`.
- **UX:** Page explains (Romanian) that the merchant confirms readiness to operate; Creator Plan / billing will be available in a future release. **No** payment UI, **no** “pay now” button, **no** external redirect.

---

## 6. Onboarding architecture

### 6.1 Responsibility split

| Layer | Responsibility |
|--------|----------------|
| `MerchantProfileService` | Load/update **profile fields** on `Merchant` while `status === draft` (validate business rules: required fields before submit). |
| `MerchantStatusService` | `submitForReview()` only when profile satisfies rules; then sets `submitted_at`. |
| `OnboardingController` | HTTP: show form, validate `FormRequest`, delegate to services; **no** status assignment beyond service calls. |

### 6.2 Routes (merchant prefix)

- `GET /merchant/onboarding` — form (middleware: `auth`, `role:merchant`, `merchant.status:draft`).
- `POST /merchant/onboarding` — save + optional “submit for review” (single form with submit button or two actions; architecture prefers **one POST** with `action=submit` vs `save` via hidden field or separate POST to `submit` route—implementer chooses; both must call services only).

### 6.3 Requests

- `App\Http\Requests\Merchant\StoreMerchantOnboardingRequest` — Romanian validation messages; rules on `business_name`, `description`, etc.

### 6.4 Views

- `resources/views/merchant/onboarding/edit.blade.php` (or `show.blade.php`) using existing layout and `x-ui.*` components.

---

## 7. Admin review architecture

### 7.1 Controllers

- `App\Http\Controllers\Admin\MerchantController` (singular resource name; RESTful naming):
  - `index` — list merchants (default filter `pending_review`; optional filter all).
  - `show` — read-only merchant + user email + status + timestamps.
  - `approve` — POST → `MerchantStatusService::approve`.
  - `reject` — POST with reason → `MerchantStatusService::reject`.
  - `suspend` — POST (from `active`) → `MerchantStatusService::suspend`.
  - `reactivate` — POST (from `suspended`) → `MerchantStatusService::reactivate`.

**Alternative:** Split `approve`/`reject` into `Admin\MerchantReviewController` if files grow; task-writer picks one namespace; **no** duplicate status logic.

### 7.2 Requests

- `App\Http\Requests\Admin\RejectMerchantRequest` — `rejection_reason` required, string, max length.
- `App\Http\Requests\Admin\SuspendMerchantRequest` — `suspension_reason` required.
- Approve may use inline validation or empty `ApproveMerchantRequest` for consistency.

### 7.3 Views

- `resources/views/admin/merchants/index.blade.php`
- `resources/views/admin/merchants/show.blade.php`
- Extend existing admin layout pattern (same as other admin auth pages).

### 7.4 Authorization

- Only `role:admin` + `auth`. Admin identity passed into `MerchantStatusService::*` as `User $admin` for `reviewed_by` / audit.

---

## 8. Merchant status-based access control

### 8.1 Middleware

- **Class:** `App\Http\Middleware\EnsureMerchantStatus`
- **Alias:** `merchant.status` (register in `bootstrap/app.php` next to `role`).
- **Signature:** `merchant.status:draft` or `merchant.status:draft,accepted_pending_subscription` (comma-separated **allowed** statuses).
- **Behavior:**
  1. `auth` must run first (route order).
  2. `role:merchant` must run before or after auth; typical order: `auth` → `role:merchant` → `merchant.status:...`.
  3. Resolve `Merchant` for `$request->user()`. If missing → redirect to `merchant.onboarding` or safe error page (implementer: redirect to onboarding create).
  4. If `$merchant->status` not in allowed list → **redirect** to `route('merchant.dashboard')` with optional flash (Romanian) “acțiunea nu este disponibilă pentru starea contului tău.”
  5. Never expose role/status details to guests.

### 8.2 Route grouping (recommended)

| Group | Middleware | Purpose |
|-------|------------|---------|
| Onboarding | `merchant.status:draft` | Edit profile + submit |
| Activation | `merchant.status:accepted_pending_subscription` | Confirm activation page |
| Dashboard | **no** `merchant.status` (all statuses see dashboard shell) | Content varies by status inside Blade |
| Future `active`-only routes | `merchant.status:active` | Products etc. (not in this task) |

### 8.3 Policies (optional v1)

- Not mandatory if middleware + service cover all routes; add `MerchantPolicy` later if admin/merchant share controllers. **v1:** middleware + service sufficient.

---

## 9. Dashboard-by-status rendering architecture

### 9.1 Controller

- `App\Http\Controllers\Merchant\DashboardController@index`:
  - Eager load: `$request->user()->load('merchant')`.
  - If no merchant row: redirect to `route('merchant.onboarding')` or show 500 in dev — **prefer redirect** to onboarding if route exists.
  - Pass `Merchant` (or DTO) to view.

### 9.2 Views

- **Single entry:** `resources/views/merchant/dashboard/index.blade.php`
- **Composition:** `@include` partials by status to avoid one huge file:
  - `resources/views/merchant/dashboard/partials/status-draft.blade.php`
  - `.../status-pending_review.blade.php`
  - `.../status-accepted_pending_subscription.blade.php`
  - `.../status-active.blade.php`
  - `.../status-rejected.blade.php`
  - `.../status-suspended.blade.php`

Naming: **kebab-case** filenames matching enum value with underscore → kebab (`pending_review` → `status-pending-review.blade.php` or keep `status-pending_review` — **convention:** use **kebab-case** for file names: `status-pending-review.blade.php`, map in controller with `str_replace('_', '-', $status->value)`.

### 9.3 CTA rules (UI only)

- **draft:** Link to onboarding.
- **pending_review:** Informational only.
- **accepted_pending_subscription:** Link to `/merchant/activation`.
- **active:** Welcome + placeholder for future modules (no products).
- **rejected / suspended:** Static explanation + support (no operational CTAs).

---

## 10. Naming conventions

| Artifact | Convention | Example |
|----------|--------------|---------|
| Model | Singular PascalCase | `Merchant` |
| Table | snake_case plural | `merchants` |
| Enum | PascalCase + `Status` suffix | `MerchantStatus` |
| Service | `{Domain}{Action}Service` or `{Domain}Service` | `MerchantStatusService`, `MerchantProfileService` |
| Middleware | PascalCase verb phrase | `EnsureMerchantStatus` |
| Form requests | `{Action}{Entity}Request` | `StoreMerchantOnboardingRequest`, `RejectMerchantRequest` |
| Controllers | `{Area}\{Entity}Controller` | `Merchant\OnboardingController`, `Admin\MerchantController` |
| Routes | snake_case names | `merchant.onboarding`, `merchant.activation`, `admin.merchants.index` |
| Views | kebab-case paths | `merchant/onboarding/edit.blade.php`, `admin/merchants/show.blade.php` |
| Blade partials | kebab-case | `status-pending-review.blade.php` |

---

## 11. Files to create or update (exact list)

### Create

- `database/migrations/xxxx_xx_xx_create_merchants_table.php`
- `database/migrations/xxxx_xx_xx_backfill_merchants_for_existing_merchant_users.php` *(or combine with create + PHP block—implementer chooses single migration vs two)*
- `app/Enums/MerchantStatus.php`
- `app/Models/Merchant.php`
- `app/Services/Merchant/MerchantStatusService.php`
- `app/Services/Merchant/MerchantProfileService.php`
- `app/Http/Middleware/EnsureMerchantStatus.php`
- `app/Http/Controllers/Merchant/OnboardingController.php`
- `app/Http/Controllers/Merchant/ActivationController.php`
- `app/Http/Requests/Merchant/StoreMerchantOnboardingRequest.php`
- `app/Http/Controllers/Admin/MerchantController.php`
- `app/Http/Requests/Admin/RejectMerchantRequest.php`
- `app/Http/Requests/Admin/SuspendMerchantRequest.php`
- `resources/views/merchant/onboarding/*.blade.php`
- `resources/views/merchant/activation/*.blade.php`
- `resources/views/merchant/dashboard/index.blade.php` (refactor from current)
- `resources/views/merchant/dashboard/partials/status-*.blade.php`
- `resources/views/admin/merchants/index.blade.php`
- `resources/views/admin/merchants/show.blade.php`

### Update

- `app/Models/User.php` — `merchant()` relationship
- `app/Services/Auth/AuthService.php` — after `registerMerchant`, create `Merchant` row (`draft`)
- `app/Http/Controllers/Merchant/Auth/RegisterController.php` — only if creation must be coordinated (prefer all in `AuthService`)
- `app/Http/Controllers/Merchant/DashboardController.php` — status-aware data + partials
- `routes/web.php` — merchant + admin routes
- `bootstrap/app.php` — alias `merchant.status`
- `database/seeders/*` — optional demo merchants for local dev

---

## 12. Explicitly out of scope for Task 005

- Products, categories, inventory, media uploads beyond text fields
- Cart, checkout, orders, pickups
- Payouts, fees, invoices
- Real subscription billing, payment gateways, webhooks, “fake payment” screens
- Email notifications (optional stub later; not required for v1 foundation)
- Public creator profile page
- Re-application after `rejected`
- Duplicate status on `users` table
- Direct `merchants.status` updates outside `MerchantStatusService`

---

## 13. Failure conditions (architecture-level)

Implementation fails architecture if:

1. Status is stored or updated outside `merchants.status` + `MerchantStatusService`.
2. More than one `merchants` row per user or merchants attached to non-merchant users.
3. Payment or fake checkout is introduced for `accepted_pending_subscription`.
4. Controllers contain transition rules instead of delegating to `MerchantStatusService`.

---

## 14. Handoff

Task-writer should produce `tasks/005-merchant-lifecycle-foundation.md` with executable steps matching this document; reviewer validates no scope creep against §12.
