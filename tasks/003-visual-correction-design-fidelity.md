# Task 003 — Visual Correction (Strict Design Fidelity)

## Purpose
Correct the current design-system visual implementation so the visible UI matches the parent Floraffeine design source-of-truth with **strict fidelity** (no approximation).

This task is **UI-only**:
- Laravel + Blade only
- no business logic changes
- no route/controller/service changes (unless strictly required to render the same UI structure)

## Source of truth (DO NOT deviate)
You must match these assets exactly:
1. CSS: `docs/design-source-of-truth/website-parent/css/style.css`
2. Logo SVG: `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
3. HTML structure/patterns:
   - `docs/design-source-of-truth/website-parent/index.html` (header/logo/footer patterns)
   - `docs/design-source-of-truth/website-parent/buchet-ai.html` (auth-form structure using `.ai-form*`)

## What is currently wrong (explicit targets)
You must address these visible issues in the implementation:
1. Oversized logo
2. Poor form formatting
3. Weak spacing rhythm
4. Weak visual hierarchy
5. Design not matching the Floraffeine source-of-truth

## Hard non-negotiable constraints
1. No invented palette:
   - You may only use palette/typography values coming from parent CSS variables/selectors.
2. No approximation:
   - You must copy selector rules and values exactly for the selectors listed below.
3. No one-off inline styles:
   - no `style="..."` in any Blade files you modify
   - no embedded `<style>...</style>` in any Blade files you modify
4. UI-only scope:
   - do not change backend/business logic
   - you may adjust Blade markup/component output to meet DOM/class requirements

## Exact files to update

### Styling
1. `resources/css/app.css`

### Layouts
1. `resources/views/layouts/app.blade.php`
2. `resources/views/layouts/auth.blade.php`

### Blade UI components (markup output must match parent selectors)
1. `resources/views/components/ui/logo.blade.php`
2. `resources/views/components/ui/input.blade.php`
3. `resources/views/components/ui/button-primary.blade.php`
4. `resources/views/components/ui/button-secondary.blade.php`
5. `resources/views/components/ui/card.blade.php`
6. (If needed to keep hierarchy correct) `resources/views/components/ui/alert.blade.php`
7. (If needed) `resources/views/components/ui/form-error.blade.php`

### Target pages (markup only; no logic changes)
1. `resources/views/welcome.blade.php`
2. `resources/views/public/auth/login.blade.php`
3. `resources/views/public/auth/register.blade.php`
4. `resources/views/merchant/auth/login.blade.php`
5. `resources/views/merchant/auth/register.blade.php`
6. `resources/views/admin/auth/login.blade.php`
7. `resources/views/public/dashboard/index.blade.php`
8. `resources/views/merchant/dashboard/index.blade.php`
9. `resources/views/admin/dashboard/index.blade.php`

## Strict visual-token mapping (exact selectors/values to copy)
### A) Fonts + palette variables (no approximation)
In `resources/css/app.css`, copy (verbatim) the relevant parent variables used by selectors in this task:
- `--ff-sans`, `--ff-serif`
- `--color-espresso`, `--color-brown`, `--color-ink`, `--color-accent`
- `--glass-border`, `--shadow-soft`, `--shadow-elev`, `--cta-shadow`

Also copy the parent base rules needed for these selectors:
- `html, body` and `body` font + background + text color rules as used by those selectors.

**Fail condition**: if you add new color values for these UI pieces that are not taken from parent selectors/variables, fail.

### B) Logo sizing + placement (must match exactly)
In `resources/css/app.css`, copy exactly:
1. `.brand`
2. `.brand img`

DOM requirement (must pass):
- The logo `<img>` must be inside an element with `class="brand"` (direct or indirect descendant).

Computed visual fail conditions (must pass):
1. computed logo `<img>` height must resolve to `52px`
2. `ds-logo`-based sizing must not affect the logo (if it does, fail)
3. logo placement must follow parent header/logo container structure (no centered card logo hacks)

### C) Auth form card/form proportions + spacing rhythm (must match parent `.ai-form`)
In `resources/css/app.css`, copy exactly the following parent selectors:
1. `.ai-form-section`
2. `.ai-form`
3. `.ai-form .form-card`
4. `.ai-form .form-grid`
5. `.ai-form .field--full`
6. `.ai-form .label`
7. `.ai-form .input`
8. `.ai-form .input:focus, .ai-form .input:focus-visible`
9. `.ai-form .textarea` (copy anyway for correctness)
10. `@media (max-width: 768px) { .ai-form .form-grid { grid-template-columns: 1fr; } }`
11. `.ai-form .form-actions`

DOM structure requirement (hard fail if not exact):
Within each auth page, the DOM must contain this chain:
1. `.ai-form-section`
   > `.ai-form`
   > `.form-card`
   > `.form-grid`
   > (repeat) `.field` elements containing:
   - `<label class="label">...`
   - `<input class="input" ...>` for text/email/password inputs

Remember checkbox exception (explicit):
- The remember checkbox input must NOT have `class="input"`.
- It must remain visually consistent using the parent layout (grid rhythm + label typography).

Spacing rhythm fail conditions (hard):
1. If email/password (or other text fields) inputs do not have `class="input"`, fail.
2. If labels do not have `class="label"`, fail.
3. If `.ai-form .form-grid` does not exist and does not switch to 1 column at `max-width: 768px`, fail.

### D) Button/input/card appearance (must match parent `.btn`)
In `resources/css/app.css`, copy exactly:
1. `.btn, a.btn` typography rules
2. `.btn-dark, .btn-theme` base rules
3. `.btn-dark:hover, .btn-theme:hover`
4. `.btn-dark:focus-visible, .btn-theme:focus-visible`
5. `.btn-outline-dark, .btn-theme--outline`
6. `.btn-outline-dark:hover, .btn-theme--outline:hover`
7. `.btn-outline-dark:focus-visible, .btn-theme--outline:focus-visible`

Bootstrap fidelity requirement:
- The parent uses Bootstrap utilities like `rounded-pill` and `px-4`.
- For strict fidelity, you MUST ensure these utilities exist in the app UI:
  - Either import Bootstrap 5.3.3 CSS in `layouts/app.blade.php` and `layouts/auth.blade.php` using the same CDN tag style as the parent
  - Or copy the exact utility definitions needed for:
    - `rounded-pill`
    - `px-3`, `px-4`

Button class mapping requirement (hard):
1. Primary action buttons must include `btn btn-dark rounded-pill px-4`
2. Secondary/logout/back buttons must include `btn btn-outline-dark rounded-pill px-4`

Fail condition:
- If buttons don’t have these exact class combinations, fail.

## Required markup logic for implementer (no backend changes)

### Step 1 — Rebuild `auth` layout DOM to match `.ai-form*`
Update `resources/views/layouts/auth.blade.php` so it outputs:
- `.ai-form-section` wrapper
- `.ai-form` wrapper (must be direct descendant)
- `.form-card` wrapper inside `.ai-form`
- `.form-grid` wrapper inside `.form-card`

Status/errors:
- Render `session('status')` and `$errors->any()` inside `.form-card` at the top (so typography/spacing comes from parent card container).
- Do not invent new classnames for errors; if you need styling, use parent variables/selectors only.

### Step 2 — Update auth pages to provide correct field markup and action buttons
In each of the 6 auth pages listed in “Target pages”, update markup so:
- each field wrapper uses `class="field"`
- each label uses `class="label"`
- each text/email/password input uses `class="input"`
- the remember checkbox input does NOT use `class="input"`
- buttons are inside a `.form-actions` wrapper and use the mapped `.btn ... px-4 rounded-pill` classes

reCAPTCHA:
- Do not move or remove reCAPTCHA blocks from their current pages.
- reCAPTCHA widget and script must remain within the actual auth `<form>` element.

### Step 3 — Update Blade components to emit parent-class markup
Update the components listed so they emit:
1. `x-ui.logo`:
   - renders `<img>` inside a `.brand` wrapper (or output that can be wrapped by `.brand` in `layouts/app`)
2. `x-ui.input`:
   - for `type=text|email|password`: emits `class="input"`
   - for `type=checkbox`: emits a plain checkbox input (no `class="input"`)
3. `x-ui.button-primary`:
   - emits `class="btn btn-dark rounded-pill px-4"`
4. `x-ui.button-secondary`:
   - emits `class="btn btn-outline-dark rounded-pill px-4"`
5. `x-ui.card`:
   - for auth usage, emit `.form-card` semantics (or stop using the card component if it cannot map exactly)

### Step 4 — Re-align `layouts/app` logo placement
Update `resources/views/layouts/app.blade.php` to match parent logo structure:
- `<a class="brand"...><img .../></a>`
- ensure the logo `<img>` gets the `.brand img` sizing rule

### Step 5 — Strict fidelity verification (non-negotiable)
Before finishing the task:
1. Verify computed styles (browser devtools):
   - logo image height == `52px`
   - auth card border-radius == `18px`
   - input border-radius == `12px` and padding == `12px 14px`
   - focus box-shadow matches parent selector
   - label margin-bottom == `8px`
   - responsive behavior: grid changes to `1fr` at <=768px
2. Verify DOM structure exists:
   - `.ai-form-section > .ai-form > .form-card > .form-grid` chain exists on each auth page
3. Verify class mapping exists:
   - every submit/logout button has required `btn ... rounded-pill px-4` classes

Hard visual fail conditions:
- any mismatch from required DOM structure or computed style values → fail.
- any inline styles or `<style>` blocks in modified Blade files → fail.
- any palette value not copied from parent selectors/variables for the affected UI → fail.

## Acceptance criteria (visual fidelity only)
1. Logo:
   - Logo size/placement matches `.brand img` rules (height = 52px) with no ds-* sizing interference.
2. Auth forms:
   - DOM structure exactly matches the `.ai-form-section > .ai-form > .form-card > .form-grid` requirement.
   - Inputs/labels use exact parent classes (`input`, `label`) with identical computed radius/padding/focus styles.
   - Spacing rhythm matches via `.form-grid` gaps and parent label/input sizing.
3. Buttons:
   - Submit and logout/back buttons use exact parent `.btn ...` variants with Bootstrap utility classes present.
4. No disallowed styling:
   - no `<style>` tags and no `style="..."` attributes in modified Blade files.
5. reCAPTCHA:
   - Widget + script blocks still appear within login/register forms and remain conditional by `config('recaptcha.enabled')`.

## Recommended execution order
1. Copy required parent selectors/values into `resources/css/app.css` (no ds overrides for auth)
2. Fix `x-ui.logo` + `layouts/app` markup to satisfy `.brand` and `.brand img` rules
3. Rebuild `layouts/auth` DOM to match `.ai-form*` wrappers
4. Update auth pages markup to `.field/.label/.input` and `.form-actions` + correct button class combinations
5. Update card/input/button components accordingly and remove any remaining ds-* dependencies for these pieces
6. Perform strict computed-style verification checks

