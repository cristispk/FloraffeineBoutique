# Merchant Lifecycle — Floraffeine Boutique

## Purpose

This document describes the **implemented** merchant (creator) lifecycle in the Boutique application: states, transitions, services, routes, middleware, and dashboard behavior. It aligns with:

- `docs/ARCHITECTURE.md` — status-driven access (role + business status)
- `docs/SCOPE.md` — merchant lifecycle as mandatory product scope
- Task 005 implementation and validation artifacts under `tasks/`

**Stack note:** Laravel + Blade. Lifecycle state is persisted on the **`merchants`** table only.

---

## Core concepts

| Concept | Description |
|--------|-------------|
| **User** | Authenticated account; `users.role = merchant` for creators. |
| **Merchant** | Business profile row (`merchants`), **1:1** with the merchant user via unique `merchants.user_id`. |
| **Status** | Single source of truth: **`merchants.status`** (string column, `App\Enums\MerchantStatus`). No duplicate lifecycle field on `users`. |
| **Transitions** | All lifecycle transitions run through **`App\Services\Merchant\MerchantStatusService`** inside DB transactions. Controllers do not assign status directly. |

---

## Lifecycle overview

High-level flow:

```text
draft
  → pending_review
      → accepted_pending_subscription  (admin approve)
      → rejected                         (admin reject — terminal in v1)

accepted_pending_subscription
  → active                              (merchant confirmation only — no billing in foundation)

active
  → suspended                           (admin)
suspended
  → active                              (admin reactivate)
```

### States (enum values)

| Status | Meaning |
|--------|---------|
| `draft` | Profile incomplete or not yet submitted for review. |
| `pending_review` | Submitted; awaiting admin decision. |
| `accepted_pending_subscription` | Approved; merchant must confirm activation (Creator Plan billing is a future module). |
| `active` | Operational creator account (subject to future feature gates). |
| `rejected` | Admin rejected application; **terminal in v1** (no self-service re-onboarding). |
| `suspended` | Admin suspended an otherwise active merchant. |

### Transition ownership

All transitions in the matrix are executed only by **`MerchantStatusService`** methods:

- `submitForReview`, `approve`, `reject`, `confirmActivation`, `suspend`, `reactivate`

Invalid transitions throw; they are not silently ignored.

---

## Role of `MerchantStatusService`

**Responsibilities:**

- Enforce allowed **from → to** transitions.
- Persist status and audit fields (`submitted_at`, `reviewed_at`, `reviewed_by`, `rejection_reason`, `suspended_at`, `suspension_reason`, `activated_at`) inside **transactions**.
- Require admin `User` for admin-driven actions; validate role where applicable.

**Not responsible for:** editing marketing copy, Blade rendering, or HTTP concerns (those stay in controllers/views).

**Related:** `MerchantProfileService` updates **profile fields** only while `status === draft`; it does not change lifecycle status (except initial row creation via `firstOrCreate` / registration — not a transition from another state).

---

## Registration and data integrity

- **New merchant registration:** `AuthService::registerMerchant` creates `User` + `Merchant` in one transaction, `status = draft`, `business_name` from registration name.
- **Existing users:** Migration backfill inserts a `merchants` row for every `users.role = merchant` that had no row (draft, `business_name` from user name or placeholder).
- **Invariant:** Each merchant user has **exactly one** `merchants` row; `user_id` is **unique**.

---

## Onboarding flow

- **Routes:** `GET/POST /merchant/onboarding` (`merchant.onboarding`, `merchant.onboarding.store`), middleware: `auth`, `role:merchant`, **`merchant.status:draft`**.
- **Behavior:** Merchant edits profile (studio/brand, description, contact, etc.). Actions: **save draft** or **submit for review**.
- **Submit:** Validates minimum requirements (e.g. `business_name`), then `MerchantStatusService::submitForReview` → `pending_review`, sets `submitted_at`.
- **UI:** Romanian copy; uses shared auth layout and UI components (`layouts.auth`, `x-ui.*`).

---

## Admin review flow

- **Routes:** Under `/admin/merchants`, `auth`, `role:admin` (e.g. `admin.merchants.index`, `admin.merchants.show`, approve/reject/suspend/reactivate POST actions).
- **List:** Default view emphasizes **`pending_review`**; optional `?filter=all` for all merchants (implementation uses query parameter `filter`).
- **Detail:** Read-only profile + user email; actions depend on status:
  - **Pending review:** approve → `accepted_pending_subscription`, or reject with **required reason** → `rejected`.
  - **Active:** suspend with **required reason** → `suspended`.
  - **Suspended:** reactivate → `active`.
- **Rejected:** No destructive actions; explanation read-only.

---

## Activation flow (confirmation only)

- **Routes:** `GET /merchant/activation`, `POST /merchant/activation/confirm`, middleware: **`merchant.status:accepted_pending_subscription`**.
- **Behavior:** Merchant confirms readiness to operate. **`MerchantStatusService::confirmActivation`** sets `active` and `activated_at` (first activation). **No payment capture, no fake checkout, no subscription billing** in this foundation task — UI states that billing will follow in a later iteration.
- **UI:** Romanian; single confirmation POST; no card fields.

---

## Middleware: `merchant.status`

- **Class:** `App\Http\Middleware\EnsureMerchantStatus`
- **Alias:** `merchant.status` (see `bootstrap/app.php`)
- **Usage:** e.g. `merchant.status:draft` — allow request only if the current merchant’s status is in the allowed set (comma-separated list supported).
- **Order:** `auth` → `role:merchant` → `merchant.status:…` on protected merchant routes.
- **If status not allowed:** Redirect to **`merchant.dashboard`** with flash: *„Acțiunea nu este disponibilă pentru starea contului tău.”*
- **Missing merchant row:** `ensureMerchantRecordExists` on `MerchantProfileService` provisions a **`draft`** row for the authenticated merchant user (used by this middleware and merchant controllers); status checks are always against a real `merchants` row.

---

## Dashboard by status

- **Route:** `GET /merchant/dashboard` — **no** `merchant.status` middleware (all merchant statuses can open the dashboard shell).
- **Rendering:** Controller passes the current `Merchant`; view includes **one Blade partial** per status, named from enum value (kebab-case), e.g. `status-draft`, `status-pending-review`, `status-accepted-pending-subscription`, etc.
- **CTAs (merchant-facing, Romanian):**
  - **draft:** link to complete onboarding.
  - **pending_review:** informational.
  - **accepted_pending_subscription:** link to activation.
  - **active:** welcome / placeholder for future modules.
  - **rejected / suspended:** explanation; **rejected** has no link back to onboarding (terminal).

---

## Constraints (summary)

| Constraint | Rule |
|------------|------|
| **1:1 User ↔ Merchant** | Unique `merchants.user_id`; one profile per merchant user. |
| **Status source of truth** | Only `merchants.status`; not duplicated on `users`. |
| **Initial status** | `draft` for new merchant rows. |
| **Transitions** | Only via `MerchantStatusService` (except initial row creation at registration / backfill / `ensureMerchantRecordExists`). |
| **Rejected (v1)** | Terminal: no onboarding route access; profile edits blocked outside draft. |
| **Activation** | Confirmation-only until real billing exists; no payment UI in foundation. |
| **Out of scope (foundation)** | Products, cart, checkout, orders, payouts, real subscription billing. |

---

## Related documentation

- `docs/ARCHITECTURE.md` — full product areas and status-driven access principle
- `docs/SCOPE.md` — merchant experience scope
- `docs/04-routing-and-access.md` — public / merchant / admin separation
- `docs/05-database-modeling.md` — data modeling expectations
- `tasks/done/` / `tasks/architecture/005-merchant-lifecycle-foundation.md` — implementation task handoff (when closed)

---

## Revision note

This document reflects the **merchant lifecycle foundation** delivered as Task 005. Future tasks (e.g. Creator Plan billing, products) must extend this model without duplicating lifecycle state on `users` or bypassing `MerchantStatusService` for transitions.
