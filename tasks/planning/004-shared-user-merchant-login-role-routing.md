# Planning — Task 004 (Shared User + Merchant Login with Role-Based Routing)

## Intent
Unify non-admin authentication entry into a single shared login flow for `user` + `merchant`, keep admin auth fully isolated, and make post-login routing deterministic by authenticated role.

Business target:
- one login page/POST flow for user + merchant
- admin login remains separate (`/admin/login`)
- successful login auto-redirects to role-appropriate dashboard
- no accidental breakage in registration and password reset flows

## Current implementation snapshot (analysis baseline)
- Routes currently split login by area:
  - user: `/login` handled by `User\Auth\LoginController`
  - merchant: `/merchant/login` handled by `Merchant\Auth\LoginController`
  - admin: `/admin/login` handled by `Admin\Auth\LoginController`
- Login service behavior:
  - `AuthService::attemptLoginForRole($role, $credentials)` requires a single expected role and logs out wrong-role logins.
- Redirect behavior:
  - user login -> `user.dashboard`
  - merchant login -> `merchant.dashboard`
  - admin login -> `admin.dashboard`
  - `RoleMiddleware` already has deterministic “redirect to own dashboard” fallback by role.
- UI:
  - separate Blade pages for public and merchant login.
- Password reset:
  - still split by area (`/password/*` and `/merchant/password/*`), both using broker `users`, with different post-reset redirect targets (`user.dashboard` vs `merchant.dashboard`).

## Scope constraints
- Laravel + Blade only.
- No SPA/framework changes.
- No unnecessary business-logic refactor.
- Keep admin auth isolated.
- Do not change registration/reset unless strictly required by final architecture.

---

## Phase 1 — Route and flow mapping (design decision lock)
### Goal
Define the single entry route for non-admin login and freeze which old merchant login routes are removed vs redirected.

### Concrete steps
1. Decide canonical shared login entrypoint:
   - Keep `/login` as single non-admin GET/POST.
2. Define fate of `/merchant/login`:
   - recommended: keep as temporary compatibility redirect to `/login` (GET and POST behavior explicitly defined), or fully remove and update all links.
3. Confirm admin isolation remains unchanged:
   - `/admin/login` GET/POST untouched.
4. Confirm non-login flows unchanged in this task:
   - registration endpoints remain split unless architecture phase identifies required updates.
   - reset-password endpoints remain split unless required.

### Dependencies
- None.

### Likely files/areas
- `routes/web.php`
- planning notes reflected in subsequent controller/service phases.

### Risks / ambiguities
- Existing bookmarks/deep-links to `/merchant/login`.
- Existing blade links currently pointing to `merchant.login`.

---

## Phase 2 — Shared authentication attempt logic
### Goal
Introduce deterministic shared non-admin login authentication path (accept user or merchant, reject admin on shared flow).

### Concrete steps
1. Add/extend auth service method for shared non-admin login:
   - candidate in `AuthService`:
     - `attemptLoginForRoles(array $allowedRoles, array $credentials): ?User` (or bool + get authenticated user).
2. Ensure method:
   - authenticates via `web` guard,
   - allows only `user` and `merchant`,
   - immediately logs out and fails if authenticated role is `admin` or unexpected.
3. Keep existing `attemptLoginForRole` used by admin login unchanged (or retain for backward compatibility where needed).

### Dependencies
- Phase 1 route decision (single endpoint behavior).

### Likely files/areas
- `app/Services/Auth/AuthService.php`
- `app/Models/User.php` (constants/helpers already present; likely read-only unless minor helper needed).

### Risks / ambiguities
- Accidental privilege crossover if admin accepted in shared flow.
- Wrong-role handling must stay indistinguishable from invalid credentials.

---

## Phase 3 — Controller/request unification for non-admin login
### Goal
Move user+merchant login POST to one controller/request path and implement deterministic post-login redirect by role.

### Concrete steps
1. Select owning controller for shared login:
   - recommended: `User\Auth\LoginController` becomes shared non-admin login controller.
2. Replace role-locked login attempt with shared-role attempt from Phase 2.
3. Implement deterministic redirect in controller after successful login:
   - `merchant` -> `merchant.dashboard`
   - `user` -> `user.dashboard`
   - any other role -> fail-safe logout + invalid credentials response (or explicit block).
4. Keep admin login controller logic unchanged.
5. Standardize request validation for shared login:
   - either reuse `UserLoginRequest` for shared flow, or create a dedicated shared request.
   - ensure recaptcha conditional logic remains correct (already fixed in current codebase).

### Dependencies
- Phase 2 service method availability.

### Likely files/areas
- `app/Http/Controllers/User/Auth/LoginController.php`
- `app/Http/Controllers/Merchant/Auth/LoginController.php` (deprecate, reduce, or remove usage)
- `app/Http/Requests/User/Auth/UserLoginRequest.php` and/or `app/Http/Requests/Merchant/Auth/MerchantLoginRequest.php`

### Risks / ambiguities
- Duplicate logic if both controllers remain active.
- Inconsistent error messaging or redirect behavior between old/new endpoints.

---

## Phase 4 — Route wiring + page/navigation consolidation
### Goal
Ensure all non-admin login navigation and route names point to the single shared flow.

### Concrete steps
1. Update route declarations in `routes/web.php`:
   - one active non-admin login GET/POST.
   - merchant login routes either removed or redirected by explicit compatibility policy from Phase 1.
2. Update auth Blade links:
   - pages currently using `route('merchant.login')` should point to shared `route('login')` where appropriate.
3. Keep admin links untouched:
   - admin login entry remains `route('admin.login')`.
4. Ensure logout endpoints remain role-area specific if required by existing route design.

### Dependencies
- Phase 1 decisions + Phase 3 shared controller path.

### Likely files/areas
- `routes/web.php`
- `resources/views/public/auth/login.blade.php`
- `resources/views/merchant/auth/login.blade.php`
- other auth views containing merchant login links (e.g., merchant register/login cross-links).

### Risks / ambiguities
- Stale route names causing blade/runtime errors.
- Merchant-facing copy may still imply separate login if text is not updated.

---

## Phase 5 — Guardrails for registration/reset-password stability
### Goal
Prevent unintended breakage in registration and password reset while login is unified.

### Concrete steps
1. Confirm registration remains unchanged:
   - user register routes/controllers/views still work.
   - merchant register routes/controllers/views still work.
2. Confirm reset-password remains unchanged:
   - user and merchant forgot/reset routes still reachable.
   - post-reset redirects still role-appropriate and deterministic.
3. If any route reference to old merchant login is required for reset UX, update links to shared login intentionally (not by accident).

### Dependencies
- Phase 4 route/link updates.

### Likely files/areas
- `app/Http/Controllers/User/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Merchant/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/User/Auth/ResetPasswordController.php`
- `app/Http/Controllers/Merchant/Auth/ResetPasswordController.php`
- related password Blade templates under:
  - `resources/views/public/auth/passwords/*`
  - `resources/views/merchant/auth/passwords/*`

### Risks / ambiguities
- Reset forms may contain hardcoded merchant login route links.
- Post-reset auto-login redirect may conflict with future shared logic if role inference is inconsistent.

---

## Phase 6 — Validation/testing and deterministic role-routing checks
### Goal
Prove shared non-admin login works and admin remains isolated, without collateral auth regressions.

### Concrete test/validation checklist
1. Shared login acceptance:
   - `/login` authenticates valid `user` account -> redirects `user.dashboard`.
   - `/login` authenticates valid `merchant` account -> redirects `merchant.dashboard`.
2. Shared login rejection:
   - `/login` with admin credentials does NOT grant access through shared flow.
3. Admin isolation:
   - `/admin/login` still authenticates admin and redirects `admin.dashboard`.
   - `/admin/login` does not authenticate user/merchant credentials as admin.
4. Route compatibility:
   - `/merchant/login` behavior matches chosen policy (redirect or removed) and does not expose a separate merchant-only flow.
5. Reset/register non-regression:
   - user and merchant registration still operate.
   - user and merchant forgot/reset links still operate and redirect correctly.
6. Middleware determinism:
   - accessing wrong dashboard route while authenticated redirects to own dashboard (`RoleMiddleware` behavior remains correct).
7. reCAPTCHA conditional validation (recent fix preservation):
   - with `RECAPTCHA_ENABLED=false`, shared login does not require token.
   - with `RECAPTCHA_ENABLED=true`, shared login requires token.

### Dependencies
- All prior phases complete.

### Likely files/areas
- tests and manual smoke checks over routes/controllers/views listed above.

---

## Explicit checks required by this task
1. **Admin remains separate**
   - admin routes/controller/view unchanged in responsibility and URL space.
2. **User/merchant login is shared**
   - one active non-admin login page + POST flow.
3. **Role-based redirect deterministic**
   - post-login redirect map is explicit and role-driven, no ambiguous fallback.
4. **No accidental reset/register breakage**
   - registration and reset flows remain operational unless explicitly changed by architecture decision.

---

## UI/UX review points for shared login
1. Shared page copy should clearly indicate it serves both users and merchants.
2. Remove conflicting navigation that implies separate merchant login.
3. Keep admin entry distinct and non-prominent in shared non-admin UX.
4. Ensure auth links from merchant register and password flows point to the shared login endpoint consistently.

---

## Recommended execution order for implementer
1. Phase 1: lock route policy for `/login` and `/merchant/login`.
2. Phase 2: implement shared non-admin auth method in `AuthService`.
3. Phase 3: wire shared controller/request logic with deterministic role redirect.
4. Phase 4: update route wiring and Blade links to single login flow.
5. Phase 5: verify registration/reset flows remain intact; apply only necessary link adjustments.
6. Phase 6: run validation checks (role routing, admin isolation, recaptcha behavior, regressions).

