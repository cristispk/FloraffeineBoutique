# Done — Task 004 Shared User + Merchant Login Role Routing

## What was implemented
Task 004 delivered a single shared non-admin login entry for `user` and `merchant` at `/login`, with deterministic post-login routing by authenticated role, while keeping admin authentication isolated under `/admin/login`.

The implementation followed the defined Laravel + Blade scope and updated only the planned files for routes, auth service/controller ownership, and merchant auth link targets.

## Shared non-admin login behavior
- `/login` is now the canonical non-admin login endpoint for both user and merchant accounts.
- Successful login redirects are deterministic:
  - `user` -> `user.dashboard`
  - `merchant` -> `merchant.dashboard`
- Admin credentials submitted on `/login` are treated as invalid credentials (no role detail leakage), matching required indistinguishable failure behavior.

## Admin isolation preserved
- `/admin/login` remains owned by `Admin\Auth\LoginController`.
- Admin login behavior/responsibility remains separate from shared non-admin login.
- Non-admin credentials on admin login continue to fail as invalid credentials.

## `/merchant/login` compatibility behavior
- `/merchant/login` remains available as a compatibility alias only:
  - `GET /merchant/login` redirects to `/login`
  - `POST /merchant/login` redirects to `/login`
- No merchant authentication is executed directly on `/merchant/login`.

## Service/controller responsibility changes (high level)
- `AuthService` now contains shared non-admin login enforcement via `attemptLoginForNonAdmin(...)`.
  - Central allowlist check for `user` and `merchant` roles.
  - Immediate logout + failure for non-allowlisted roles (including admin).
- `User\Auth\LoginController` is the shared non-admin login owner.
  - Uses existing `UserLoginRequest`.
  - Delegates role allowlist enforcement to `AuthService`.
  - Handles deterministic role-based redirect mapping after successful login.

## Registration and password reset continuity
- User and merchant registration flows were preserved (not unified).
- User and merchant forgot/reset flows were preserved (not unified).
- Merchant auth-related login CTA links (register/forgot/reset views) were updated to point to shared `route('login')` for consistency.

## Quality gates status
- **Review:** Passed (`tasks/reviews/004-shared-user-merchant-login-role-routing.md`) with no must-fix items.
- **Runtime test:** Passed (`tasks/tests/004-shared-user-merchant-login-role-routing.md`) across shared login, admin isolation, merchant compatibility redirects, role protection, registration/reset continuity, and reCAPTCHA enabled/disabled behavior.

## Main updated files
- `routes/web.php`
- `app/Services/Auth/AuthService.php`
- `app/Http/Controllers/User/Auth/LoginController.php`
- `resources/views/merchant/auth/register.blade.php`
- `resources/views/merchant/auth/passwords/forgot.blade.php`
- `resources/views/merchant/auth/passwords/reset.blade.php`

## Optional follow-up
- No blocking follow-up required for this task scope.
- Optional later cleanup: remove deprecated merchant login controller/request artifacts in a dedicated cleanup task once compatibility period ends.

