# Planning — Task 002 (Design System & Redesign Existing Pages)

## Purpose
Create an implementation-ready plan to extract UI tokens from the design source-of-truth, build a reusable Blade-based design system (layouts + Blade components + styling strategy), and redesign the existing specified pages to match it—without changing authentication/business logic.

## Guiding constraints to preserve
- Keep to Laravel + Blade only (no SPA frameworks).
- Avoid business-logic changes; prefer structural layout/component integration only if necessary.
- Any user-facing strings added/updated must be in Romanian and aligned with project terminology.
- New UI should be implemented as reusable Blade components (avoid one-off inline styles in pages).

## Phase 0 — Repo alignment & discovery (setup)
### Goal
Establish what currently exists for styling, layouts, and component patterns before adding design-system structure.
### Steps
1. Inspect existing view structure and current auth/welcome/dashboard pages:
   - `resources/views/layouts/auth.blade.php`
   - `resources/views/welcome.blade.php`
   - `resources/views/public/auth/*`
   - `resources/views/merchant/auth/*`
   - `resources/views/admin/auth/*`
   - `resources/views/*/dashboard/index.blade.php`
2. Inspect existing CSS/asset pipeline:
   - Check `resources/css/*` and any existing Vite entry imports (e.g., `resources/js/*`, `resources/css/app.css`, etc.).
   - Identify whether the project uses utility classes already (Tailwind/other) or pure CSS.
3. Inspect existing Blade component conventions (if any):
   - `resources/views/components/*`
4. Read relevant project docs for UI terminology and rules:
   - `docs/BOUTIQUE-GLOSSARY.md`
   - `docs/ARCHITECTURE.md`
5. Produce an internal “UI mapping sheet”:
   - Current class names used on auth cards/buttons/inputs/messages vs expected token equivalents from the design source.
### Dependencies
- None.
### Likely files/folders touched
- Read-only first; no code changes in this phase.
### Risks / ambiguities
- The project may already have a CSS framework or pre-existing class conventions; incorrectly replacing it could break visual consistency or introduce style conflicts.

## Phase 1 — Design Source-of-Truth extraction (tokens & primitives)
### Goal
Extract a minimal set of tokens and primitives needed for the required pages (colors, typography, spacing, buttons, inputs, cards, alerts/messages, layout patterns).
### Steps
1. Locate and review the design source content:
   - `docs/design-source-of-truth/website-parent`
   - Focus on images (logo), styles/colors, spacing scales, typography rules, and component samples.
2. Build a token inventory:
   - Colors: primary/accent, backgrounds, text, border, link hover states.
   - Typography: base font, sizes/weights for headings/subheadings/body.
   - Spacing: consistent padding/margin scale (e.g., 4/8/12/16…).
3. Define “component contracts” (what props/slots each UI component needs):
   - Buttons: variants (primary/secondary/outline), labels via slot.
   - Inputs: type/name/value/label/error rendering pattern.
   - Card: title slot and body slot (generic).
   - Alert: variant (success/error) slot content.
4. Capture reusable layout patterns:
   - Container width and center alignment rules.
   - Header/nav/footer presence expectations (or MVP “simple header/footer” if nav is not specified).
5. Decide the MVP-simple styling integration strategy:
   - Prefer one central CSS entry and classes used by components/layouts.
6. Record extracted tokens in a developer-friendly format inside the planning doc:
   - (If token duplication is unacceptable, mirror tokens in CSS variables and reference them by class names in components.)
### Dependencies
- Phase 0 discovery complete (so token mapping matches current integration reality).
### Likely files/folders touched
- Read-only, but may create a temporary internal note in the planning doc.
### Risks / ambiguities
- The design source might express sizes/colors in a different format (e.g., screenshots with hex hints); ensure there’s a consistent mapping to CSS variables/classes.
- If the design source specifies complex navigation, this task explicitly says not to implement it unless already present—confirm the navigation requirements from the design source.

## Phase 2 — Build design system foundation (layouts & components)
### Goal
Create reusable Blade layouts and UI components that can render the required pages without adding page-specific styling logic.
### Steps
1. Create/confirm layout structure:
   - `resources/views/layouts/app.blade.php`:
     - Implement a base page shell with header/footer placeholders and a `{{ $slot }}` or `@yield` content area.
   - Update `resources/views/layouts/auth.blade.php`:
     - Make it the dedicated auth layout, but ensure it uses design-system components and tokens.
2. Implement UI Blade components (minimum set required by task; keep props/slots minimal):
   - `resources/views/components/ui/button-primary.blade.php`
   - `resources/views/components/ui/button-secondary.blade.php`
   - `resources/views/components/ui/input.blade.php`
   - `resources/views/components/ui/select.blade.php` (only if needed by redesigned pages)
   - `resources/views/components/ui/form-error.blade.php`
   - `resources/views/components/ui/card.blade.php`
   - `resources/views/components/ui/alert.blade.php`
   - `resources/views/components/ui/logo.blade.php`
3. Establish component usage guidelines:
   - Components should not contain hard-coded business strings; accept via slots/props.
   - Components should render consistent markup for spacing and borders.
4. Integrate logo:
   - Load `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg` into `logo` component in a way compatible with the project asset pipeline:
     - Prefer copying the SVG into `public/` or referencing it as a static asset (choose the approach that matches existing conventions discovered in Phase 0).
5. Define message/error rendering flow:
   - Ensure there’s a single standard for displaying:
     - Success status messages (likely `session('status')`)
     - Validation errors under fields (via `form-error`).
### Dependencies
- Phase 1 token/component contract decisions (so components use the correct class names/tokens).
### Likely files/folders touched
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/components/ui/*`
- Potential asset placement under `public/` if the SVG needs to be served.
### Risks / ambiguities
- Asset serving for SVG might differ from existing project conventions; ensure the logo renders correctly in dev and compiled builds.
- Component contracts (props/slots) must align with existing form markup in auth pages; otherwise redesign will require touching many pages.

## Phase 3 — Styling integration (CSS strategy + token application)
### Goal
Apply the extracted tokens to the reusable layouts/components via a consistent CSS strategy (variables/classes), avoiding inline styles in pages.
### Steps
1. Decide the concrete CSS location and include path:
   - If there is an existing global stylesheet, extend it with design-system classes.
   - Otherwise add a dedicated stylesheet under `resources/css/` and include it from the relevant layout.
2. Implement CSS tokens:
   - Add CSS variables for colors and spacing scale (or map them directly into component classes).
3. Implement base typography and common UI primitives:
   - Body font, heading sizes/weights, link styles, focus outlines.
4. Implement component classes:
   - `btn-primary`, `btn-secondary`, `btn-outline` (or component-specific classes)
   - `ui-input`, `ui-select`
   - `ui-card`, `ui-alert-success`, `ui-alert-error`
   - Error text styles used by `form-error`
5. Ensure focus/hover states:
   - Verify visible focus rings for accessibility.
6. Validate no inline style usage was introduced in page files:
   - Ensure the redesign uses only class-based styling from components/layouts.
### Dependencies
- Phase 2 components and class naming decisions.
### Likely files/folders touched
- `resources/css/*` (new or updated)
- `resources/views/layouts/app.blade.php` and `auth.blade.php` (to include CSS/entries if needed)
### Risks / ambiguities
- The project might already include CSS; adding conflicting base styles can break existing pages. Keep new styles scoped to design-system classes where possible.
- If the project uses Vite, ensure the stylesheet is included via the correct entrypoint so changes appear in browser.

## Phase 4 — Redesign target pages to use the design system (no logic changes)
### Goal
Replace unique page-specific styling with design-system layouts/components for the exact list of required pages.
### Steps (recommended order)
1. Welcome page:
   - Update `resources/views/welcome.blade.php` to use `layouts.app.blade.php` and design-system components.
2. User auth pages:
   - Update `resources/views/public/auth/login.blade.php`
   - Update `resources/views/public/auth/register.blade.php`
   - Ensure:
     - Forms use `ui.input` and display field errors via `ui.form-error`.
     - Buttons use the correct button components.
     - Card container uses `ui.card` with standard spacing.
     - Alerts/messages use `ui.alert`.
3. Merchant auth pages:
   - Update `resources/views/merchant/auth/login.blade.php`
   - Update `resources/views/merchant/auth/register.blade.php`
4. Admin auth page:
   - Update `resources/views/admin/auth/login.blade.php`
5. Dashboard placeholders:
   - Update `resources/views/public/dashboard/index.blade.php`
   - Update `resources/views/merchant/dashboard/index.blade.php`
   - Update `resources/views/admin/dashboard/index.blade.php`
### Dependencies
- Phase 3 styling and class availability.
- The components/layouts from Phase 2 must be stable before page integration.
### Likely files/folders touched
- `resources/views/welcome.blade.php`
- `resources/views/public/auth/*`
- `resources/views/merchant/auth/*`
- `resources/views/admin/auth/*`
- `resources/views/public/dashboard/index.blade.php`
- `resources/views/merchant/dashboard/index.blade.php`
- `resources/views/admin/dashboard/index.blade.php`
### Risks / ambiguities
- Avoid altering route names, controller logic, or request validation behavior; focus on markup only.
- Ensure Romanian text consistency:
  - If the auth pages currently have correct Romanian, redesign should not introduce new English strings.
  - If components include labels or helper text, ensure they are not hard-coded in the component (use slots instead).
- Some pages may have special markup (e.g., recaptcha blocks). Redesign must preserve conditional widget/script behavior.

## Phase 5 — Integration validation (smoke tests + regression checks)
### Goal
Confirm all acceptance criteria: functional flows remain intact and all redesigned pages visually align with the tokens.
### Steps
1. Manual smoke test of auth flows:
   - user login/register
   - merchant login/register
   - admin login
2. Validation/error rendering:
   - Trigger field validation errors and confirm `form-error` displays under correct fields.
3. Success/status messages:
   - Confirm session `status` or equivalent messages render with `ui.alert`.
4. Dashboard placeholder access:
   - Confirm each dashboard renders with the design-system container/components.
5. Visual regression spot-check:
   - Verify consistent button/input/card styles across all target pages.
6. Check for “inline style drift”:
   - Inspect modified pages for any `style="..."` attributes; remove if present.
### Dependencies
- Phase 4 page redesign complete.
### Likely files/folders touched
- Read-only; fix small issues if found (rerun Phase 4 micro-adjustments).
### Risks / ambiguities
- If CSS loads slowly or not at all, pages may render unstyled; confirm stylesheet inclusion from the proper layout.

## Phase 6 — Polish and documentation alignment
### Goal
Ensure the implementation is maintainable and future tasks can reuse the design system.
### Steps
1. Ensure components are documented via lightweight inline comments or a short section in the planning doc:
   - What slots/props each component expects.
2. Confirm naming consistency:
   - Buttons/inputs/cards follow a predictable naming scheme under `components/ui`.
3. Optional (MVP-safe) cleanup:
   - If some components (e.g., select) are not used by redesigned pages, keep them but ensure they don’t complicate future development.
4. Verify welcome/dashboard/dashboard auth pages match Romanian requirement.
### Dependencies
- Phase 5 validation complete.
### Likely files/folders touched
- Possibly `resources/views/components/ui/*` small adjustments.
### Risks / ambiguities
- Over-polishing can accidentally introduce behavioral changes; keep polish limited to UI markup/classes.

## Recommended execution order for the implementer
1. Phase 0 (repo discovery)
2. Phase 1 (design token extraction + token/class naming decisions)
3. Phase 2 (layouts + Blade components)
4. Phase 3 (CSS token application + stylesheet inclusion)
5. Phase 4 (redesign the target pages in the order: welcome -> user auth -> merchant auth -> admin auth -> dashboards)
6. Phase 5 (smoke tests + regression checks)
7. Phase 6 (polish + documentation alignment)

## Key risks checklist (watch during implementation)
- Styling conflicts with existing CSS or class naming conventions.
- Components that accidentally hard-code business strings instead of using slots/props.
- Missing preservation of conditional blocks inside auth pages (e.g., recaptcha UI conditions).
- Inline styles added during redesign (must be avoided).
- Logo asset not accessible in the runtime environment (wrong location/URL).
- Token mapping ambiguity from the design source-of-truth (inconsistent spacing/typography between components).

