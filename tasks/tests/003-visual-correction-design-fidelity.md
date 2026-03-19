# Test Report — Task 003 (Visual Correction, Strict Fidelity)

## Testing Scope
- Verified the implemented UI changes for Task 003 across:
  - `GET /login`, `/register`
  - `GET /merchant/login`, `/merchant/register`
  - `GET /admin/login`
  - `GET /dashboard`, `/merchant/dashboard`, `/admin/dashboard` (note: may render login form when unauthenticated)
- Verified required auth DOM/class structure and strict CSS selector mappings using:
  - HTML assertions on HTTP responses
  - POST-based validation-error rendering (CSRF/session persistence)
  - repository-side checks for inline styles (`style="..."` / `<style>`)

## Tooling / Measurement Limitations (important)
- Browser automation/runtime computed-style measurement (pixel height/padding) was not available in this environment.
- Therefore, “visual testing” is done via strict DOM/class verification + CSS selector correctness.

## 1. Functional Testing

### 1.1 Render checks (HTTP)
All targeted pages returned HTTP `200`:
- `/`, `/login`, `/register`
- `/merchant/login`, `/merchant/register`
- `/admin/login`
- `/dashboard`, `/merchant/dashboard`, `/admin/dashboard`

### 1.2 Auth DOM chain + required classes (structural validation)
For each auth page above, the HTML response contained:
- `class="ai-form-section"`
- `class="ai-form"`
- `class="form-card"`
- `class="form-grid"`
- repeated `class="field"`
- labels with `class="label"`
- text inputs with `class="input"`
- remember checkbox input:
  - confirmed that the `name="remember"` checkbox does **not** include `class="input"`

### 1.3 Validation errors display correctly (POST smoke tests)
POST tests (using GET for CSRF token + WebRequestSession for cookies):
- `POST /login` with empty `email` + empty `password`
  - response HTML contained `id="error-email"` and `id="error-password"`
  - confirmed no reCAPTCHA-related messaging in HTML (`g-recaptcha` / `g-recaptcha-response` patterns absent)
- `POST /register` with empty required fields
  - response HTML contained `id="error-name"`, `id="error-email"`, `id="error-password"`
  - confirmed no reCAPTCHA-related messaging in HTML

### 1.4 `session('status')` messages
- The auth layout includes conditional rendering of `session('status')` inside the form card.
- However, no deterministic endpoint was executed in this test run that reliably populates `session('status')`, so this part was not functionally triggered.
- Code-level presence was verified in `resources/views/layouts/auth.blade.php`.

### 1.5 reCAPTCHA rendering when enabled
- In this runtime environment, reCAPTCHA widget is **not present** in HTML for:
  - `/login`
  - `/register`
  - `/merchant/login`
  - `/merchant/register`
  - `/admin/login`
- This matches `config/recaptcha.php` behavior:
  - `enabled` is controlled by `env('RECAPTCHA_ENABLED', false)`
- Because `RECAPTCHA_ENABLED` was effectively false in this environment, the “when enabled” visual presence could not be validated here.

## 2. Visual Testing (Critical)

### 2.1 Previous must-fix resolution: `.brand img` selector
Must-fix from review was applied:
- `resources/css/app.css` now contains only:
  - `.brand img { height: 52px; width: auto; }`
- Removed extra property (no additional properties beyond those required by source-of-truth block for `.brand img`).

Result: the original selector/value mismatch is resolved.

### 2.2 Logo size/placement
Findings:
- The global header/logo wrapper uses `.brand` in `resources/views/layouts/app.blade.php`.
- Auth pages (`/login`, `/register`, `/merchant/*/login|register`, `/admin/login`) render `layouts/auth.blade.php` only and do **not** output any `.brand` / logo SVG in their HTML response.

Therefore:
- No “oversized logo” could be observed on auth pages because the logo is not rendered there.
- However, if the source-of-truth expects the header/logo on auth pages, this is a visual fidelity gap.

### 2.3 Auth card proportions + alignment (CSS selector correctness)
The copied parent selectors required by Task 003 exist in `resources/css/app.css`:
- `.ai-form-section`
- `.ai-form`
- `.ai-form .form-card`
- `.ai-form .form-grid`
- `.ai-form .label`
- `.ai-form .input`
- `.ai-form .input:focus, .ai-form .input:focus-visible`
- `.ai-form .textarea`
- responsive media rule at `max-width: 768px` switching `.ai-form .form-grid` to `grid-template-columns: 1fr`

Combined with the confirmed DOM chain in HTML, card/form spacing rhythm should follow the source-of-truth selectors.

### 2.4 Button styles match required class combinations
Verified in HTML responses:
- primary action buttons include the exact combination:
  - `btn btn-dark rounded-pill px-4`
- secondary buttons in dashboard templates (logout) use:
  - `btn btn-outline-dark rounded-pill px-4`

### 2.5 No inline styles / no layout hacks in modified Blade files
Confirmed via ripgrep across modified layout/auth/templates:
- no occurrences of `style="..."`
- no `<style>` blocks

## Issues Found

### Must-fix
1. Auth pages do not include the `.brand` / logo SVG markup:
   - HTML checks show `class="brand"` and logo SVG are absent on `/login`, `/register`, `/merchant/*`, `/admin/login`.
   - If strict design fidelity requires the header/logo to be visible on auth pages (as in the parent source patterns), this is currently a mismatch.

### Optional improvements
1. `session('status')` not functionally triggered in this run.
   - Not a spec breach; just a test gap.
2. reCAPTCHA “enabled” state not testable here due to environment `RECAPTCHA_ENABLED` being false.

