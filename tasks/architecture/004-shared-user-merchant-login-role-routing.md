# Architecture — Task 004 (Shared User+Merchant Login, Role Routing)

## 1) Final architecture decisions (no ambiguity)

1. `/merchant/login` **remains**, but only as a compatibility redirect to `/login`.
2. `Merchant\Auth\LoginController` is **deprecated and no longer used by routes** in this task.
3. Shared non-admin login uses **existing** `UserLoginRequest` (no new request class).
4. Route naming strategy:
   - `login` remains the canonical shared non-admin login route name.
   - `merchant.login` route name is temporarily preserved as a redirect alias for compatibility.
5. Wrong-role behavior policy:
   - For shared non-admin login, `admin` credentials are treated as invalid credentials (indistinguishable behavior).
   - For admin login, non-admin credentials are treated as invalid credentials.

---

## 2) Target architecture (post-task)

### Authentication entry points
- Non-admin shared login (user + merchant):
  - `GET /login` -> shared login form
  - `POST /login` -> shared login submit
  - Controller owner: `App\Http\Controllers\User\Auth\LoginController`
- Merchant legacy login endpoint:
  - `GET /merchant/login` -> redirect to `/login` (temporary compatibility)
  - `POST /merchant/login` -> redirect to `/login` (temporary compatibility)
  - No authentication processing on this endpoint.
- Admin login remains isolated:
  - `GET /admin/login`, `POST /admin/login`
  - Controller owner unchanged: `App\Http\Controllers\Admin\Auth\LoginController`

### Post-login deterministic redirect map
After successful non-admin login (`POST /login`):
- role `merchant` -> `route('merchant.dashboard')`
- role `user` -> `route('user.dashboard')`
- any other role (including `admin`) -> authentication attempt fails and returns invalid credentials response

### Role access containment
- Existing `RoleMiddleware` remains active as safety net for dashboard route access.
- Controller-level redirect after login is the primary deterministic routing mechanism.

---

## 3) Exact file strategy

## Files to update
1. `routes/web.php`
2. `app/Services/Auth/AuthService.php`
3. `app/Http/Controllers/User/Auth/LoginController.php`
4. `resources/views/merchant/auth/register.blade.php`
5. `resources/views/merchant/auth/passwords/forgot.blade.php` (if merchant login link exists)
6. `resources/views/merchant/auth/passwords/reset.blade.php` (if merchant login link exists)

## Files to keep (in use, unchanged responsibilities)
1. `app/Http/Controllers/Admin/Auth/LoginController.php`
2. `app/Http/Requests/User/Auth/UserLoginRequest.php`
3. `app/Http/Requests/Admin/Auth/AdminLoginRequest.php`
4. `app/Http/Middleware/RoleMiddleware.php`
5. User + merchant register controllers and requests
6. User + merchant forgot/reset controllers and shared request classes

## Files to deprecate / stop using for login ownership
1. `app/Http/Controllers/Merchant/Auth/LoginController.php`
   - Keep file for now (no broad refactor), but remove route ownership.
   - No route should point to its `showLoginForm` or `login` methods after implementation.

---

## 4) Route architecture (exact)

## Shared non-admin login
- Keep:
  - `GET /login` -> `User\Auth\LoginController@showLoginForm`, name `login`
  - `POST /login` -> `User\Auth\LoginController@login`

## Merchant compatibility alias
- Replace merchant login handlers:
  - `GET /merchant/login` -> closure/controller redirect to `route('login')`, keep name `merchant.login`
  - `POST /merchant/login` -> redirect to `route('login')`
- Rationale: avoids immediate route-name breakage in existing Blade links while enforcing single login entry.

## Admin isolation (unchanged)
- `GET /admin/login` + `POST /admin/login` remain bound to `Admin\Auth\LoginController`.
- Do not merge admin into shared non-admin flow.

## Logout routes
- Keep existing logout endpoints unchanged in this task:
  - `/logout`, `/merchant/logout`, `/admin/logout`
- No logout consolidation in this task.

---

## 5) Controller ownership and responsibilities

## `User\Auth\LoginController` (shared non-admin owner)
- `showLoginForm()` remains shared page renderer (`public.auth.login`).
- `login()` becomes role-agnostic for non-admin:
  - validates via `UserLoginRequest`
  - calls new shared service method (see section 6)
  - on success, routes by authenticated role using deterministic map
  - on failure, returns current invalid credentials behavior (`email` error; with input)

## `Merchant\Auth\LoginController`
- No route ownership for login after this task.
- Can remain in codebase temporarily (deprecation stage) without being invoked.

## `Admin\Auth\LoginController` (isolated)
- No changes to ownership or behavior.

---

## 6) Service-layer architecture (`AuthService`)

Add one explicit method for shared non-admin login:
- `attemptLoginForNonAdmin(array $credentials): ?\App\Models\User`
  - attempts authentication against email/password using `web` guard
  - if guard attempt fails -> return `null`
  - if authenticated user role is not in `[User::ROLE_USER, User::ROLE_MERCHANT]`:
    - logout immediately
    - return `null`
  - otherwise return authenticated `User`

Keep existing methods:
- `attemptLoginForRole()` stays for admin flow and backward compatibility.
- `logout()`, registration methods unchanged.

Guardrail:
- Do not add route/controller role branching logic that bypasses service role checks.
- Role allowlist for shared login must be enforced in service.

---

## 7) Request-validation ownership

Shared non-admin login request validation owner:
- `App\Http\Requests\User\Auth\UserLoginRequest`

Validation scope for shared login:
- `email`, `password`, `remember` only (and existing conditional reCAPTCHA behavior already present).
- No role field accepted from request.
- No merchant-specific login request for the shared flow.

Merchant login request:
- `MerchantLoginRequest` remains in codebase initially (non-goal to remove unless task-writer decides cleanup task later).
- Must not be used by active login route bindings after this task.

---

## 8) Blade/view ownership

Canonical shared login page:
- `resources/views/public/auth/login.blade.php`

Merchant auth UI implications:
- Any “already have account / login” links under merchant register/reset pages should point to `route('login')`.
- No separate merchant login page should be presented as a distinct active flow.

Admin login view ownership remains:
- `resources/views/admin/auth/login.blade.php`

---

## 9) Registration and password-reset stability plan

Registration:
- Keep user and merchant registration endpoints/controllers/views unchanged.
- Do not merge registration flows in this task.

Password reset:
- Keep user and merchant forgot/reset endpoints/controllers unchanged.
- Only update UI links to shared login if currently pointing to merchant login page directly.
- Do not alter broker usage or token flow in this task.

---

## 10) Failure conditions (hard fail)

Implementation fails if any of the below occur:
1. `/merchant/login` still performs an independent merchant authentication attempt.
2. Admin credentials can authenticate through `/login` and reach non-admin flow.
3. Post-login redirect from `/login` is not deterministic by role.
4. `admin` routes/controllers are merged into shared login or otherwise altered beyond isolation requirements.
5. Registration or reset-password flow behavior is broken or unintentionally refactored.
6. Multiple active non-admin login pages remain exposed as first-class flows.

---

## 11) Non-goals (do not change in this task)

1. No registration-flow unification.
2. No password-reset-flow unification.
3. No guard/provider architecture refactor.
4. No UI redesign beyond minimal navigation/link updates needed for shared login.
5. No removal of legacy controller/request files if not required for route-level behavior change.

---

## 12) Implementation guardrails

1. Prefer route-level compatibility redirect for `/merchant/login` rather than duplicating auth logic.
2. Keep wrong-role failure messaging identical to invalid credentials (no role leakage).
3. Centralize role allowlist in `AuthService` shared method.
4. Keep admin flow isolated and untouched except for regression verification.
5. Ensure tests explicitly cover:
   - user via `/login` -> user dashboard
   - merchant via `/login` -> merchant dashboard
   - admin via `/login` denied
   - admin via `/admin/login` allowed
   - `/merchant/login` redirects to `/login`

