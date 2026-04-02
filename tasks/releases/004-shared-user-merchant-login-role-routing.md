# Release / Closure — Task 004 Shared User + Merchant Login Role Routing

## Commit message proposal
`feat(auth): unify user+merchant login at /login with role-based redirects`

## Short release summary
This release consolidates non-admin authentication into a single shared login entry at `/login` for both user and merchant accounts, with deterministic redirect-by-role after successful authentication.  
Admin authentication remains isolated under `/admin/login`, and `/merchant/login` is now compatibility redirect-only (GET/POST redirect to `/login`, no direct merchant auth processing).

## Workflow artifacts produced
- Planning: `tasks/planning/004-shared-user-merchant-login-role-routing.md`
- Architecture: `tasks/architecture/004-shared-user-merchant-login-role-routing.md`
- Code review: `tasks/reviews/004-shared-user-merchant-login-role-routing.md` (passed, no must-fix)
- Runtime test report: `tasks/tests/004-shared-user-merchant-login-role-routing.md` (passed)
- Completion documentation: `tasks/done/004-shared-user-merchant-login-role-routing.md`

## Final closure status
- Task is completed and closed based on completed documentation and passing review/test artifacts.
- Active task file removed from `tasks/004-shared-user-merchant-login-role-routing.md` as requested.
- Workflow artifacts retained in place under:
  - `tasks/planning/`
  - `tasks/architecture/`
  - `tasks/reviews/`
  - `tasks/tests/`
  - `tasks/done/`

## Main implementation scope included in release
- Route behavior updates for shared login and merchant compatibility redirect:
  - `routes/web.php`
- Shared non-admin allowlist auth method:
  - `app/Services/Auth/AuthService.php`
- Shared non-admin login ownership and deterministic role redirects:
  - `app/Http/Controllers/User/Auth/LoginController.php`
- Merchant auth CTA/login-link compatibility updates:
  - `resources/views/merchant/auth/register.blade.php`
  - `resources/views/merchant/auth/passwords/forgot.blade.php`
  - `resources/views/merchant/auth/passwords/reset.blade.php`

## Non-blocking follow-up
- Optional cleanup task later: remove deprecated merchant login controller/request artifacts after compatibility period, if product decision confirms full retirement.

