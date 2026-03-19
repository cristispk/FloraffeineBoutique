# Architecture — Task 003 (Visual Correction: Design Fidelity)

## Goal
Correct the visual fidelity of the current design-system implementation so that the visible UI matches the parent Floraffeine source-of-truth **exactly**, using only:
- `docs/design-source-of-truth/website-parent/css/style.css`
- `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
- relevant DOM structure/patterns from `docs/design-source-of-truth/website-parent/index.html` and other parent pages (notably `buchet-ai.html`)

This task is **UI-only**: no business logic changes, and no route/controller changes.

## Exact files to update (create/update)

### 1) Styling
1. `resources/css/app.css`
   - Add **exact** CSS rules by copying the relevant selectors + values from parent `style.css`
   - Ensure these rules take precedence over existing Tailwind/preflight and current `ds-*` styles
   - Remove or neutralize any current `ds-*` rules that override the copied parent rules for the affected UI pieces

### 2) Layouts
1. `resources/views/layouts/app.blade.php`
   - Fix logo sizing/placement to use parent `.brand` + `.brand img` rules
   - Remove/stop using `ds-logo`-driven sizing
2. `resources/views/layouts/auth.blade.php`
   - Rebuild the auth card/form wrapper DOM to match the parent `.ai-form-section` → `.ai-form` → `.form-card` → `.form-grid` structure
   - Render status/errors inside `.form-card` so that parent typography/spacing applies

### 3) Blade UI components (markup must match parent selectors)
Update these components so they emit **the same classnames** that parent `style.css` targets:
1. `resources/views/components/ui/logo.blade.php`
2. `resources/views/components/ui/input.blade.php`
3. `resources/views/components/ui/button-primary.blade.php`
4. `resources/views/components/ui/button-secondary.blade.php`
5. `resources/views/components/ui/card.blade.php`
6. `resources/views/components/ui/alert.blade.php` (optional only if alert styling conflicts; prefer using plain markup that inherits parent `.label`/base typography)
7. `resources/views/components/ui/form-error.blade.php` (optional: must not introduce a new palette not present in parent)

### 4) Target pages (markup only)
Update the following pages to match the required DOM/class structure for parent CSS targeting:
1. `resources/views/welcome.blade.php`
2. `resources/views/public/auth/login.blade.php`
3. `resources/views/public/auth/register.blade.php`
4. `resources/views/merchant/auth/login.blade.php`
5. `resources/views/merchant/auth/register.blade.php`
6. `resources/views/admin/auth/login.blade.php`
7. `resources/views/public/dashboard/index.blade.php`
8. `resources/views/merchant/dashboard/index.blade.php`
9. `resources/views/admin/dashboard/index.blade.php`

## Strict visual-token mapping (copy selectors/values exactly)
Implementer must **copy** the following selectors from parent `docs/design-source-of-truth/website-parent/css/style.css` into `resources/css/app.css` with the same selector names and the same values.

### A) Fonts and palette (no invented tokens)
Copy the parent `:root` variable definitions used by the selectors in this task, including:
- `--ff-sans`, `--ff-serif`, and any other vars referenced by the rules below
- `--color-espresso`, `--color-brown`, `--color-ink`, `--color-accent`
- `--glass-border`, `--shadow-soft`, `--shadow-elev`, `--cta-shadow`

Then copy the parent base rules:
- `html, body { background: #ffffff; color: var(--color-brown); font-family: var(--ff-sans); -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }`
- `body { --color-text: #fff; --color-bg: #000; --color-link: #fff; --color-link-hover: #fff; --color-frame: #ff5ba4; color: var(--color-text); background-color: var(--color-bg); font-family: var(--ff-sans); ... }`

**Fail condition**: do not invent `--ds-color-*` values; your app must use the parent `--color-*` and `--ff-*` variables for any visible color/typography.

### B) Logo sizing and placement (must match exactly)
From parent CSS, copy:
1. `.brand`:
   - `display: inline-flex; align-items: center; gap: 10px; text-decoration: none;`
2. `.brand img`:
   - `height: 52px; width: auto; display: block;`

Markup requirement:
- The logo `<img>` must be a descendant of an element with `class="brand"`.

**Fail conditions**
- If the logo `<img>` is not inside a `.brand` element, fail.
- If the computed logo image height is not `52px`, fail.
- If `ds-logo` (or any other app-specific logo sizing) is still affecting the `<img>`, fail.

### C) Auth card + form proportions (parent `.ai-form` system)
Copy the following parent selectors exactly:
1. `.ai-form-section`
   - `padding: 24px 0 80px;`
2. `.ai-form`
   - `max-width: 1080px; margin: 0 auto; padding: 0 10px;`
3. `.ai-form .form-card`
   - `background: #fff; border: 1px solid var(--glass-border); border-radius: 18px; box-shadow: var(--shadow-soft); padding: 24px;`
4. `.ai-form .form-grid`
   - `display: grid; grid-template-columns: 1fr 1fr; gap: 20px;`
5. `.ai-form .field--full`
   - `grid-column: 1 / -1;`
6. `.ai-form .label`
   - `display: block; font-weight: 600; color: var(--color-espresso); margin-bottom: 8px; font-family: var(--ff-serif);`
7. `.ai-form .input`
   - `width: 100%; border: 1px solid var(--glass-border); border-radius: 12px; padding: 12px 14px; background: #fff; color: var(--color-espresso); transition: border-color 160ms ease, box-shadow 160ms ease;`
8. `.ai-form .input:focus, .ai-form .input:focus-visible`
   - `outline: none; border-color: rgba(47, 35, 31, 0.55); box-shadow: 0 0 0 2px rgba(47, 35, 31, 0.08);`
9. `.ai-form .textarea` (copy even if not used; ensures consistency if introduced)
   - `min-height: 110px; resize: vertical;`
10. Responsive rule:
   - `@media (max-width: 768px) { .ai-form .form-grid { grid-template-columns: 1fr; } }`
11. `.ai-form .form-actions`
   - `display:flex; gap:10px; justify-content:center; margin-top:8px;`

**Markup requirement for auth pages**
- The DOM must contain (in this order):
  - a wrapper: `.ai-form-section`
  - inside it: `.ai-form`
  - inside it: `.form-card`
  - inside it: `.form-grid`
  - each field wrapper must be `.field`
  - each label must be `<label class="label" ...>`
  - each text/password input must have `class="input"`

**Remember checkbox exception (explicit)**
- Parent `.ai-form .input` is designed for text inputs; applying it to checkbox will break proportions.
- For `remember` checkbox only:
  - do not apply `class="input"` to the checkbox input
  - still place it inside the `.field` grid and keep the label as `.label` for typography + spacing rhythm

**Fail conditions**
- If the auth page DOM does not include `.ai-form-section > .ai-form > .form-card > .form-grid`, fail.
- If any email/password input does not have `class="input"`, fail.
- If labels are not `class="label"`, fail.
- If the auth card does not have `form-card` class (directly or via the card component), fail.
- If the responsive behavior at `max-width: 768px` is not observed (grid switches 2→1 columns), fail.

### D) Buttons (parent `.btn` typography + `.btn-*` colors)
Copy these parent selectors exactly:
1. `.btn, a.btn`:
   - `font-family: var(--ff-serif); letter-spacing: 0.015em; font-weight: 600;`
2. `.btn-dark, .btn-theme`:
   - `color:#fff; background: rgba(47,35,31,0.92); border-color: rgba(47,35,31,0.98); box-shadow: 0 2px 0 rgba(255,255,255,0.06) inset; transition: ...;`
3. `.btn-dark:hover, .btn-theme:hover`
   - background, transform, and `box-shadow: var(--cta-shadow)`
4. `.btn-dark:focus-visible, .btn-theme:focus-visible`
   - exact focus box-shadow values
5. `.btn-outline-dark, .btn-theme--outline`
   - `color: var(--color-espresso); background: transparent; border-color: rgba(47,35,31,0.7); transition: ...;`
6. `.btn-outline-dark:hover, .btn-theme--outline:hover`
   - `background: #f7f2eb; transform: translateY(-2px); box-shadow: var(--cta-shadow);`
7. `.btn-outline-dark:focus-visible, .btn-theme--outline:focus-visible`
   - exact focus box-shadow values

Bootstrap class fidelity (required)
- The parent HTML uses `rounded-pill` and `px-3/px-4` on buttons (from Bootstrap utilities).
- For exact fidelity, implementer must ensure those utility classes exist in our app.

Implementation rule:
- Either import Bootstrap 5.3.3 CSS in `layouts/app` and `layouts/auth` (same CDN URL as parent), OR copy the exact Bootstrap definitions for `rounded-pill`, `px-3`, `px-4`, and `btn` into `resources/css/app.css`.

**Fail conditions**
- If primary/secondary buttons do not include the exact class combinations as the parent pattern:
  - primary: `btn btn-dark rounded-pill px-4` (for submit)
  - secondary/action-back: `btn btn-outline-dark rounded-pill px-4`
  fail.
- If the computed background color on button hover does not match the parent hover rgba values, fail.

## What must be corrected in the current implementation (non-negotiable)
Based on the current implementation and the corrective intent, the implementer must:
1. Remove the current ds-driven auth card and replace it with parent `.ai-form-section/.ai-form/.form-card` structure.
2. Update `x-ui.input` to emit `class="input"` (for text/password/email) rather than `ds-input`.
3. Update `x-ui.button-primary` and `x-ui.button-secondary` to emit the parent `.btn ...` class combos (not `ds-btn-*`).
4. Fix the logo: ensure it is inside a `.brand` element so `.brand img` controls size (`52px` height).
5. Remove/neutralize ds-* rules that still override parent-computed values for the auth and welcome pages.

## Layout hierarchy and responsive behavior
### Auth pages
- Layout must provide:
  - `.ai-form-section` wrapper (sets vertical padding)
  - `.ai-form` wrapper (sets max width + horizontal padding)
  - `.form-card` wrapper (sets card border-radius/padding/shadow)
  - Inside yield content must render `.form-grid` and `.form-actions` where applicable
- Responsive:
  - `.ai-form .form-grid` must switch to single column under `max-width: 768px`

### Welcome/dashboard
- If welcome uses a card/layout pattern, it should reuse the same parent font/palette values, and logo rules must remain exact.
- Dashboard placeholders should use the same auth button styling primitives (`btn btn-outline-dark rounded-pill px-4` for logout) so hierarchy remains consistent.

## Visual fidelity fail conditions (cannot approximate)
The implementer must verify with browser devtools computed styles for at least the following and must fail the task if any mismatch exists:
1. Logo height computed value equals `52px` from `.brand img`
2. Auth card computed:
   - `border-radius` equals `18px`
   - `padding` equals `24px`
   - `box-shadow` equals `var(--shadow-soft)` (resolved value match)
3. Input computed:
   - border-radius equals `12px`
   - padding equals `12px 14px`
   - focus box-shadow equals `0 0 0 2px rgba(47, 35, 31, 0.08)`
4. Label computed:
   - margin-bottom equals `8px`
   - font-family equals `var(--ff-serif)` resolved
5. Button computed:
   - background equals `rgba(47, 35, 31, 0.92)` in normal state for primary
   - background equals `rgba(58, 44, 39, 0.98)` on hover for primary
6. Responsive grid:
   - at viewport width <= 768px, auth field grid becomes 1 column
7. Hard ban:
   - no inline `style="..."` attributes in the modified Blade templates
   - no embedded `<style>` blocks in Blade templates

## Recommended execution order for the implementer
1. Extract fidelity selectors:
   - Ensure the exact CSS selectors listed above are present verbatim in `resources/css/app.css`
2. Fix logo markup and logo component output:
   - Update `layouts/app.blade.php` + `x-ui.logo.blade.php`
3. Rebuild auth layout DOM:
   - Update `layouts/auth.blade.php` to emit `.ai-form-section/.ai-form/.form-card`
4. Update UI components to emit parent classnames:
   - `x-ui.input`, `x-ui.button-*`, `x-ui.card`
5. Update auth/dashboard/welcome pages:
   - Align wrappers `.form-grid/.field/.label/.input/.form-actions`
6. Fidelity verification:
   - Use computed style checks against the exact parent values

