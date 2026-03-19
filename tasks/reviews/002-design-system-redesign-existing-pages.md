# Review — Task 002 (Design System & Redesign Existing Pages)

## Scope checked
- `tasks/002-design-system-redesign-existing-pages.md` (implementer task)
- Implemented files under `resources/views/*`, `resources/css/app.css`, and added `public/img/floraffeine-logo.svg`
- Architecture alignment vs `tasks/architecture/002-design-system-redesign-existing-pages.md`

## Must-fix issues
1. None found.

## Findings (severity-ordered)
### 1) Inline styles / embedded `<style>` (blocking)
- Verified: no embedded `<style>` tags and no `style="..."` attributes remain in `resources/views` after the changes (including the updated pages and layouts).
- Evidence: repo-wide search for `<style` and `style="` under `resources/views` returned no matches.

### 2) reCAPTCHA blocks preserved (blocking)
- Verified: each updated auth page that previously contained reCAPTCHA still has:
  - the widget markup inside `@if(config('recaptcha.enabled'))`
  - the script load inside the same `@if(...)` block
- Files checked:
  - `resources/views/public/auth/login.blade.php`
  - `resources/views/public/auth/register.blade.php`
  - `resources/views/merchant/auth/login.blade.php`
  - `resources/views/merchant/auth/register.blade.php`

### 3) Architecture compliance (blocking)
- `layouts/auth.blade.php`
  - Removed the embedded `<style>` and switched to design-system structure.
  - Uses `<x-ui.card>` and `<x-ui.alert>` for status + error summary.
  - No controller/business-logic changes.
- Design-system components
  - All implemented UI primitives exist under `resources/views/components/ui/` and are reusable.
  - Pages use `x-ui.*` components for inputs/buttons and error rendering.
- CSS centralization
  - All `ds-*` styling is centralized in `resources/css/app.css`.
  - Legacy alias classes for password reset pages are present in `resources/css/app.css`.

### 4) Component API correctness (non-blocking, quick checks)
- `<x-ui.input>` / `<x-ui.form-error>`
  - Input error ids follow the required scheme `error-{$name}` and match the error component.
  - `aria-invalid` and `aria-describedby` are applied only when the field has an error.
- `<x-ui.button-primary>` / `<x-ui.button-secondary>`
  - Correctly render `<button>` by default, with optional `<a href="...">` when `href` is provided.

## Optional improvements (not blockers)
1. Brand text / “no English user-facing text” ambiguity
   - `resources/views/layouts/app.blade.php` footer includes “Floraffeine Boutique”.
   - `resources/views/components/ui/logo.blade.php` default `alt` includes “Floraffeine Boutique”.
   - If the project treats brand names as “English user-facing text”, consider localizing these strings (or confirm with the glossary/brand rules).
2. Header navigation extensibility
   - `layouts/app.blade.php` defines `@hasSection('header_nav')` for navigation, but current redesigned pages don’t provide it.
   - Not required for Task 002, but if future tasks need it, ensure a consistent slot API is used.
3. Card header slot
   - `resources/views/components/ui/card.blade.php` supports a `header` slot via `$header`, but no current pages pass it.
   - Works for the current scope; just ensure future usage passes the slot the intended way.

## Recommended next step before testing
1. Run the manual smoke tests defined in Task 002:
   - user login/register
   - merchant login/register
   - admin login
2. Specifically verify error states:
   - trigger validation errors and confirm field-level errors render under inputs
   - confirm global errors render via the layout using `<x-ui.alert variant="error">`

