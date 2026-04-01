# Review — Task 004 Shared User+Merchant Login Role Routing

## Scope reviewed
- `tasks/004-shared-user-merchant-login-role-routing.md`
- `tasks/architecture/004-shared-user-merchant-login-role-routing.md`
- Implemented code in:
  - `routes/web.php`
  - `app/Services/Auth/AuthService.php`
  - `app/Http/Controllers/User/Auth/LoginController.php`
  - `resources/views/merchant/auth/register.blade.php`
  - `resources/views/merchant/auth/passwords/forgot.blade.php`
  - `resources/views/merchant/auth/passwords/reset.blade.php`

---

## 1) Structural / Architectural compliance

- **Allowed-file scope**: Implementation changes are aligned with the exact file list defined by task/architecture scope.
- **Shared non-admin route ownership**: `GET /login` and `POST /login` remain owned by `User\Auth\LoginController` (`login` route name preserved).
- **Merchant compatibility alias**: `GET /merchant/login` and `POST /merchant/login` are redirect-only to `route('login')`; no auth handling on merchant login endpoint.
- **No active merchant login ownership**: No active login route points to `Merchant\Auth\LoginController@showLoginForm` or `@login`.
- **Admin isolation unchanged**: `/admin/login` routes remain bound to `Admin\Auth\LoginController` with unchanged ownership.
- **Service architecture requirement met**: `AuthService` contains `attemptLoginForNonAdmin(array $credentials): ?User` and enforces allowlist (`user`, `merchant`) centrally with immediate logout for non-allowlisted roles.
- **Shared login owner requirement met**: `User\Auth\LoginController` is the sole owner of shared non-admin login behavior.
- **No out-of-scope refactor observed**: No additional controller/request/service ownership shifts beyond task scope.

---

## 2) Controller & Service correctness

- **Controller uses new service method**: `User\Auth\LoginController@login` calls `AuthService::attemptLoginForNonAdmin(...)`.
- **No role from request input**: Role is not accepted or read from payload; behavior is derived from authenticated user role only.
- **Failure behavior remains invalid-credentials style**: Failures return same `email` error key/message and same `withInput($request->only('email'))` pattern.
- **No role leakage**: Unexpected/non-allowlisted role path logs out and returns the same invalid-credentials response shape.
- **Deterministic redirect in controller**: Redirect mapping is explicit in controller (`merchant` -> `merchant.dashboard`, `user` -> `user.dashboard`) and not incorrectly delegated.

---

## 3) Blade / route reference correctness

- **Merchant auth link targets updated where required**:
  - `resources/views/merchant/auth/register.blade.php`
  - `resources/views/merchant/auth/passwords/forgot.blade.php`
  - `resources/views/merchant/auth/passwords/reset.blade.php`
- **Link strategy compliance**: Merchant auth CTAs now point to `route('login')` as required.
- **No unintended view changes detected in reviewed scope**: Changes are limited to expected merchant auth login-CTA targets.

---

## Must-fix issues
- None.

## Optional improvements
- None required for this task scope.

---

## Verdict
Implementation is compliant with Task 004 and its architecture document at code level for structural ownership, route behavior, service/controller responsibility split, and merchant auth link updates.

