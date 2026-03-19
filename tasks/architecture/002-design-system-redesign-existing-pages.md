# Architecture — Task 002 (Design System & Redesign Existing Pages)

## Objective
Introduce a reusable, Blade-based design system (layouts + UI components + design-token styling) and apply it to the existing welcome/auth/dashboard placeholder pages, keeping flows and business logic unchanged.

## Architectural constraints (must)
1. Use Laravel + Blade only (no SPA frameworks).
2. No business logic changes (controllers/services/requests untouched unless the layout requires new markup, not new behavior).
3. All user-facing text remains in Romanian.
4. No one-off inline styling:
   - no `<style>...</style>` blocks inside Blade views
   - no `style="..."` attributes in pages/components
5. Reusable UI must be implemented as Blade components under `resources/views/components/ui/`.

## Current repo reality (drives decisions)
1. Tailwind v4 is present and loaded from `resources/css/app.css` via `@vite(['resources/css/app.css', 'resources/js/app.js'])` in `welcome.blade.php`, but:
   - `resources/views/layouts/auth.blade.php` is a standalone HTML document that defines styling via an embedded `<style>` block.
2. There is no `resources/views/layouts/app.blade.php` yet (required by this task).
3. Target pages currently rely on legacy classnames coming from the embedded `<style>`:
   - `.field`, `.btn`, `.btn-secondary`, `.link-row`, `.errors`, `.status`
4. The auth pages already include reCAPTCHA blocks using `@if(config('recaptcha.enabled'))` (widget + script). Redesign must preserve the conditional blocks and their locations (widget markup and script load).

## Exact file structure (create/update)

### Layouts (create/update)
1. Create `resources/views/layouts/app.blade.php`
2. Update `resources/views/layouts/auth.blade.php`

### Blade UI components (create)
Create the following components under `resources/views/components/ui/`:
1. `resources/views/components/ui/button-primary.blade.php`
2. `resources/views/components/ui/button-secondary.blade.php`
3. `resources/views/components/ui/input.blade.php`
4. `resources/views/components/ui/select.blade.php` (only required if redesigned pages need it; component still should exist to satisfy “minimum set”)
5. `resources/views/components/ui/form-error.blade.php`
6. `resources/views/components/ui/card.blade.php`
7. `resources/views/components/ui/alert.blade.php`
8. `resources/views/components/ui/logo.blade.php`

### Design-system styling (create/update)
1. Update `resources/css/app.css` (add design-token CSS variables + `ds-*` class definitions, and legacy compatibility aliases if needed)

### Branding asset (create)
1. Create `public/img/floraffeine-logo.svg`
   - source: `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
   - runtime usage via `/img/floraffeine-logo.svg`

### Redesign target pages (update)
Update only Blade views listed below (and optionally password-reset views if needed for consistency):
1. `resources/views/welcome.blade.php`
2. `resources/views/public/auth/login.blade.php`
3. `resources/views/public/auth/register.blade.php`
4. `resources/views/merchant/auth/login.blade.php`
5. `resources/views/merchant/auth/register.blade.php`
6. `resources/views/admin/auth/login.blade.php`
7. `resources/views/public/dashboard/index.blade.php`
8. `resources/views/merchant/dashboard/index.blade.php`
9. `resources/views/admin/dashboard/index.blade.php`

## Blade component naming conventions
1. All design-system components live in `resources/views/components/ui/`.
2. Component names use the namespace tag prefix `ui.*`:
   - example: `resources/views/components/ui/button-primary.blade.php` is used as `<x-ui.button-primary ... />`
3. Components must:
   - not contain business copy (no Romanian labels inside components unless explicitly passed in props/slots)
   - accept `class` as an override/extension point, but must always include their base design-system classes
   - forward HTML attributes with `$attributes` where safe

## Layout architecture

### `resources/views/layouts/app.blade.php` (new)
Responsibilities:
1. Own the global HTML skeleton:
   - `<html lang="...">`
   - `<head>` meta tags, fonts, and Vite asset loading
2. Render consistent header + footer wrapper for non-auth pages.
3. Provide a `@yield('content')` region.

Implementation requirements:
1. `<head>` and Vite loading must be consistent across all layouts:
   - include `@vite(['resources/css/app.css', 'resources/js/app.js'])` using the same conditional logic already present in `welcome.blade.php`.
2. `lang` must be derived from `app()->getLocale()` (do not hardcode `en`).
3. Header:
   - MVP rule: show logo and, at most, simple links only if a page provides a `@section('header_nav')`.
   - logo must use `<x-ui.logo />`.
4. Footer:
   - provide minimal footer structure consistent with design system (can be placeholder text if the parent design is not fully extracted yet, but must remain Romanian).

Yield/section contract (must not change without updating all consumers):
1. `@yield('content')` for page body
2. Optional `@section('header_nav')` for header navigation blocks

### `resources/views/layouts/auth.blade.php` (updated)
Responsibilities:
1. Remain the dedicated layout for auth pages.
2. Provide the card-based centered auth shell.
3. Display cross-page auth status/errors using the design-system components.
4. Preserve the child page section API:
   - existing auth pages already define:
     - `@section('title', '...')`
     - optional `@section('subtitle', '...')`
     - `@section('content')`
   - layout must continue rendering `@yield('title')`, `@hasSection('subtitle')`, and `@yield('content')`.

Implementation requirements:
1. Remove the embedded `<style>` block entirely.
2. Load assets (same approach as `layouts/app`):
   - `@vite(['resources/css/app.css', 'resources/js/app.js'])` (conditional if needed)
3. HTML structure:
   - centered wrapper using `ds-*` classes
   - auth card using `<x-ui.card>`
4. Messages:
   - status: `session('status')` must render via `<x-ui.alert variant="success">`
   - global validation errors (when `$errors->any()`): render via `<x-ui.alert variant="error">` with an unordered list
5. No inline styles:
   - remove `style="margin: 0; padding-left: 1rem;"` from the error list

## CSS / design tokens architecture

### Styling location
All design-system styling must live in:
- `resources/css/app.css`

No embedded `<style>` blocks in any Blade view.

### Token strategy
Define tokens as CSS variables to keep component CSS cohesive. Recommended variable set:
1. Colors:
   - `--ds-color-bg` (page background)
   - `--ds-color-card-bg` (card background)
   - `--ds-color-text` (main text)
   - `--ds-color-text-muted` (subtitle/secondary text)
   - `--ds-color-border` (input/card borders)
   - `--ds-color-brand` (primary accent)
   - `--ds-color-brand-hover` (primary hover)
   - `--ds-color-link` (links, typically brand)
   - `--ds-color-danger` (error text)
   - `--ds-color-danger-bg` (error alert background)
   - `--ds-color-success` (success text)
   - `--ds-color-success-bg` (success alert background)
2. Radii/shadows:
   - `--ds-radius-card`
   - `--ds-shadow-card`
3. Spacing scale:
   - optional if using Tailwind for spacing; otherwise define `--ds-space-1`, `--ds-space-2`, ...

Token values must be sourced from:
- `docs/design-source-of-truth/website-parent` (design extraction phase)

### Design-system class naming (`ds-*`)
All styled design-system elements must use `ds-*` classes (no arbitrary brand hex values in page markup).

Minimum required `ds-*` classes:
1. Layout:
   - `.ds-page` (global background + text color)
   - `.ds-auth-wrapper` (centering and min-height)
   - `.ds-container` (max width + horizontal padding)
2. Card and typography:
   - `.ds-card`
   - `.ds-auth-card`
   - `.ds-title`
   - `.ds-subtitle`
3. Forms:
   - `.ds-field` (vertical spacing)
   - `.ds-label`
   - `.ds-input`
   - `.ds-input--error` (error state border/focus)
   - `.ds-form-error` (error message under field)
   - `.ds-link-row` (remember links / auth links row)
4. Buttons:
   - `.ds-btn`
   - `.ds-btn-primary`
   - `.ds-btn-secondary`
5. Alerts:
   - `.ds-alert`
   - `.ds-alert--success`
   - `.ds-alert--error`

Accessibility requirements for CSS:
1. `.ds-input` must define a visible focus ring using brand accent.
2. Error state must be visually distinguishable (border + focus ring + error text).

### Legacy compatibility aliases (recommended to reduce regression risk)
Because multiple auth views already use legacy classnames, the CSS layer should include aliases to map:
- `.btn`, `.btn-secondary`, `.field`, `.link-row`, `.errors`, `.status`
to the new `ds-*` styles.

This aliasing should exist during the transition to ensure:
- pages not in the Task’s redesign list that still use legacy markup (e.g. password reset views) do not break visually.

## How layouts and components interact (contracts)
1. Pages:
   - must extend either `layouts.app` (welcome) or `layouts.auth` (all auth pages)
2. Auth pages contract:
   - `title`, optional `subtitle`, and `content` sections are required
   - within `content`, pages should use UI components:
     - `<x-ui.input>`, `<x-ui.form-error>`, `<x-ui.button-primary>`, `<x-ui.button-secondary>`, and `<x-ui.card>` where appropriate
3. Components:
   - components never assume which user/merchant/admin area they belong to; styling and structure are generic
4. Alerts:
   - layout uses `<x-ui.alert>` for session status and global errors summary
   - pages use `<x-ui.form-error>` for per-field validation messages

## Expected component API (props/slots)

### `<x-ui.logo>`
Props:
- `alt` (string, default: "Floraffeine Boutique")
- `class` (string, optional)
Behavior:
- renders `<img src="/img/floraffeine-logo.svg" alt="..." ... />`

### `<x-ui.card>`
Props:
- `class` (string, optional)
Slots:
- `header` (optional named slot): renders at the top
- default slot: renders body content

### `<x-ui.alert>`
Props:
- `variant` (string, required; allowed: `success`, `error`)
Slots:
- default slot: message content

### `<x-ui.button-primary>`
Props:
- `type` (string, default: "button")
- `class` (string, optional)
- `href` (string, optional):
  - if present, render as `<a>` (link-style primary button)
  - if absent, render as `<button>`

Slots:
- default slot: button label

### `<x-ui.button-secondary>`
Props and slots:
- same as `button-primary`, but secondary variant classes

### `<x-ui.input>`
Props (minimum):
- `name` (string, required)
- `type` (string, default: "text")
- `id` (string, optional; default: same as name)
- `value` (string|null, optional)
- `class` (string, optional)
Behavior:
1. Always renders an `<input>` element.
2. Applies `.ds-input` base class.
3. If `$errors->has($name)` is true, adds `.ds-input--error`.
4. Uses `aria-invalid` and `aria-describedby="error-{$name}"` when errors exist.
5. Merges `$attributes` for autofocus, required, placeholder, etc.

### `<x-ui.select>`
Props:
- `name` (string, required)
- `id` (string, optional; default: same as name)
- `options` (array, optional): array of `{ value, label }` (implementation-specific shape should be documented in the component)
- `class` (string, optional)

Behavior:
- renders a `<select>` with `.ds-input` styling

### `<x-ui.form-error>`
Props:
- `name` (string, required)
Behavior:
1. If there is an error for `name`, render it under the input.
2. Set an element id: `id="error-{$name}"` so the input’s `aria-describedby` matches.
3. If no error exists, render nothing.

## Consistency rules (must)
1. No inline `style` attributes in any file touched for this task.
2. No `<style>` tags in any Blade view.
3. No hardcoded brand colors in page markup:
   - use `ds-*` classes or Tailwind utilities for spacing/layout only
4. All form fields in redesigned pages must follow the same structure:
   - `.ds-field` wrapper
   - `<label class="ds-label">...</label>`
   - `<x-ui.input ... />`
   - `<x-ui.form-error name="..." />`
5. Button usage:
   - forms must use `<x-ui.button-primary>` or `<x-ui.button-secondary>`
6. Messages:
   - use `<x-ui.alert>` for layout-level status and error summaries
   - use `<x-ui.form-error>` for field-level messages

## Page integration expectations (technical mapping)
### Welcome page
1. Must switch from standalone HTML to:
   - `@extends('layouts.app')`
   - populate `@section('content')`
2. Replace all legacy/placeholder content with Romanian strings as per task requirements.
3. Use `x-ui.card`, `x-ui.button-primary/secondary` where appropriate for CTAs.

### User/Merchant/Admin auth pages
1. Keep `@extends('layouts.auth')` and the `title/subtitle/content` sections.
2. Replace:
   - `.field`, `.btn`, `.btn-secondary`, `.link-row`, `.errors`, `.status` usage with design-system primitives:
     - field wrappers use `.ds-field`
     - inputs use `<x-ui.input>` and `<x-ui.form-error>`
     - buttons use `<x-ui.button-*>`
     - link rows use `.ds-link-row`
3. Remove inline styles used for “remember me” rows:
   - replace `style="display:flex;align-items:center;gap:0.4rem;"` with class-based equivalents (e.g., `flex items-center gap-*` or a `.ds-remember-row` helper if you add it in CSS)
4. Preserve reCAPTCHA blocks:
   - widget:
     - remains inside `@if(config('recaptcha.enabled'))`
   - script:
     - remains inside `@if(config('recaptcha.enabled'))`
   - do not move those blocks into shared components.

### Dashboard placeholders
1. Must remove inline `style="font-size:0.9rem;margin-bottom:1rem;"`.
2. Use design-system typography helpers or standard spacing classes (via `ds-*` CSS classes).
3. Use `<x-ui.button-secondary type="submit">Deconectare</x-ui.button-secondary>` for logout forms.

## Migration risk & ambiguity handling
1. Tailwind + custom CSS layering:
   - ensure new `ds-*` classes are placed in `resources/css/app.css` in a way that does not get overridden by Tailwind preflight.
2. Dark mode behavior:
   - existing welcome markup uses `dark:*` classes, but auth layout currently does not define dark mode.
   - decision required during implementation:
     - either implement dark support in `ds-*` CSS using `:root` + `@media (prefers-color-scheme: dark)`
     - or rely on tailwind `dark:` if the layout sets the `.dark` class.
3. Legacy class aliasing:
   - recommended to include CSS aliases for `.btn`, `.field`, etc until all auth views (including password reset ones) are refactored.
4. Component contracts:
   - input `aria-describedby` id scheme must exactly match the `form-error` id scheme.

## Validation checklist (architecture-level)
1. No inline `style="..."` in updated pages/layouts/components.
2. All target pages render with the new layouts and use design-system components.
3. reCAPTCHA blocks remain conditionally visible and functional in all auth pages that contain them.
4. All layouts load `resources/css/app.css` via `@vite`, so the design system is applied consistently.

