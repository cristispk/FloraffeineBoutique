# Planning — 006: Application Shells and Navigation System

## Objective

Define and sequence the implementation of a reusable, responsive shell and navigation foundation for:

- public guest area
- authenticated app area (user / merchant)
- admin area

This task must improve UI maturity without expanding business logic scope. It must keep all existing flows operational and prepare stable layout primitives for upcoming roadmap work.

---

## Context alignment used for this planning

Planning is aligned with:

- `docs/UI_SHELL_BLUEPRINT.md` (target shell behaviors and visual separation)
- `docs/ARCHITECTURE.md` and `docs/SCOPE.md` (strict public/merchant/admin boundaries; status-aware access)
- `docs/ROADMAP.md` (incremental execution and dependency discipline)
- `docs/DEVELOPMENT_WORKFLOW.md` and `docs/AGENT_WORKFLOW.md` (planner output for handoff)
- `docs/DESIGN_CONTINUITY.md`, `docs/UI_VISUAL_STANDARDS.md`, `docs/UI_COMPONENTS_REFERENCE.md` (design fidelity, no ad-hoc styles, component reuse)
- current implemented structure (`routes/web.php`, `resources/views/layouts/*`, auth/dashboard/admin pages)
- prior workflow artifacts for Tasks 003/004/005 (patterns and quality baseline)

Note: no project-level `.cursor/skills/*` artifacts were identified in the repository; planning is based on mandatory docs and existing codebase state.

---

## Scope boundary (strict)

### In scope

- Shell architecture and Blade layout separation
- Navigation systems (desktop/tablet/mobile patterns)
- Base page-header and section container patterns
- Foundational admin filter/table wrappers (layout-level only)
- Responsive behavior and access-aware nav visibility

### Out of scope

- New business modules (products, checkout, payouts, promotions, events logic)
- Domain behavior changes beyond link wiring needed for shell nav
- Full redesign of every existing page
- Rewriting the blueprint document itself

---

## Ordered implementation phases

### Phase 1 — Shell architecture split and contract definition

Goal: establish explicit shell contracts per area before visual expansion.

Concrete steps:

1. Introduce dedicated area layouts:
   - public guest shell layout
   - authenticated app shell layout (user/merchant)
   - admin shell layout
2. Keep current `layouts/auth.blade.php` functional during transition (temporary compatibility path).
3. Define shared section contracts in Blade (`title`, `breadcrumbs`, `page_actions`, optional side actions).
4. Add shell-level body classes/data attributes to support area-specific styling without inline hacks.

Deliverable outcome:

- A stable layout API for future pages without duplicating nav/header/footer code.

---

### Phase 2 — Public guest shell foundation (premium brand-led)

Goal: replace basic guest framing with a premium public shell matching continuity docs.

Concrete steps:

1. Implement sticky public header with:
   - logo
   - primary nav links
   - right-side auth CTA group
2. Implement structured public footer (columns + legal/support links placeholders).
3. Add guest mobile off-canvas/drawer menu pattern and tablet-compressed nav state.
4. Introduce public content container primitives:
   - wide storytelling sections
   - constrained text container
   - hero/page-intro wrapper slot
5. Apply shell to current public landing/auth entry pages without changing business flow.

Deliverable outcome:

- Public area feels premium and brand-forward while preserving existing routes.

---

### Phase 3 — Authenticated app shell (user + merchant, middle visual tone)

Goal: create a work-oriented shell between public elegance and admin density.

Concrete steps:

1. Implement authenticated shell structure:
   - collapsible left sidebar
   - topbar with page title/breadcrumb slot
   - right-side profile/logout cluster
2. Define role-aware nav groups:
   - base user links
   - merchant links (status-safe visibility only; no new business logic)
3. Add responsive behavior:
   - desktop fixed sidebar
   - tablet collapsed/sidebar-toggle mode
   - mobile drawer navigation
4. Add reusable page header component with optional quick actions.
5. Migrate existing user/merchant dashboard and related auth-protected pages onto this shell.

Deliverable outcome:

- Authenticated area gets consistent orientation and navigation for current + future modules.

---

### Phase 4 — Admin shell (practical operations-first)

Goal: build an organized administrative shell optimized for filtering/list/action workflows.

Concrete steps:

1. Implement admin-specific shell variant:
   - practical sidebar taxonomy
   - utility topbar
   - wide content container
2. Add admin page structure primitives:
   - filter bar wrapper
   - table toolbar wrapper
   - list/detail page header pattern
3. Apply to current admin dashboard and merchant management pages.
4. Ensure admin visual language remains distinct from public shell (less decorative, more dense/scannable).

Deliverable outcome:

- Admin pages become operationally coherent and ready for upcoming table-heavy tasks.

---

### Phase 5 — Navigation behavior hardening and responsive QA

Goal: finalize cross-area navigation consistency and ensure non-breaking responsive behavior.

Concrete steps:

1. Active-state highlighting and current-route handling for all area menus.
2. Access-safe nav rendering:
   - no admin links outside admin context
   - merchant-only items gated by role/status-aware conditions
3. Keyboard/focus basics for menus, dropdowns, and drawer toggles.
4. Verify breakpoints across representative pages in each area.
5. Resolve overflow/truncation/sidebar stacking issues.

Deliverable outcome:

- Navigation is reusable, predictable, and responsive-ready for subsequent tasks.

---

### Phase 6 — Foundation handoff hardening

Goal: lock conventions for implementer follow-up and avoid style drift.

Concrete steps:

1. Document shell usage conventions in concise code comments and Blade structure patterns.
2. Ensure repeated UI patterns are componentized (no one-off inline style blocks).
3. Confirm CSS bundle availability on shell pages (no dependency on missing artifacts).
4. Preserve backward compatibility for routes/pages not yet migrated by using transitional wrappers where needed.

Deliverable outcome:

- Future page tasks can plug into shell primitives with minimal rework.

---

## Dependencies between phases

```text
Phase 1 (layout contracts)
   -> Phase 2 (public shell)
   -> Phase 3 (authenticated shell)
   -> Phase 4 (admin shell)
   -> Phase 5 (navigation hardening + responsive QA)
   -> Phase 6 (handoff hardening)
```

Key dependency notes:

- Phase 1 is mandatory before any shell rollout.
- Phases 2, 3, and 4 can be developed in parallel only after contract freeze, but should be integrated in order: public -> authenticated -> admin to reduce regression risk.
- Phase 5 must run after all area shells are wired.
- Phase 6 runs last to stabilize conventions.

---

## Likely files/folders to create or update

### Layouts / partials / components

- `resources/views/layouts/app.blade.php` (public shell baseline refactor)
- `resources/views/layouts/auth.blade.php` (transition compatibility, then reduced scope)
- `resources/views/layouts/public-shell.blade.php` (new)
- `resources/views/layouts/app-shell.blade.php` (new user/merchant shell)
- `resources/views/layouts/admin-shell.blade.php` (new)
- `resources/views/components/ui/` (new shell/navigation components, e.g. nav item, sidebar section, page header, breadcrumb, profile dropdown, mobile menu trigger)
- `resources/views/partials/` or `resources/views/components/ui/navigation/` for menu composition

### Styles

- `resources/css/app.css` (area tokens, shell structure, responsive behavior, nav states)

### Route-aware wiring

- `routes/web.php` (only if named routes/sections require minor nav-friendly adjustments; no business flow expansion)

### Existing page migrations to new shells

- `resources/views/welcome.blade.php`
- `resources/views/public/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/index.blade.php` and its status partials
- `resources/views/admin/dashboard/index.blade.php`
- `resources/views/admin/merchants/index.blade.php`
- `resources/views/admin/merchants/show.blade.php`
- relevant auth pages that currently rely on `layouts/auth.blade.php`

---

## Architectural and visual risks

1. Shell leakage risk (mixing public/admin patterns) if area-specific layout boundaries are not enforced.
2. Regression risk on existing auth/dashboard flows during layout migration.
3. Over-scoping risk if implementer starts adding business features while wiring new nav.
4. Responsive complexity risk around sidebar/drawer interactions and table overflow behavior.
5. Visual drift risk if parent design tokens are approximated instead of mapped exactly.
6. Accessibility/focus risk in dropdowns and mobile navigation states.

Mitigations:

- Freeze layout contracts in Phase 1.
- Migrate representative pages incrementally with smoke checks.
- Keep nav links route-driven and status-aware without adding new business branches.
- Enforce no inline Blade styles and component reuse per visual standards.

---

## Responsive considerations (mandatory)

- Public:
  - desktop full nav + CTA
  - tablet compressed nav
  - mobile off-canvas with clear auth actions
- Authenticated app:
  - desktop fixed sidebar + topbar
  - tablet collapsible sidebar
  - mobile drawer + simplified topbar
- Admin:
  - desktop wide list/table layout
  - tablet compact filter controls + horizontal table scroll wrappers
  - mobile fallback for critical lists (stack/condensed cards only where needed)
- Global:
  - no horizontal viewport overflow
  - nav toggles remain keyboard reachable
  - touch targets remain usable on mobile

---

## Admin vs public differentiation requirements

### Public (premium, brand-led)

- richer spacing rhythm, storytelling structure, stronger visual branding, curated CTA hierarchy

### Authenticated user/merchant (balanced)

- moderated branding, clearer structure, utility-forward but still warm and polished

### Admin (practical, organized)

- high legibility, predictable hierarchy, action/table/filter-first presentation, minimal decorative elements

Non-negotiable:

- do not reuse identical shell tone across all three areas
- do not introduce disconnected visual language vs parent Floraffeine continuity

---

## Recommended execution order for implementer

1. Build Phase 1 shell contracts and transitional compatibility.
2. Implement public shell and migrate `welcome` + public dashboard.
3. Implement authenticated app shell and migrate user/merchant dashboards.
4. Implement admin shell and migrate admin dashboard + merchant list/detail.
5. Add responsive navigation states and active-link/access-safe rendering.
6. Run visual + runtime verification on representative pages for each area.
7. Final cleanup: remove redundant markup, keep only reusable primitives.

---

## Mockup / wireframe recommendation (before implementation)

Yes: lightweight wireframe artifacts are recommended before coding to reduce rework.

### Must-create wireframes

1. Public homepage shell frame (header, hero intro slot, section rhythm, footer)
2. Public generic content page shell
3. Authenticated dashboard shell (sidebar + topbar + page header)
4. Admin list page shell (filters + table + row actions)
5. Admin detail/review page shell
6. Mobile navigation states for all 3 shells (public menu, app drawer, admin drawer)

### Fidelity guidance

- low-to-mid fidelity wireframes are enough for structure and spacing decisions
- exact color/token mapping remains tied to design source-of-truth during implementation

---

## Acceptance-oriented checkpoints for downstream roles

- Reviewer (pre-implementation): validate no scope creep beyond shell/navigation.
- Implementer: deliver reusable Blade shell/components, no inline style hacks.
- Tester: verify route access behavior unchanged and navigation responsive behavior.
- Visual auditor (mandatory for this UI task): verify rendered fidelity, CSS loading, and shell differentiation across public/app/admin.

---

## Planning outcome

Task 006 should be executed as a shell-first foundation task, not a feature-module task. The result must be a reusable, responsive UI infrastructure that cleanly separates public, authenticated, and admin experiences while keeping current flows intact and ready for subsequent roadmap modules.
