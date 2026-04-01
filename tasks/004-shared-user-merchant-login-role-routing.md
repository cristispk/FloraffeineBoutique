# Task 004 — Shared User + Merchant Login with Deterministic Role Routing

## Purpose
Implement one shared non-admin login flow for `user` + `merchant`, while keeping admin login fully isolated.

This task must deliver:
- one canonical non-admin login entry (`/login`)
- compatibility redirect behavior for legacy `/merchant/login`
- deterministic redirect after successful login by authenticated role
- no regressions in registration or password-reset flows

This is **Laravel + Blade only**.

---

## Hard constraints (non-negotiable)
1. Admin auth stays isolated under `/admin/login`.
2. `/merchant/login` must no longer authenticate merchants directly.
3. Wrong-role behavior must remain indistinguishable from invalid credentials.
4. No registration-flow unification.
5. No password-reset-flow unification.
6. No route/controller refactor outside the files listed below.
7. No UI redesign except minimal link target updates required for shared login.

---

## Exact files to update

1. `routes/web.php`
2. `app/Services/Auth/AuthService.php`
3. `app/Http/Controllers/User/Auth/LoginController.php`
4. `resources/views/merchant/auth/register.blade.php`
5. `resources/views/merchant/auth/passwords/forgot.blade.php` *(only if merchant-login link exists)*
6. `resources/views/merchant/auth/passwords/reset.blade.php` *(only if merchant-login link exists)*

## Files explicitly **not** to change behavior for
- `app/Http/Controllers/Admin/Auth/LoginController.php` (admin flow ownership unchanged)
- `app/Http/Requests/User/Auth/UserLoginRequest.php` (shared request owner remains this file)
- `app/Http/Requests/Admin/Auth/AdminLoginRequest.php`
- user/merchant register controllers/requests (except view link target updates listed above)
- user/merchant forgot/reset controllers and broker/token logic

## File to deprecate (no route ownership after implementation)
- `app/Http/Controllers/Merchant/Auth/LoginController.php`  
  Keep file in codebase, but no active login route may point to its login/form actions.

---

## Required route behavior (exact)

### Shared non-admin login
- `GET /login` -> `User\Auth\LoginController@showLoginForm`, route name `login`
- `POST /login` -> `User\Auth\LoginController@login`

### Merchant compatibility alias
- `GET /merchant/login` -> redirect to `route('login')`, keep route name `merchant.login`
- `POST /merchant/login` -> redirect to `route('login')`
- No authentication attempt is allowed on `/merchant/login`.

### Admin isolation
- Keep `GET /admin/login` and `POST /admin/login` bound to `Admin\Auth\LoginController` unchanged.

### Logout endpoints
- Keep existing `/logout`, `/merchant/logout`, `/admin/logout` routes unchanged in this task.

---

## Required service-layer behavior (exact)

Update `app/Services/Auth/AuthService.php`:

1. Add method:
   - `attemptLoginForNonAdmin(array $credentials): ?\App\Models\User`

2. Method rules:
   - attempt auth using `web` guard with provided email/password (+ remember)
   - if attempt fails: return `null`
   - if authenticated user role is NOT one of:
     - `User::ROLE_USER`
     - `User::ROLE_MERCHANT`
     then:
     - logout immediately
     - return `null`
   - else return authenticated `User`

3. Keep existing methods intact:
   - `attemptLoginForRole()` remains for admin flow
   - registration and logout methods unchanged

---

## Required controller behavior (exact)

Update `app/Http/Controllers/User/Auth/LoginController.php`:

1. `showLoginForm()` remains the shared non-admin login page owner:
   - returns `public.auth.login`

2. `login()` must:
   - use existing `UserLoginRequest` validation
   - call `AuthService::attemptLoginForNonAdmin($credentials)`
   - on failure: keep current invalid credentials response pattern
     - same `email` error key/message behavior
     - same withInput behavior
   - on success: redirect deterministically by authenticated role:
     - `merchant` -> `route('merchant.dashboard')`
     - `user` -> `route('user.dashboard')`
     - any unexpected role -> logout + invalid credentials response (do not expose role details)

3. Do not introduce role input from request payload.

---

## Required request-validation ownership
- Shared non-admin login must use existing:
  - `App\Http\Requests\User\Auth\UserLoginRequest`
- Do not create a new shared login request in this task.
- Do not bind `MerchantLoginRequest` to active login routes after this change.

---

## Required Blade link updates (compatibility and UX consistency)

Update merchant-auth-related views so login links point to shared login route where needed:
- `resources/views/merchant/auth/register.blade.php`
- merchant password views listed above (only if they contain links to merchant login)

Rule:
- Replace links targeting `route('merchant.login')` with `route('login')` for user-facing login CTA links.
- Do not change admin login links.

---

## Strict implementation steps (execution order)

### Step 1 — Route rewiring
1. Update `routes/web.php`:
   - keep `/login` GET/POST as shared non-admin flow under `User\Auth\LoginController`
   - replace `/merchant/login` GET/POST handlers with redirects to `route('login')`
   - keep route name `merchant.login` on GET redirect
   - leave `/admin/login` flow unchanged

### Step 2 — AuthService shared non-admin method
1. Add `attemptLoginForNonAdmin()` in `AuthService`.
2. Enforce allowed roles (`user`, `merchant`) and immediate logout for others.

### Step 3 — Shared login controller ownership
1. Update `User\Auth\LoginController@login` to use `attemptLoginForNonAdmin()`.
2. Implement deterministic redirect map by role.
3. Preserve invalid-credentials behavior for failures.

### Step 4 — Merchant controller deprecation by route ownership
1. Ensure no login route points to `Merchant\Auth\LoginController@showLoginForm` or `@login`.
2. Keep controller file present; do not broad-delete/refactor.

### Step 5 — Merchant auth view link updates
1. Update merchant auth/register/reset/forgot link targets to shared `route('login')` where applicable.
2. Do not alter reset broker/token/controller logic.

### Step 6 — Regression checks (required)
Run all required scenarios from the validation section below.

---

## Validation scenarios (must all pass)

### A. Shared login correctness
1. Valid `user` credentials on `/login` -> redirect to `user.dashboard`.
2. Valid `merchant` credentials on `/login` -> redirect to `merchant.dashboard`.
3. Valid `admin` credentials on `/login` -> fail as invalid credentials (no admin login through shared flow).

### B. Admin isolation
1. Valid admin credentials on `/admin/login` -> `admin.dashboard`.
2. Non-admin credentials on `/admin/login` -> invalid credentials response.

### C. Merchant compatibility redirect
1. `GET /merchant/login` redirects to `/login`.
2. `POST /merchant/login` redirects to `/login` and does not authenticate directly.

### D. Registration and reset non-regression
1. User register flow unchanged and works.
2. Merchant register flow unchanged and works.
3. User forgot/reset flow unchanged and works.
4. Merchant forgot/reset flow unchanged and works.

### E. Role-routing determinism
1. After successful shared login, redirect destination is fully role-deterministic (`user` vs `merchant`).
2. Role mismatch does not leak role details in error responses.

---

## Acceptance criteria
1. One active non-admin login flow exists at `/login` for both user and merchant.
2. Admin login remains isolated under `/admin/login`.
3. `/merchant/login` is compatibility redirect-only; it does not execute merchant auth attempt.
4. Shared login redirects users deterministically by authenticated role:
   - user -> `user.dashboard`
   - merchant -> `merchant.dashboard`
5. Wrong-role behavior remains indistinguishable from invalid credentials.
6. Merchant auth links that previously implied separate merchant login now point to shared login where required.
7. Registration and password-reset flows remain operational and ununified.

---

## Hard failure conditions
Implementation is **failed** if any occur:
1. `/merchant/login` still performs an independent merchant authentication attempt.
2. Admin can authenticate through shared `/login`.
3. Redirect after shared login is non-deterministic or not role-based.
4. Admin routes/controllers are merged into shared flow.
5. Registration or reset-password behavior is changed beyond link-target compatibility updates.
6. New request/controller/refactor introduced outside listed scope.

