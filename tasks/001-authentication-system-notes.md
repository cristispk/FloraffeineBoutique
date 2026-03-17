# Task 001 — Implementation Notes

## CTO Clarifications

1. Public auth route names should remain simple:
- register
- login
- logout

Instead of generic names like:
- auth.register
- auth.login
- auth.logout

2. Avoid generic root DashboardController naming.
Prefer a clearly scoped dashboard controller, for example:
- App\Http\Controllers\User\DashboardController
- App\Http\Controllers\Merchant\DashboardController
- App\Http\Controllers\Admin\DashboardController

3. Keep password reset requests shared between user and merchant where possible:
- ForgotPasswordRequest
- ResetPasswordRequest

Do not duplicate request classes unnecessarily for MVP.

4. Wrong-role login attempts must fail as invalid credentials.
Do NOT authenticate the user and then redirect to their own area.

5. RoleMiddleware should only validate role.
Authentication itself should remain the responsibility of Laravel auth middleware.

6. AdminUserSeeder is mandatory in Task 001.
It is not optional.

---

## Implementation Summary (Completed)

- Implemented a single `users` table with a `role` column (`user`, `merchant`, `admin`) and role helpers on the `User` model.
- Implemented a generic `role` middleware, registered as `role`, that:
  - relies on `auth` middleware for authentication
  - validates the current user's role
  - redirects authenticated users to their own dashboard when accessing the wrong area.
- Implemented `AuthService` that owns:
  - `registerUser()` and `registerMerchant()` (using the model's `password` cast for hashing)
  - `attemptLoginForRole()` (wrong-role login attempts are treated as invalid credentials and log out the user)
  - `logout()`.
- Implemented thin controllers and Blade-only views for:
  - user auth (`/register`, `/login`, `/logout`, password reset) and `/dashboard`
  - merchant auth (`/merchant/register`, `/merchant/login`, `/merchant/logout`, password reset) and `/merchant/dashboard`
  - admin auth (`/admin/login`, `/admin/logout`) and `/admin/dashboard`.
- Implemented shared `ForgotPasswordRequest` and `ResetPasswordRequest` used by both user and merchant password reset flows.
- Added `AdminUserSeeder` and wired it from `DatabaseSeeder` to create a default admin (`admin@example.com`) with role `admin`.

Task 001 — Authentication System is now implemented and considered complete.


### Localization

All authentication and dashboard views were translated to Romanian in accordance with the global Language & UI Text Rules defined in ARCHITECTURE.md.

No functional or structural changes were made.