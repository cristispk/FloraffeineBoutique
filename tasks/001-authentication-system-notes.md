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