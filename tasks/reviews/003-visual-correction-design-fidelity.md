# Review — Task 003 Visual Correction (Strict Design Fidelity)

## Structural / Architectural Review

### Files updated (scope check)
Only the files listed in `tasks/003-visual-correction-design-fidelity.md` show the updated structural markers (`form_action`, `.ai-form-section` in welcome, and the new auth sections), based on repo-wide `rg` checks:
- `resources/css/app.css`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/components/ui/{logo,input,button-primary,button-secondary,card,form-error}.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/{public,merchant,admin}/auth/login.blade.php` and the `public/` + `merchant/` `register.blade.php` pages
- `resources/views/{public,merchant,admin}/dashboard/index.blade.php`

No modified Blade files in the auth/layout paths contain `style="..."` or `<style>` tags.

### Auth DOM/class structure requirement
The required DOM chain is produced by the combination of:
- `resources/views/layouts/auth.blade.php` (renders the `.ai-form-section` wrapper, the `<form class="ai-form">`, the `x-ui.card` container, and the `<div class="form-grid">`)
- Each target auth page (provides `form_action`, `form_fields`, `recaptcha`, `form_actions`, `form_links`)

Specifically, when `@hasSection('form_action')` is true (all 5 target pages below + both register pages define it), the structure is:
- `.ai-form-section`
  - direct child `.ai-form` (the `<form class="ai-form">`)
    - direct child `.form-card` (the `<x-ui.card>` which renders `<div class="form-card">`)
      - direct child `.form-grid` (the `<div class="form-grid">`)
        - repeated `.field` elements (provided by each page’s `form_fields` section)

### Buttons / inputs / labels mapping
Verified by inspecting:
- `resources/views/components/ui/button-primary.blade.php`: always renders `class="btn btn-dark rounded-pill px-4"` (no appended `class` merging)
- `resources/views/components/ui/button-secondary.blade.php`: always renders `class="btn btn-outline-dark rounded-pill px-4"`
- `resources/views/components/ui/input.blade.php`:
  - for non-checkbox inputs: always emits `class="input"`
  - for checkbox inputs: emits a plain checkbox `<input>` (no `class="input"`)
- auth pages: all labels use `class="label"` and text inputs use the `x-ui.input` component with the correct `type`

### reCAPTCHA rendering within the auth `<form>`
reCAPTCHA widget + script remain conditional and are yielded into `layouts/auth.blade.php` *inside* the `<form class="ai-form">` branch:
- pages render the widget + script under `@section('recaptcha')`
- layout places `@yield('recaptcha')` after `.form-grid`

This satisfies the requirement that the widget + script remain within the actual auth form element.

## Visual Fidelity Review

### Exact selector/value mapping from source-of-truth
Compared the required selector blocks in `resources/css/app.css` against:
`docs/design-source-of-truth/website-parent/css/style.css`

**Must-fix**
1. `resources/css/app.css` — `.brand img` is not a verbatim copy.
   - Source-of-truth `.brand img` contains only:
     - `height: 52px;`
     - `width: auto;`
   - Current implementation includes an extra property:
     - `display: block;` (this property is not present in the source-of-truth block)
   - File: `resources/css/app.css` (`.brand img` block)

This violates the task requirement: “copy selector rules and values exactly for the selectors listed below”.

**No other mismatches found in the required blocks (by inspection)**
These required selectors appear to match the source-of-truth blocks (values/properties):
- `.ai-form-section`
- `.ai-form`
- `.ai-form .form-card`
- `.ai-form .form-grid` (+ responsive media query at `max-width: 768px`)
- `.ai-form .field--full`
- `.ai-form .label`
- `.ai-form .input`
- `.ai-form .input:focus, .ai-form .input:focus-visible`
- `.ai-form .textarea`
- `.ai-form .form-actions`
- button typography + variants:
  - `.btn, a.btn`
  - `.btn-dark, .btn-theme` (+ `:hover`, `:focus-visible`)
  - `.btn-outline-dark, .btn-theme--outline` (+ `:hover`, `:focus-visible`)

### Visual hierarchy / spacing rhythm risk
The auth form spacing rhythm relies entirely on the copied `.ai-form*` selectors and the required DOM chain. Structurally it matches; therefore spacing/hierarchy should follow the source-of-truth once `.brand img` is corrected.

### Optional improvements
1. Validation error duplication risk (UX/visual clutter):
   - `layouts/auth.blade.php` renders an aggregated `$errors->any()` block at the top of `.form-card`.
   - Each field also renders `<x-ui.form-error name="...">` inside its `.field`.
   - The task requires aggregated errors at the top, but it does not explicitly require per-field rendering; this may produce duplicated error text visually.
   - This is optional unless the design source-of-truth shows only the top aggregate.

