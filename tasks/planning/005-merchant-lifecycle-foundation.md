# Planning — Task 005: Merchant Lifecycle Foundation

## Purpose

Establish the **merchant as a first-class domain entity** with persisted business data, a **formal status machine**, and **status-aware access**—on top of existing authentication (shared login, role-based routing, base UI). Today, `merchant` is only a `users.role` value; that is insufficient for approvals, subscriptions-to-come, and operational rules.

This planning aligns with:

- `docs/SCOPE.md` — merchant lifecycle is mandatory; features must respect lifecycle.
- `docs/ARCHITECTURE.md` — status-driven access (role + business status); merchant area enforcement.
- `docs/ROADMAP.md` — merchant onboarding, status flow, admin approval, and creator-plan activation are sequenced; this task **consolidates the foundational slice** so later roadmap items (products, public boutique, billing) plug in without rework.

**Explicitly out of scope for this task (foundational only):**

- Product catalog, cart, checkout, orders, payouts.
- Real subscription billing, payment provider, or invoice generation (`accepted_pending_subscription` may be **modeled and surfaced in UI** without implementing billing).

**Technical constraints:**

- Laravel + Blade only; no SPA frameworks.
- Preserve existing auth areas (public / merchant / admin) and shared login behavior.

---

## Target outcomes (what “done” means)

1. **Merchant profile / onboarding foundation**: structured data (not only `User`) collected and stored; clear path from first-time merchant to review queue.
2. **Status machine** with these states, persisted and enforced:
   - `draft`
   - `pending_review`
   - `accepted_pending_subscription`
   - `active`
   - `rejected`
   - `suspended`
3. **Clear relationship** between `User` (auth identity, `role = merchant`) and **merchant business record** (profile, status, audit fields).
4. **Access rules** by merchant status (middleware / policies / services—implementation detail left to architecture task).
5. **Minimal admin review**: list merchants needing attention, open detail, approve / reject (and optionally suspend) with audit trail hooks.
6. **Merchant dashboard** (and related merchant routes) **reflect current status**: messaging, allowed actions, and redirects—not a single static page for all merchants.

---

## Ordered phases

### Phase A — Domain model and persistence

**Goal:** Introduce a dedicated merchant entity and migrations; link to user; seed-safe defaults.

**Concrete steps:**

1. Add `merchants` (or equivalent name per naming convention) table:
   - Foreign key to `users.id` (one merchant business profile per merchant user—decide 1:1 and enforce unique `user_id`).
   - Profile fields needed for onboarding foundation (minimal set: e.g. display/studio name, description, contact, legal identifiers if required by source docs—exact columns to be taken from source-of-truth docs in architecture).
   - `status` column using an enum/string backed by PHP enum or constants (consistent with Laravel style used in project).
   - Timestamps + optional `reviewed_at`, `rejection_reason`, `suspended_at` / `suspension_reason` if needed for admin flows (keep minimal).
2. Add Eloquent `Merchant` model; `User` ↔ `Merchant` relationship methods (`merchant()` / `user()`).
3. Decide **initial status** for new merchant registrations:
   - Recommended default: `draft` until onboarding minimum is submitted, then transition to `pending_review` (alternative: start `pending_review` with empty profile—document risk in architecture).
4. Backfill strategy: existing `merchant` users in DB without a row—migration or one-time command (architecture/implementer to choose safest).

**Likely files/folders:**

- `database/migrations/*_create_merchants_table.php`
- `app/Models/Merchant.php`
- `app/Models/User.php` (relationship + helpers)
- Optional: `database/seeders/` updates if dev fixtures need merchants

---

### Phase B — Status machine (domain rules, no billing)

**Goal:** Centralize transitions and guards so controllers stay thin.

**Concrete steps:**

1. Define allowed transitions (example—finalize in architecture):

   - `draft` → `pending_review` (onboarding submitted)
   - `pending_review` → `accepted_pending_subscription` | `rejected` (admin)
   - `accepted_pending_subscription` → `active` (manual “activation” or placeholder CTA until billing exists—**no payment integration**)
   - `active` → `suspended` (admin)
   - `suspended` → `active` or remain suspended per rules
   - `rejected` → terminal or allow re-application (decision: architecture; default **terminal** for v1 to reduce scope)

2. Implement `MerchantStatusService` (or similarly named) responsible for:
   - Validating transition requests
   - Setting status + relevant timestamps/reason fields
   - Emitting no domain events unless already project pattern—keep simple

3. Ensure **invalid transitions** fail predictably (exception or false + flash message); never silently ignore.

**Likely files/folders:**

- `app/Services/Merchant/MerchantStatusService.php` (or `app/Services/Merchant/...`)
- `app/Enums/MerchantStatus.php` (if project adopts enums)
- Unit/feature tests under `tests/` (task for tester later)

---

### Phase C — Merchant profile and onboarding (foundation UI)

**Goal:** Merchant can complete required onboarding data and trigger transition toward review.

**Concrete steps:**

1. Routes under `merchant` prefix, `auth` + `role:merchant` + **merchant status middleware** where applicable:
   - `GET/POST` onboarding wizard or single form (minimal v1 acceptable if spec allows).
2. Form requests for validation; Romanian UI copy per project rules.
3. Controller delegates to a **MerchantProfileService** or repository pattern consistent with existing `AuthService` style.
4. On successful submit from `draft`: move to `pending_review` (if that is the chosen rule).

**Likely files/folders:**

- `routes/web.php` (merchant group)
- `app/Http/Controllers/Merchant/OnboardingController.php` (or `ProfileController`)
- `app/Http/Requests/Merchant/...`
- `resources/views/merchant/onboarding/*.blade.php`
- `resources/views/layouts/auth.blade.php` or merchant layout if introduced (reuse existing components per `docs/UI_COMPONENTS_REFERENCE.md`)

---

### Phase D — Access rules (status + role)

**Goal:** Authentication proves identity; **status proves what the merchant may do**.

**Concrete steps:**

1. Introduce middleware e.g. `merchant.status` or `EnsureMerchantStatus` with parameters listing allowed statuses for a route group (e.g. dashboard “read-only” for `pending_review` vs full `active`).
2. Map route groups:
   - Onboarding: only `draft` (and maybe `rejected` if re-apply allowed later).
   - Post-submission: read-only status page for `pending_review`, `rejected`, `suspended`.
   - “Activation” placeholder page: only `accepted_pending_subscription`.
   - Operational merchant area (future products): only `active`—for this task, **dashboard sections** gated, not product CRUD.
3. Align with `RoleMiddleware`: order of middleware matters (auth → role → merchant status).
4. Admin routes unaffected except new merchant management routes.

**Likely files/folders:**

- `app/Http/Middleware/EnsureMerchantStatus.php` (name illustrative)
- `bootstrap/app.php` or middleware registration per Laravel 11 project pattern
- `routes/web.php` (grouped middleware)

**DB/model considerations:**

- Never duplicate status on `users` table; **single source of truth** on `merchants.status` (optional cached denormalization only if justified later—not in foundation unless needed for queries).

---

### Phase E — Minimal admin review / approval

**Goal:** Admins can drive `pending_review` → `accepted_pending_subscription` or `rejected`.

**Concrete steps:**

1. Routes under `admin`, `role:admin`:
   - Index: merchants filtered by `pending_review` (and optionally all with filters).
   - Show: merchant profile + user email link (read-only fields).
2. Actions: Approve (to `accepted_pending_subscription`), Reject (with reason), optional Suspend for `active` merchants.
3. Use admin layout and Romanian copy; no product moderation here.

**Likely files/folders:**

- `app/Http/Controllers/Admin/MerchantController.php` (or split controllers)
- `resources/views/admin/merchants/*.blade.php`
- Form requests for admin actions

---

### Phase F — Merchant dashboard behavior by status

**Goal:** Replace “one static dashboard for all merchants” with status-appropriate experience.

**Concrete steps:**

1. `Merchant\DashboardController` loads `auth` user’s `Merchant` record; if missing, redirect to onboarding or show error (edge case from backfill).
2. Blade shows:
   - **draft**: CTA to complete onboarding.
   - **pending_review**: explanation + no destructive actions.
   - **accepted_pending_subscription**: CTA “activează planul creator” → placeholder route that sets `active` **without billing** (clearly labeled as temporary until roadmap billing task) OR static page explaining next step—**decision point for architecture** to avoid fake payments.
   - **active**: current simple dashboard content (still no products in this task).
   - **rejected** / **suspended**: explanation + support/contact copy; no operational CTAs.

**Likely files/folders:**

- `app/Http/Controllers/Merchant/DashboardController.php`
- `resources/views/merchant/dashboard/*.blade.php` (possibly partials per status)

---

## Dependencies between phases

```
A (DB/model) → B (status service) → C (onboarding) ─┐
                     ↓                               │
              D (access middleware) ←─────────────┤
                     ↓                               │
              E (admin review)                      │
                     ↓                               │
              F (dashboard by status) ←─────────────┘
```

- **C** depends on **A** and **B**.
- **D** depends on **A** and **B**; should exist before **E** and **F** expose sensitive routes.
- **E** depends on **A**, **B**, **D** (admin must be protected).
- **F** depends on **A**, **B**, **D**; content can ship after **C** minimal path exists.

---

## Recommended execution order (for implementer)

1. Phase A — migrations + models + user relationship + backfill decision.
2. Phase B — status service + transition rules + tests skeleton.
3. Phase D — merchant status middleware + apply to merchant route groups (may temporarily show “maintenance” messages until C/E/F complete).
4. Phase C — onboarding UI + submit.
5. Phase E — admin list/detail/approve/reject.
6. Phase F — dashboard variants + placeholder activation for `accepted_pending_subscription` (non-billing).

---

## Likely files/folders summary (non-exhaustive)

| Area | Paths |
|------|--------|
| Migrations / models | `database/migrations/`, `app/Models/Merchant.php`, `app/Models/User.php` |
| Services | `app/Services/Merchant/` |
| HTTP | `app/Http/Controllers/Merchant/`, `app/Http/Controllers/Admin/`, `app/Http/Middleware/`, `app/Http/Requests/Merchant/`, `app/Http/Requests/Admin/` |
| Routes | `routes/web.php` |
| Views | `resources/views/merchant/`, `resources/views/admin/merchants/` |
| Bootstrap | `bootstrap/app.php` (middleware) |
| Tests | `tests/Feature/...` (later tester phase) |

---

## Risks and ambiguities

1. **Source-of-truth field list**: Exact onboarding fields (tax, address, brand, etc.) must be pulled from `docs/source-of-truth/` in the architecture step—planning assumes “minimal required set” without inventing legal requirements.
2. **`draft` vs immediate `pending_review`**: Product decision affects UX and empty-state handling; recommend explicit default in architecture.
3. **`accepted_pending_subscription` without billing**: Risk of confusing users if UI implies payment; must be clearly copy-safe (Romanian) and architecturally stubbed (e.g. “confirm activation” admin-only toggle vs merchant button—decide in architecture).
4. **Rejected merchants**: Re-register vs edit same profile—impacts unique constraints and support burden.
5. **Performance / N+1**: Admin index should eager-load `user` relationship.
6. **Alignment with roadmap numbering**: Roadmap lists separate tasks for onboarding, status, admin approval, activation, dashboard; Task 005 intentionally **bundles foundation**—later tasks (products, billing) must not duplicate tables; document follow-up tasks to avoid scope creep in 005.

---

## Explicit checks before closing implementation (for tester / acceptance)

- [ ] Merchant user has exactly one merchant record (or documented exception path).
- [ ] Status transitions only via service layer (no ad-hoc `update(['status' => ...])` scattered in controllers).
- [ ] `pending_review` merchant cannot access `active`-only routes (when those exist).
- [ ] Admin can approve/reject from list/detail; audit fields populated where defined.
- [ ] Dashboard content changes visibly by status (smoke test).
- [ ] No cart, checkout, product, or payout routes added in this task.

---

## Handoff notes

- Next workflow steps per project rules: **architect** → `tasks/architecture/005-merchant-lifecycle-foundation.md`, then task-writer, reviewer, implementer, tester, etc.
- This document is **planning only**; it does not replace source documents or architecture decisions.
