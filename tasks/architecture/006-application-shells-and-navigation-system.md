# Architecture — 006: Application Shells and Navigation System

## 1. Scope and alignment

This document converts `tasks/planning/006-application-shells-and-navigation-system.md` into implementation-ready architecture.

It is aligned with:

- `docs/UI_SHELL_BLUEPRINT.md`
- `docs/ARCHITECTURE.md`
- `docs/SCOPE.md`
- `docs/ROADMAP.md`
- `docs/DESIGN_CONTINUITY.md`
- `docs/UI_VISUAL_STANDARDS.md`
- `docs/UI_COMPONENTS_REFERENCE.md`
- current implemented state (`layouts/app`, `layouts/auth`, auth/dashboard/admin pages, shared `x-ui.*` components, `resources/css/app.css`)

Hard constraints:

- Laravel + Blade only
- no business logic expansion
- no route restructuring beyond minimal navigation-safe adjustments
- no inline Blade styles
- reusable shell foundation only
- CSS must load reliably through current runtime path (`asset('css/app.css')`)

---

## 2. Core architecture decisions (explicit)

| Decision | Choice |
|----------|--------|
| Shell count | 4 layouts: public guest, authenticated app, admin, auth transitional compatibility |
| Area separation | Public, authenticated non-admin, and admin use distinct layout classes and nav composition; no shared visual shell markup that collapses differentiation |
| Navigation source | Route-name driven arrays/config in Blade partials/components (not hardcoded duplicate links per page) |
| Active state | Determined by route name matching (`request()->routeIs(...)`) only; no URL-string parsing in views |
| Access-safe nav | Links render by authenticated role/status context only; hidden links are not authorization, middleware remains source of access control |
| Component boundary | Structural chrome (shell skeleton) in layouts/partials; reusable UI atoms and repeated nav rows in components |
| Transitional auth | Existing `layouts/auth.blade.php` remains valid during migration and becomes an adapter to shared shell primitives |
| CSS strategy | Keep single CSS entrypoint, but reorganize into clearly separated shell blocks and utility contracts (public/app/admin/auth) |

---

## 3. Exact shell architecture

### 3.1 Public guest shell

Purpose: premium brand-led browsing and trust-building.

Structural contract:

1. Sticky public header (`logo`, primary nav, auth CTAs, mobile menu trigger).
2. Optional hero/page-intro zone.
3. Main content container with section rhythm tokens.
4. Structured public footer (multi-column placeholders for expansion).

Required layout sections:

- `@yield('title')` (optional document title override)
- `@yield('public_hero')` (optional)
- `@yield('content')` (required)
- `@yield('public_footer_extra')` (optional)

Layout class contract:

- `body[data-shell="public"]`
- `main.public-main`

### 3.2 Authenticated app shell (user / merchant)

Purpose: balanced utility shell for work navigation.

Structural contract:

1. Left sidebar (primary app navigation).
2. Topbar (page title + breadcrumbs + right utilities/profile).
3. Main content area with page header and action slots.
4. Mobile drawer variant of sidebar.

Required layout sections:

- `@yield('page_title')` (required)
- `@section('breadcrumbs')` (optional)
- `@section('page_actions')` (optional)
- `@yield('content')` (required)

Layout class contract:

- `body[data-shell="app"]`
- `body[data-app-role="user|merchant"]`
- `main.app-main`

### 3.3 Admin shell

Purpose: practical, organized, table/filter/action-oriented control surface.

Structural contract:

1. Admin sidebar (operations taxonomy).
2. Utility topbar (context title, optional quick tools, profile/logout).
3. Wide content region optimized for filters/lists/details.
4. Mobile drawer variant of admin sidebar.

Required layout sections:

- `@yield('page_title')` (required)
- `@section('breadcrumbs')` (optional)
- `@section('page_actions')` (optional)
- `@section('admin_tools')` (optional topbar utility slot)
- `@yield('content')` (required)

Layout class contract:

- `body[data-shell="admin"]`
- `main.admin-main`

### 3.4 Auth transitional compatibility path

Purpose: prevent breakage while existing auth pages are migrated.

Rules:

1. `layouts/auth.blade.php` remains available for all current auth pages.
2. It adopts shared head/assets and reuses shell-compatible primitives (`site-header`, content wrappers).
3. It must not introduce duplicate CSS loading paths or inline scripts except existing reCAPTCHA usage.
4. Deletion/merge of transitional layout is not part of this task; only stabilization.

---

## 4. Exact file structure and paths

### 4.1 Layout files

Create:

- `resources/views/layouts/base.blade.php` (head/assets/html skeleton shared by all shells)
- `resources/views/layouts/public-shell.blade.php`
- `resources/views/layouts/app-shell.blade.php`
- `resources/views/layouts/admin-shell.blade.php`

Update:

- `resources/views/layouts/app.blade.php` -> becomes alias/wrapper to `layouts.public-shell` during transition
- `resources/views/layouts/auth.blade.php` -> transitional auth layout using shared base contracts

### 4.2 Partials

Create:

- `resources/views/partials/shell/public-header.blade.php`
- `resources/views/partials/shell/public-footer.blade.php`
- `resources/views/partials/shell/public-mobile-nav.blade.php`
- `resources/views/partials/shell/app-sidebar.blade.php`
- `resources/views/partials/shell/app-topbar.blade.php`
- `resources/views/partials/shell/app-mobile-nav.blade.php`
- `resources/views/partials/shell/admin-sidebar.blade.php`
- `resources/views/partials/shell/admin-topbar.blade.php`
- `resources/views/partials/shell/admin-mobile-nav.blade.php`

### 4.3 Components

Create under `resources/views/components/ui/`:

- `shell-page-container.blade.php`
- `shell-page-header.blade.php`
- `breadcrumb.blade.php`
- `nav-link.blade.php`
- `sidebar-link.blade.php`
- `topbar-profile-menu.blade.php`
- `mobile-drawer-toggle.blade.php`
- `filter-bar.blade.php`
- `table-wrapper.blade.php`
- `section-block.blade.php`

Keep existing reusable atoms:

- `logo`, `button-primary`, `button-secondary`, `card`, `input`, `select`, `form-error`, `alert`

### 4.4 CSS organization

Update `resources/css/app.css` with ordered blocks:

1. Design tokens (existing root tokens, refined where needed)
2. Shared shell primitives (`.shell-*`, spacing scale, focus states)
3. Public shell styles (`.shell-public-*`)
4. App shell styles (`.shell-app-*`)
5. Admin shell styles (`.shell-admin-*`)
6. Transitional auth styles (`.shell-auth-*`, existing `.ai-form-*`)
7. Responsive breakpoints (public/app/admin grouped)

No per-page CSS files introduced in this task.

---

## 5. Exact navigation architecture

### 5.1 Public header navigation

Desktop menu targets (route-safe placeholders allowed where route missing):

- Acasă (`/`)
- Despre Boutique
- Creatori / Comercianți
- Evenimente / Showcase
- Cum funcționează
- Autentificare
- Creează cont

Rules:

- Use `x-ui.nav-link` with `isActive` from route-name matching.
- Links to unavailable pages may remain disabled/placeholder style only if route is missing; do not create business pages in this task.
- Auth CTAs always visible for guest context.

### 5.2 Public mobile navigation

- Off-canvas/drawer panel toggled via `x-ui.mobile-drawer-toggle`.
- Same information architecture as desktop, vertically stacked.
- Auth CTAs pinned near top of drawer.
- Drawer close action must be accessible via keyboard and visible button.

### 5.3 Authenticated app sidebar/topbar navigation

Sidebar groups:

- Base user: Panou de control, Profil, Date cont
- Merchant extension: Profil comerciant, Produse, Cereri/Comenzi, Evenimente, Abonament, Setări

Topbar:

- left: page title + breadcrumbs
- right: optional utility/search placeholder, profile menu, logout action

Access-safe rendering:

- Merchant-only entries render only for `role=merchant`.
- User-only entries render only for `role=user`.
- Status-sensitive links (future operational items) are display-gated by merchant status when needed, without changing backend authorization requirements.

### 5.4 Admin sidebar/topbar navigation

Sidebar baseline:

- Dashboard
- Comercianți
- Utilizatori
- Produse
- Comenzi
- Evenimente
- Promoții
- Abonamente
- Setări

Topbar:

- area title
- optional quick search placeholder
- profile menu + logout

Rules:

- Admin sidebar never appears in public/app shells.
- Admin menu density and width optimized for operational scans, not decorative presentation.

### 5.5 Profile menu and logout placement

- Public guest: no profile dropdown; auth CTAs in header.
- App shell: profile menu in topbar right, logout in dropdown or adjacent button.
- Admin shell: same topbar-right profile placement, admin context label required.

### 5.6 Active state handling

Single strategy:

- all navigation components receive `:active="request()->routeIs('pattern.*')"` or equivalent boolean
- active style tokenized in CSS (`.is-active`)
- no JS-only active state logic

---

## 6. Exact page structure contracts

### 6.1 Page container

Component: `x-ui.shell-page-container`

Responsibilities:

- consistent horizontal padding
- area-specific max width strategy
- vertical spacing baseline

Variants:

- `variant="public|app|admin"`

### 6.2 Page header

Component: `x-ui.shell-page-header`

Slots:

- `title` (required)
- `subtitle` (optional)
- `breadcrumbs` (optional)
- `actions` (optional)

### 6.3 Breadcrumbs

Component: `x-ui.breadcrumb`

Contract:

- accepts array/list of label+url items
- last item rendered as current page without link

### 6.4 Page actions

- right-aligned action slot inside `shell-page-header`
- supports buttons and dropdown action clusters
- no inline spacing hacks; use shared classes

### 6.5 Filter bar wrapper

Component: `x-ui.filter-bar`

Contract:

- wraps filter controls for admin/app list pages
- supports stacked layout on small screens
- includes clear area for primary/secondary filter actions

### 6.6 Table wrapper

Component: `x-ui.table-wrapper`

Contract:

- horizontal overflow handling for medium/small screens
- table toolbar slot (optional)
- empty state slot support

### 6.7 Section spacing rules

Global rhythm tokens:

- `--section-space-lg`, `--section-space-md`, `--section-space-sm`

Rules:

- Public uses larger spacing defaults.
- App uses medium spacing.
- Admin uses compact-medium spacing for density and readability.

---

## 7. Component system definition

### 7.1 Required new Blade components

1. `x-ui.nav-link`
   - standard top navigation link with active/disabled states.
2. `x-ui.sidebar-link`
   - sidebar row with icon slot and active indicator.
3. `x-ui.topbar-profile-menu`
   - reusable profile context + logout action surface.
4. `x-ui.mobile-drawer-toggle`
   - accessible trigger button for drawer menus.
5. `x-ui.shell-page-container`
   - max-width/padding wrapper by area variant.
6. `x-ui.shell-page-header`
   - title/breadcrumb/actions contract.
7. `x-ui.breadcrumb`
   - standardized breadcrumb rendering.
8. `x-ui.filter-bar`
   - responsive filter controls wrapper.
9. `x-ui.table-wrapper`
   - responsive table boundary + toolbar slot.
10. `x-ui.section-block`
   - section spacing utility for public storytelling blocks.

### 7.2 Naming conventions

- component files: kebab-case
- partial files: kebab-case
- layout files: kebab-case with `-shell` suffix for shell layouts
- CSS classes: `shell-{area}-{element}` convention

### 7.3 Responsibility split

Layout-level:

- global shell skeleton (header/sidebar/topbar/main/footer placement)
- drawer containers and backdrop region
- top-level slots (`content`, `page_actions`, `breadcrumbs`)

Component-level:

- repeated navigation row patterns
- page header rendering
- filter/table wrappers
- breadcrumb and profile menu fragments

---

## 8. Responsive architecture

Breakpoint contract:

- mobile: `< 768px`
- tablet: `768px - 1023px`
- desktop: `>= 1024px`

### 8.1 Public shell behavior

- desktop: full header nav + CTA group visible
- tablet: compressed nav (reduced spacing/labels when needed)
- mobile: drawer-only navigation, visible CTA actions

### 8.2 App shell behavior

- desktop: persistent sidebar + topbar
- tablet: collapsible sidebar with toggle
- mobile: hidden sidebar, drawer overlay opened via topbar toggle

### 8.3 Admin shell behavior

- desktop: persistent admin sidebar + wide main
- tablet: collapsible sidebar + compact filter rows
- mobile: drawer sidebar and stacked filter controls

### 8.4 Drawer/sidebar strategy

- one drawer pattern per shell area (public/app/admin), shared toggle component
- overlay/backdrop with explicit close button
- close on ESC and backdrop click if JS is available; if JS absent, keep non-broken fallback (menu closed by default)

### 8.5 Table overflow/filter stacking

- tables always inside `x-ui.table-wrapper` with horizontal scroll container
- admin filter bars stack vertically under `768px`
- action buttons wrap rather than overflow

---

## 9. Migration order and compatibility

### 9.1 Migration sequence

1. Introduce base/shell layouts + shell partials (no page migration yet).
2. Migrate public pages:
   - `welcome`
   - `public/dashboard/index`
3. Migrate authenticated non-admin pages:
   - `merchant/dashboard/index` + status partials
   - `public/dashboard/index` (if still using auth path)
4. Migrate admin pages:
   - `admin/dashboard/index`
   - `admin/merchants/index`
   - `admin/merchants/show`
5. Keep auth entry pages on transitional `layouts/auth` path.

### 9.2 Transition compatibility rules

- `layouts/auth` must remain stable while shells are introduced.
- Existing route names and controller flows remain unchanged.
- Existing forms (login/register/password reset/onboarding/activation actions) keep current submission targets.

### 9.3 Anti-break strategy

- migrate page-by-page, not mass replacement
- preserve existing `@section` contracts while adding new ones
- ensure missing optional slots do not break rendering

---

## 10. Visual differentiation rules (strict)

### 10.1 Public shell (premium)

Must have:

- larger breathing space
- stronger brand emphasis
- elegant header/footer presence
- storytelling-friendly section rhythm

### 10.2 Authenticated app shell (balanced)

Must have:

- structured, work-friendly hierarchy
- moderated decorative density
- clear page orientation (sidebar + page header)

### 10.3 Admin shell (practical)

Must have:

- high readability and dense-but-clean information surfaces
- obvious filter/list/action hierarchy
- minimal decorative styling

### 10.4 Fail conditions

Implementation fails architecture if:

1. Public, app, and admin shells are visually near-identical.
2. Admin uses decorative/public styling that reduces operational clarity.
3. Public loses premium brand tone and resembles admin utility UI.
4. Inline per-page style hacks are used to force differentiation.

---

## 11. Wireframe/mockup architecture requirement

Wireframes are required before implementation to reduce layout rework.

### 11.1 Required screens

1. Public homepage shell frame
2. Public generic content shell page
3. Public list shell page (creators/comercianți style listing frame)
4. Authenticated dashboard shell
5. Admin list shell page (filter + table + actions)
6. Admin detail/review shell page
7. Mobile navigation states for public/app/admin shells

### 11.2 Storage location

Create and store under:

- `tasks/artifacts/wireframes/006-application-shells-and-navigation-system/`

Recommended files:

- `public-home-shell.md` (or image)
- `public-generic-shell.md`
- `public-list-shell.md`
- `app-dashboard-shell.md`
- `admin-list-shell.md`
- `admin-detail-shell.md`
- `mobile-nav-states.md`

---

## 12. Implementation guardrails

1. No business logic expansion: shell/nav only.
2. No route restructuring except minimal nav-safe aliasing if strictly required.
3. No inline Blade styles.
4. Reusable component-first implementation; avoid one-off markup repetition.
5. CSS must continue loading via `asset('css/app.css')` in all shell layouts.
6. Do not remove/rename existing critical route names used by forms and redirects.
7. Do not move authorization logic from middleware/services into views.
8. Keep all user-facing copy in Romanian.

---

## 13. Files to create/update (implementation contract)

### Create

- `resources/views/layouts/base.blade.php`
- `resources/views/layouts/public-shell.blade.php`
- `resources/views/layouts/app-shell.blade.php`
- `resources/views/layouts/admin-shell.blade.php`
- `resources/views/partials/shell/public-header.blade.php`
- `resources/views/partials/shell/public-footer.blade.php`
- `resources/views/partials/shell/public-mobile-nav.blade.php`
- `resources/views/partials/shell/app-sidebar.blade.php`
- `resources/views/partials/shell/app-topbar.blade.php`
- `resources/views/partials/shell/app-mobile-nav.blade.php`
- `resources/views/partials/shell/admin-sidebar.blade.php`
- `resources/views/partials/shell/admin-topbar.blade.php`
- `resources/views/partials/shell/admin-mobile-nav.blade.php`
- `resources/views/components/ui/nav-link.blade.php`
- `resources/views/components/ui/sidebar-link.blade.php`
- `resources/views/components/ui/topbar-profile-menu.blade.php`
- `resources/views/components/ui/mobile-drawer-toggle.blade.php`
- `resources/views/components/ui/shell-page-container.blade.php`
- `resources/views/components/ui/shell-page-header.blade.php`
- `resources/views/components/ui/breadcrumb.blade.php`
- `resources/views/components/ui/filter-bar.blade.php`
- `resources/views/components/ui/table-wrapper.blade.php`
- `resources/views/components/ui/section-block.blade.php`

### Update

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/public/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/partials/*.blade.php` (as needed for shell contracts)
- `resources/views/admin/dashboard/index.blade.php`
- `resources/views/admin/merchants/index.blade.php`
- `resources/views/admin/merchants/show.blade.php`
- `resources/css/app.css`

Optional minimal routing update (only if required for nav safety):

- `routes/web.php`

---

## 14. Handoff requirements

Task-writer should produce executable task instructions directly from this architecture, preserving:

- exact shell file structure
- component boundaries
- navigation/access-safe rules
- responsive contracts
- migration order and compatibility path

Reviewer must fail the task if shell boundaries blur, if route/flow regressions occur, or if one-off non-reusable styling is introduced.
