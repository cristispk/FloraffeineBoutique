# Task 002 — Implement Design System & Redesign Existing Pages

## Goal
Add a reusable Blade design system (layouts + UI components + centralized styling) and redesign the listed existing pages to use it, without changing authentication/business logic.

## Scope (must do)
Redesign these pages (UI only):
1. `resources/views/welcome.blade.php`
2. `resources/views/public/auth/login.blade.php`
3. `resources/views/public/auth/register.blade.php`
4. `resources/views/merchant/auth/login.blade.php`
5. `resources/views/merchant/auth/register.blade.php`
6. `resources/views/admin/auth/login.blade.php`
7. `resources/views/public/dashboard/index.blade.php`
8. `resources/views/merchant/dashboard/index.blade.php`
9. `resources/views/admin/dashboard/index.blade.php`

## Constraints (hard requirements)
1. Laravel + Blade only (no React/Vue/Inertia/Livewire).
2. No route/controller/service/business-logic changes.
3. No user-facing English text in any updated UI.
4. No inline styles:
   - no `style="..."` attributes in any file you touch for this task
   - no embedded `<style>...</style>` blocks in Blade views
5. No one-off styling:
   - all new UI styling must come from centralized CSS and reusable Blade components
6. reCAPTCHA:
   - keep widget markup and script loading inside `@if(config('recaptcha.enabled'))` blocks in the login/register Blade pages
   - do not move those blocks into components/layouts
7. Backward-safe CSS:
   - password reset pages are not redesigned in this task, so you must provide CSS compatibility aliases so they still look acceptable:
     `.btn`, `.btn-secondary`, `.field`, `.link-row`, `.errors`, `.status`

## Exact files to create/update

### Create
1. `resources/views/layouts/app.blade.php`
2. `resources/views/components/ui/button-primary.blade.php`
3. `resources/views/components/ui/button-secondary.blade.php`
4. `resources/views/components/ui/input.blade.php`
5. `resources/views/components/ui/select.blade.php`
6. `resources/views/components/ui/form-error.blade.php`
7. `resources/views/components/ui/card.blade.php`
8. `resources/views/components/ui/alert.blade.php`
9. `resources/views/components/ui/logo.blade.php`
10. `public/img/floraffeine-logo.svg` (copy from `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`)

### Update
1. `resources/views/layouts/auth.blade.php` (remove embedded `<style>`, switch to design system classes/components)
2. `resources/css/app.css` (add design tokens + `ds-*` classes + legacy compatibility aliases)
3. The 9 Blade pages listed in Scope

## Implementation steps (recommended execution order)

### Step 0 — Prep checks (do first)
1. Confirm Tailwind v4 is active by ensuring `@vite(['resources/css/app.css', 'resources/js/app.js'])` works in the redesigned layouts.
2. Identify all current “auth legacy” CSS class usage in the auth area:
   - `.field`, `.btn`, `.btn-secondary`, `.link-row`, `.errors`, `.status`
3. Identify which auth pages already contain reCAPTCHA:
   - user + merchant login/register include widget + script under `@if(config('recaptcha.enabled'))`
4. Confirm password reset pages still extend `layouts.auth` and currently rely on the legacy classes; you will support them via CSS aliases (do not refactor their markup for this task).

### Step 1 — Create the global layout `layouts.app`
Create `resources/views/layouts/app.blade.php` with:
1. Proper HTML skeleton:
   - `<html lang="{{ str_replace('_','-', app()->getLocale()) }}">`
2. Standard `<head>` content:
   - load `resources/css/app.css` and `resources/js/app.js` via `@vite` (use the same conditional logic style as existing `welcome.blade.php`)
3. Minimal header/footer wrapper (no complex navigation required):
   - use `<x-ui.logo />` in the header area
4. `@yield('content')` for page body content.

### Step 2 — Refactor `layouts.auth` to use the design system
Update `resources/views/layouts/auth.blade.php`:
1. Remove the entire embedded `<style>...</style>` block (must be zero `<style>` tags in this file).
2. Switch HTML structure to:
   - page wrapper classes using the new `ds-*` CSS classes
   - auth card rendered via `<x-ui.card>`
3. Preserve the existing child page API:
   - render `@yield('title')`
   - render `@hasSection('subtitle')` + `@yield('subtitle')`
   - render `@yield('content')`
4. Replace message rendering:
   - session status (`session('status')`) -> `<x-ui.alert variant="success">`
   - global validation errors (`$errors->any()`) -> `<x-ui.alert variant="error">` containing a `<ul>` list
5. Remove inline style from the error list (`style="margin:0; padding-left:1rem;"`) by relying purely on CSS classes.

### Step 3 — Implement the Blade UI components (`x-ui.*`)
Create the components under `resources/views/components/ui/` and ensure:
1. Each component accepts `$attributes` and merges `class` with its base classes.
2. Components do not contain business Romanian strings internally (they render only passed labels/slots/children).
3. Component APIs (must implement at least the following behavior):

#### `<x-ui.logo />`
- Renders: `<img src="/img/floraffeine-logo.svg" alt="Floraffeine Boutique" ... />`

#### `<x-ui.card />`
- Wraps content with consistent card markup
- Supports `header` named slot + default slot

#### `<x-ui.alert variant="success|error">`
- Uses `variant` to choose success/error styling
- Renders slot content

#### `<x-ui.button-primary />` and `<x-ui.button-secondary />`
- Render `<button>` by default
- Support `href` to render `<a>` instead (optional but recommended)
- Use primary vs secondary CSS classes

#### `<x-ui.input name="..." type="..." ... />`
- Always renders `<input>`
- Adds base class for design-system inputs
- If validation error exists for that `name`, adds error class and `aria-invalid`
- `aria-describedby` must point to the matching error element id: `error-{$name}`

#### `<x-ui.form-error name="..." />`
- If an error exists for `name`, render the message under the input
- Must render a container with `id="error-{$name}"` (or equivalent) so `aria-describedby` works
- If no error exists, render nothing

#### `<x-ui.select />`
- Render a `<select>` using the same design-system classes as inputs
- You may keep `options` support minimal, as select is only “minimum-set” for this task

### Step 4 — Add design system CSS tokens + classes
Update `resources/css/app.css`:
1. Add `ds-*` classes and CSS variables for colors/spacing/radii/shadows extracted from the design source-of-truth.
2. Required `ds-*` classes (at minimum):
   - Layout: `.ds-page`, `.ds-container`, `.ds-auth-wrapper`
   - Card/title/subtitle: `.ds-card`, `.ds-auth-card`, `.ds-title`, `.ds-subtitle`
   - Forms: `.ds-field`, `.ds-label`, `.ds-input`, `.ds-input--error`, `.ds-form-error`, `.ds-link-row`
   - Buttons: `.ds-btn`, `.ds-btn-primary`, `.ds-btn-secondary`
   - Alerts: `.ds-alert`, `.ds-alert--success`, `.ds-alert--error`
3. Dark mode:
   - implement dark mode using `prefers-color-scheme: dark` and CSS variables (do not rely on a `.dark` class existing in auth layout)
4. Focus ring:
   - `.ds-input` must have an obvious focus style using the brand accent token.
5. Legacy compatibility aliases (required for password reset pages):
   - map these legacy classnames to the new styles using CSS aliases (no inline styles):
     `.btn`, `.btn-secondary`, `.field`, `.link-row`, `.errors`, `.status`

### Step 5 — Redesign `welcome.blade.php`
Update `resources/views/welcome.blade.php`:
1. Switch to `@extends('layouts.app')`
2. Implement `@section('content')`
3. Remove the existing English text content and replace with Romanian strings.
4. Ensure the layout/card/typography uses `ds-*` classes and reusable components (`<x-ui.card>` and button components as appropriate).

### Step 6 — Redesign authentication pages (login/register)
Update these pages:
1. `resources/views/public/auth/login.blade.php`
2. `resources/views/public/auth/register.blade.php`
3. `resources/views/merchant/auth/login.blade.php`
4. `resources/views/merchant/auth/register.blade.php`
5. `resources/views/admin/auth/login.blade.php` (no register in scope)

For each page:
1. Keep `@extends('layouts.auth')` and keep the `title/subtitle/content` sections.
2. Replace legacy markup with design system components:
   - replace `<div class="field">...` with `<div class="ds-field">`
   - replace `<label>` classes to use `.ds-label`
   - replace `<input ...>` with `<x-ui.input ... />`
   - add `<x-ui.form-error name="..." />` under each relevant field (email, password, password_confirmation, name)
   - replace `<button class="btn ...">` with `<x-ui.button-primary>` or `<x-ui.button-secondary>` depending on the page purpose
   - replace “links row” container with a `.ds-link-row` wrapper (no inline style)
3. Remember-me row:
   - remove any `style="display:flex;align-items:center;gap:0.4rem;"` and implement the same layout using classes only.
4. reCAPTCHA:
   - preserve the existing `@if(config('recaptcha.enabled'))` blocks:
     - widget `<div class="g-recaptcha" data-sitekey="..."></div>`
     - script `<script src="https://www.google.com/recaptcha/api.js" async defer></script>`

### Step 7 — Redesign dashboard placeholders (remove inline style)
Update these pages:
1. `resources/views/public/dashboard/index.blade.php`
2. `resources/views/merchant/dashboard/index.blade.php`
3. `resources/views/admin/dashboard/index.blade.php`

For each:
1. Remove inline styles like `style="font-size:0.9rem;margin-bottom:1rem;"`
2. Use `ds-*` typography/spacing classes (or component-based equivalents) and ensure logout uses `<x-ui.button-secondary>` (design-system button).

### Step 8 — Manual verification (must)
After code changes, run a smoke test in a browser:
1. Auth pages render with correct styling:
   - user login
   - user register
   - merchant login
   - merchant register
   - admin login
2. reCAPTCHA UI presence:
   - when `RECAPTCHA_ENABLED=true`, widget + script appear
   - when `RECAPTCHA_ENABLED=false`, widget + script are not present
3. Validation display:
   - submit invalid forms and confirm:
     - field-level errors render under fields via `<x-ui.form-error>`
     - any global errors (if triggered) render via `layouts.auth` using `<x-ui.alert variant="error">`
4. Password reset pages are not broken visually:
   - open `resources/views/public/auth/passwords/forgot.blade.php` and `reset.blade.php` and verify they still look styled (using legacy alias CSS).
5. No inline styles / no `<style>` tags:
   - confirm in all modified Blade files:
     - no `style="..."` attributes
     - no `<style>` tags

## Acceptance criteria
1. Every page in Scope visually matches the design source-of-truth’s visual language using `ds-*` styling and reusable components (no one-off inline styles).
2. `resources/views/layouts/auth.blade.php` contains no `<style>` tag and uses `<x-ui.*>` components for card/alerts.
3. `resources/css/app.css` defines the required `ds-*` classes and tokens, plus legacy compatibility aliases.
4. reCAPTCHA widget and script blocks remain conditionally rendered in the login/register pages exactly within their existing `@if(config('recaptcha.enabled'))` blocks.
5. Welcome page text is fully Romanian and uses `layouts.app`.
6. Dashboard placeholders have no inline styles and use design-system typography/buttons.
7. Authentication flows still work (manual smoke test: login/register for user + merchant, admin login).

## Deliverable check
When finished, ensure the implementer has:
1. Created all files listed under “Create”
2. Updated all files listed under “Update”
3. Performed the manual verification checklist in Step 8

# Task 002 — Align Design System & Redesign Existing Pages

## Context
The project already has:
- an authentication system (public / merchant / admin) with working Blade pages and validation
- a minimal Blade layout for auth (`resources/views/layouts/auth.blade.php`)
- placeholder “dashboard” pages for user/merchant/admin

However, there is also a **design source-of-truth** at:
- `docs/design-source-of-truth/website-parent`

This task aims to:
- align all existing pages with the Floraffeine visual identity
- introduce a **reusable design system** (layouts + Blade components) that will be used as the standard for any future module

## Objective
Introduce a design system based on:
- a color palette
- typography
- a spacing system
- button, input, and card styles
- layout patterns and message patterns (alerts/errors/empty states)

and redesign the existing pages so they are visually consistent and scalable.

## Constraints
- Keep **Laravel + Blade**
- Do NOT introduce frontend frameworks (React/Vue/Inertia/Livewire etc.)
- Do NOT modify **business logic**, routes, or controllers unless strictly necessary to support a new layout
- All user-facing texts added/updated must be in **Romanian**, respecting the terminology from:
  - `docs/BOUTIQUE-GLOSSARY.md`
  - the UI rules in `docs/ARCHITECTURE.md`
- Any new UI component must be implemented as a **Blade component** (not one-off inline styles inside pages)

## Steps (Design → System → Redesign)

### 1) Design Source-of-Truth Analysis
Analyze `docs/design-source-of-truth/website-parent` and extract:
- color palette (e.g. brand accent, backgrounds, text, link hover)
- typography (font families, styles for titles/subtitles/body)
- spacing system (padding/margin scale)
- button styles:
  - primary / secondary / outline (hover/focus)
- input styles:
  - input, textarea, select (borders, focus behavior)
- card styles:
  - “auth cards”, general listing cards
- layout patterns:
  - container, header/navigation, footer
- message patterns:
  - alert success/error
  - empty state (message + optional CTA)

**Expected result:** an internal list of key tokens and the key classes/components extracted from the design source-of-truth (stored in task notes or referenced in commit notes).

### 2) Design System Foundation (Reusable)
Create a base structure in Blade:

#### 2.1 Layouts
- `resources/views/layouts/app.blade.php`
  - include header (logo + navigation if applicable)
  - include footer (consistent structure)
  - contain a slot for page content
- `resources/views/layouts/auth.blade.php`
  - keep this as a dedicated layout for auth pages, but use the styling and components from the design system

#### 2.2 Blade UI Components (minimum set)
Implement reusable components (examples):
- `resources/views/components/ui/button-primary.blade.php`
- `resources/views/components/ui/button-secondary.blade.php`
- `resources/views/components/ui/input.blade.php` (render an input + classes)
- `resources/views/components/ui/select.blade.php` (if needed by pages)
- `resources/views/components/ui/form-error.blade.php` (display errors under a field)
- `resources/views/components/ui/card.blade.php`
- `resources/views/components/ui/alert.blade.php`
- `resources/views/components/ui/logo.blade.php`

Each component must:
- use the same tokens/cls/classes as the rest of the design system
- keep consistent spacing and styling across all pages
- not “force” business copy (texts live in pages; components receive content via slots/props)

#### 2.3 Integrate palette & styles
Choose an MVP-simple strategy:
- either local CSS (CSS file in `resources/css` + included in layout via `@vite` or script/style tags)
- or reuse any existing CSS classes if the project already uses them consistently

**Important:** avoid inline styles in pages. Styling should live in layouts/CSS or in components.

### 3) Redesign existing pages (no logic changes)
Redesign the following pages so they use the new layouts and components from the design system:

#### 3.1 Welcome page
- `resources/views/welcome.blade.php`
  - translate all texts to Romanian
  - align the layout with the design system pattern

#### 3.2 User auth pages
- `resources/views/public/auth/login.blade.php`
- `resources/views/public/auth/register.blade.php`

#### 3.3 Merchant auth pages
- `resources/views/merchant/auth/login.blade.php`
- `resources/views/merchant/auth/register.blade.php`

#### 3.4 Admin auth page
- `resources/views/admin/auth/login.blade.php`

#### 3.5 Dashboard placeholders (user/merchant/admin)
- `resources/views/public/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/index.blade.php`
- `resources/views/admin/dashboard/index.blade.php`

## Branding
Integrate the Floraffeine logo:
- source: `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
- integrate it into a dedicated component (`components/ui/logo.blade.php`)
- use it in the layout/header and in auth pages if the design requires it

## Deliverables
- Layouts:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/auth.blade.php` (updated)
- Blade UI components:
  - components from section 2.2 (minimum set)
- Redesign of the pages (full list from section 3)
- Visual consistency:
  - buttons / inputs / cards / messages use the same style and the same “visual language”

## Acceptance Criteria
- All pages listed in section “3) Page Redesign” look coherent and aligned with the design source-of-truth (colors/spacing/buttons/inputs/cards).
- Auth pages and dashboard placeholders use the design system (layout + components), not unique one-off inline styles.
- The welcome page is fully translated to Romanian.
- No route/controller changes are made (except strictly necessary layout integration).
- All authentication flows remain functional (manual smoke test):
  - user login/register
  - merchant login/register
  - admin login

## Notes / Non-goals (for MVP UI)
- Do NOT implement complex navigation (multi-layer menus) unless it already exists in the UI design.
- Do NOT introduce new pages or extend business logic.
- Do NOT refactor the domain (products/cart/checkout) — this task is UI-only.

# Task 002 — Aliniere Design System & Redesign Pagini Existente

## Context
Proiectul are deja:
- sistemul de autentificare (public / merchant / admin) cu pagini Blade și validări funcționale
- layout Blade minimal (`resources/views/layouts/auth.blade.php`)
- pagini “dashboard” placeholder pentru user/merchant/admin

Există, în schimb, o **sursă de adevăr pentru design** la:
- `docs/design-source-of-truth/website-parent`

Această task are ca scop:
- să alinieze toate paginile existente la identitatea vizuală Floraffeine
- să introducă un **design system reutilizabil** (layouts + componente Blade) care va fi folosit în mod standard pentru orice modul viitor

## Obiectiv
Introduceți un design system bazat pe:
- paletă de culori
- tipografie
- sistem de spacing
- stiluri de butoane, inputs și carduri
- pattern-uri de layout și mesaje (alerts/errors/empty states)

și redesenați paginile existente astfel încât să fie vizual coerente și scalabile.

## Constrângeri
- Păstrați **Laravel + Blade**
- Nu introduceți framework-uri frontend (React/Vue/Inertia/Livewire etc.)
- Nu modificați **business logic**, rute sau controlere decât dacă este strict necesar pentru a “prinde” paginile într-un layout nou
- Toate textele user-facing adăugate/atinse trebuie să fie în **Română**, respectând terminologia din:
  - `docs/BOUTIQUE-GLOSSARY.md`
  - regulile UI din `docs/ARCHITECTURE.md`
- Orice componentă UI nouă trebuie să fie **reutilizabilă** și implementată ca **Blade component** (nu style-uri “one-off” în pagini).

## Etape (Design → System → Redesign)

### 1) Analiză Design Source-of-Truth
Analizați `docs/design-source-of-truth/website-parent` și extrageți:
- paletă de culori (ex: brand accent, fundaluri, text, link hover)
- tipografie (familii, fallback-uri, stiluri pentru titluri/subtitluri/body)
- spacing system (scale de padding/margin)
- stiluri pentru butoane:
  - primary / secondary / outline (hover/focus)
- stiluri pentru inputs:
  - input, textarea, select (borders, focus)
- stiluri pentru carduri:
  - carduri “auth”, carduri generale/listări
- pattern-uri de layout:
  - container, header/navigation, footer
- pattern-uri pentru mesaje:
  - alert success/error
  - empty state (mesaj + opțional CTA)

**Rezultat așteptat:** o listă internă (în task notes sau în commit message) cu tokenii cheie și componentele/clasele-cheie extrase din sursa de adevăr.

### 2) Fundație Design System (reutilizabilă)
Creați o structură de bază în Blade:

#### 2.1 Layout-uri
- `resources/views/layouts/app.blade.php`
  - include header (logo + navigație dacă este cazul)
  - include footer (structură consistentă)
  - conține un slot pentru conținutul paginii
- `resources/views/layouts/auth.blade.php`
  - rămâne layout dedicat pentru paginile de autentificare, dar folosește styling și componentele din design system

#### 2.2 Blade UI Components (minim)
Implementați componente reutilizabile (exemple):
- `resources/views/components/ui/button-primary.blade.php`
- `resources/views/components/ui/button-secondary.blade.php`
- `resources/views/components/ui/input.blade.php` (render input + classes)
- `resources/views/components/ui/select.blade.php` (dacă este necesar pentru pagini)
- `resources/views/components/ui/form-error.blade.php` (afișare erori sub câmp)
- `resources/views/components/ui/card.blade.php`
- `resources/views/components/ui/alert.blade.php`
- `resources/views/components/ui/logo.blade.php`

Fiecare componentă trebuie:
- să folosească aceleași tokenuri/cls ca restul design system
- să păstreze spacing și stil consistent între pagini
- să nu “impună” texte business (textele vin din views/controllers, componentele primesc content/slots)

#### 2.3 Integrare paletă & stiluri
Alegeți o strategie MVP-simple:
- fie prin CSS local (fișier CSS în `resources/css` + includere în layout cu `@vite` sau taguri)
- fie prin reutilizarea claselor existente (dacă proiectul deja le folosește în mod consecvent)

**Important:** evitați stiluri inline în pagini. Stilurile trebuie să fie în layout/CSS sau în componente.

### 3) Redesign pagini existente (fără schimbări de logică)
Redesenați următoarele pagini astfel încât să folosească layout-urile și componentele din design system:

#### 3.1 Welcome page
- `resources/views/welcome.blade.php`
  - traduceți textele în Română
  - aliniați layoutul cu pattern-ul design system

#### 3.2 User auth pages
- `resources/views/public/auth/login.blade.php`
- `resources/views/public/auth/register.blade.php`

#### 3.3 Merchant auth pages
- `resources/views/merchant/auth/login.blade.php`
- `resources/views/merchant/auth/register.blade.php`

#### 3.4 Admin auth page
- `resources/views/admin/auth/login.blade.php`

#### 3.5 Dashboard placeholder-uri (user/merchant/admin)
- `resources/views/public/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/index.blade.php`
- `resources/views/admin/dashboard/index.blade.php`

## Branding
Integrați logo-ul Floraffeine:
- sursă: `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
- integrați într-un component dedicat (`components/ui/logo.blade.php`) și folosiți-l în layout/header și în paginile auth dacă designul o cere.

## Deliverables
- Layout-uri:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/auth.blade.php` actualizat
- Componente Blade UI:
  - componentele din secțiunea 2.2 (minim)
- Paginile redesenate (listă completă din secțiunea 3)
- Coerentizare vizuală:
  - butoane / inputs / carduri / mesaje folosesc același stil și aceeași “limbă vizuală”

## Criterii de acceptanță
- Toate paginile enumerate în secțiunea “Redesign pagini existente” arată coerent și aliniat cu design source-of-truth (culori/spacing/buttons/inputs/cards).
- Pagina auth și dashboard placeholder folosesc design system (layout + componente), nu style-uri inline unice.
- Welcome page este tradus complet în Română.
- Nu se modifică rutele/controlerele (cu excepția strict necesară pentru a integra un nou layout, dacă apare o dependență structurală).
- Toate fluxurile de autentificare rămân funcționale (smoke test manual):
  - user login/register
  - merchant login/register
  - admin login

## Note / Non-goals (pentru MVP UI)
- Nu implementați navigație complexă (meniuri multilayer) dacă nu există deja în UI design.
- Nu introduceți noi pagini și nu extindeți business logic.
- Nu refactorizați domeniul (produse/cart/checkout) — doar UI.

