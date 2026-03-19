# Planning — Task 003 (Visual Correction: Design Fidelity)

## Intent
Re-align the current design-system implementation to match the parent Floraffeine design source-of-truth with strict visual fidelity (no approximation). This task is UI-only and targets the visible issues:
- oversized logo
- poor form formatting
- weak spacing rhythm
- weak hierarchy
- design not matching the Floraffeine source-of-truth

## Hard fidelity requirements (must)
1. The implementation must match the parent source-of-truth *exactly* using:
   - `docs/design-source-of-truth/website-parent/css/style.css`
   - `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
   - relevant HTML structure/patterns from:
     - `docs/design-source-of-truth/website-parent/index.html`
2. No approximation allowed for:
   - color palette
   - logo sizing + placement
   - form spacing + alignment
   - button/input/card appearance
   - visual hierarchy
   - page proportions
3. No business logic changes.
4. Laravel + Blade only.

## Scope (UI-only; likely changes)
The corrective work is expected to involve some/all of:
1. Styling:
   - `resources/css/app.css`
2. Layouts:
   - `resources/views/layouts/app.blade.php`
   - `resources/views/layouts/auth.blade.php`
3. UI components:
   - `resources/views/components/ui/logo.blade.php`
   - `resources/views/components/ui/input.blade.php`
   - `resources/views/components/ui/button-primary.blade.php`
   - `resources/views/components/ui/button-secondary.blade.php`
   - `resources/views/components/ui/card.blade.php`
   - (optionally) `resources/views/components/ui/alert.blade.php` and `form-error.blade.php` if hierarchy/spacing differs
4. Target pages (markup only; no logic):
   - `resources/views/welcome.blade.php`
   - `resources/views/public/auth/login.blade.php`
   - `resources/views/public/auth/register.blade.php`
   - `resources/views/merchant/auth/login.blade.php`
   - `resources/views/merchant/auth/register.blade.php`
   - `resources/views/admin/auth/login.blade.php`
   - `resources/views/public/dashboard/index.blade.php`
   - `resources/views/merchant/dashboard/index.blade.php`
   - `resources/views/admin/dashboard/index.blade.php`

## Dependencies between phases/steps
Phase 1 (source extraction) must complete before Phase 2 (CSS fidelity mapping). Phase 2 must complete before Phase 3 (markup/component updates), because component markup depends on exact selector targets in `style.css`.

## Phase 1 — Extract exact fidelity specifications (source extraction)
### Goal
Produce a deterministic “fidelity map” of exact selectors and values from the parent design source-of-truth that correspond to the currently-visible issues.
### Steps
1. Logo sizing + placement extraction:
   - From `style.css`, extract the *exact* rules for:
     - `.brand img` (height/width)
     - any `.brand` wrapper alignment rules (e.g. `.brand` display/gap)
   - From `index.html`, extract the HTML pattern used:
     - `<a class="brand" ...><img src="img/floraffeine-logo.svg" ... /></a>`
2. Typography + palette extraction:
   - From `style.css`, extract the final computed rules (not just variables) affecting:
     - body/html background
     - text color
     - font families (`--ff-sans`, `--ff-serif`) and where they apply
3. Form inputs extraction:
   - Identify which parent form styling best matches auth forms (based on visible inputs/labels/buttons):
     - candidate selectors:
       - `.f-input` (input size, border, radius, padding)
       - `.ai-form .input` and `.ai-form .label` (input + label typography + focus)
   - Extract exact values for:
     - border radius
     - padding (left/right and top/bottom)
     - height
     - focus border + focus box-shadow
4. Button extraction:
   - Extract exact rules for:
     - `.btn` base typography
     - `.btn-dark`, `.btn-theme`, `.btn-outline-dark` for primary/secondary variants
5. Card/layout extraction for auth shell:
   - Extract exact card/frame rules relevant to the auth card:
     - candidate selectors:
       - `.ai-form .form-card` (card background, radius, border, padding, shadow)
       - any other “card-like” selectors used for forms
   - Extract exact spacing/rhythm selectors:
     - `gap` between inputs/labels (e.g. `.ds-field` equivalent)
     - label margin-bottom rules
6. Output a fidelity map document section (inside this planning doc) listing:
   - Selector → exact values → which component/page it will control
### Dependencies
- None.
### Likely files touched
- Read-only inspection of:
  - `docs/design-source-of-truth/website-parent/css/style.css`
  - `docs/design-source-of-truth/website-parent/index.html`
  - `docs/design-source-of-truth/website-parent/img/floraffeine-logo.svg`
### Risks / ambiguities
- The parent design may not have an “auth page” exact analogue; if multiple candidates exist (`.f-input` vs `.ai-form .input`), selection must be justified by matching the current auth UI layout structure.
- Bootstrap utilities (e.g. `rounded-pill`) exist in parent `index.html`; verify whether equivalent CSS must be replicated in our app for exact matching.

## Phase 2 — CSS fidelity mapping (replace approximations with exact rules)
### Goal
Modify `resources/css/app.css` so that our design-system output matches the parent CSS *exactly* for all auth/welcome/dashboard UI elements.
### Steps (concrete)
1. Establish exact CSS source alignment:
   - Implement the extracted palette variables and typography settings *using the same values* as in `style.css`.
   - Ensure the rendered page fonts and colors match the parent final computed rules.
2. Replace current `ds-*` approximations:
   - Update `ds-*` class definitions so each one is a 1:1 mapping to the corresponding parent selector.
   - For example, ensure:
     - input radius/padding/height/focus ring match the chosen parent rule set (`.f-input` or `.ai-form .input`)
     - button appearance matches `.btn` + exact variant class (`.btn-theme`/`.btn-dark`/`.btn-outline-dark`)
     - card appearance matches the exact card-like selector used in the fidelity map (`.ai-form .form-card` if chosen)
3. Logo fidelity:
   - Ensure the `<img>` used by `x-ui.logo` is styled to match `.brand img` exactly.
   - If oversize is caused by a wrapper, move wrapper classes (or add/remove them) so that only the `.brand` rules affect sizing.
4. Spacing rhythm fidelity:
   - Ensure spacing between label/input/error/CTA matches the parent:
     - label margin-bottom
     - field gap / vertical rhythm
     - button margin-top (if any)
5. Remove temporary compatibility aliases that cause visual mismatch:
   - If legacy alias CSS like `.btn`/`.field` conflicts with fidelity mappings, update or remove it so the final look is controlled by exact fidelity classes.
6. Create explicit “fidelity check selectors” in CSS comments:
   - e.g. a comment above `.ds-input` stating which parent selector it mirrors.
### Dependencies
- Phase 1 fidelity map must be finalized.
### Likely files to update
- `resources/css/app.css`
### Risks / ambiguities
- Tailwind’s preflight may interfere with base styles; ensure your fidelity rules win via specificity or correct ordering.
- Importing too much of parent CSS could bring unexpected selectors; stick to exact selector mapping for the components in scope unless exact full import is explicitly required by fidelity checks.

## Phase 3 — Component + markup re-alignment (match the parent HTML pattern)
### Goal
Adjust Blade layouts and UI components so the produced HTML matches the parent structural patterns needed for exact CSS targeting.
### Steps (concrete)
1. Logo markup fix (oversized logo):
   - Update `resources/views/layouts/app.blade.php` and/or `resources/views/components/ui/logo.blade.php` so the rendered logo markup matches the parent:
     - `<a class="brand"> <img ... /> </a>`
   - Ensure logo is placed in the correct container/positioning model and does not inherit conflicting sizes.
2. Form structure fix (poor form formatting + weak spacing/hierarchy):
   - Update auth page markup and/or components so the DOM structure needed by the parent selectors is present.
   - Concretely:
     - labels should use the exact class expected by the chosen parent rule set (`.label` from `.ai-form` or equivalent)
     - inputs should use the exact class expected by the chosen parent rule set (`.f-input` or `.ai-form .input`)
     - buttons should have the exact combination:
       - `.btn` + `.btn-theme` or `.btn-dark` as selected
       - any required shape utility classes (replicated if missing)
3. Card wrapper alignment:
   - Ensure the auth card wrapper matches the parent card selector’s expected structure (e.g. `.ai-form .form-card` inside `.ai-form` if that rule set is used).
4. Error/alert hierarchy:
   - Ensure global errors and field errors visually match parent message patterns:
     - if parent uses a specific alert/error class pattern, map to it
     - ensure spacing from field to error message is exact.
5. Update the `x-ui.*` components to enforce the final markup:
   - Avoid relying on page markup to apply critical classes; components should output the exact required structure/classes by default.
### Dependencies
- Phase 2 CSS fidelity mapping must exist, so markup can be aligned to correct classes.
### Likely files to update
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/components/ui/logo.blade.php`
- `resources/views/components/ui/input.blade.php`
- `resources/views/components/ui/button-primary.blade.php`
- `resources/views/components/ui/button-secondary.blade.php`
- `resources/views/components/ui/card.blade.php`
- auth page templates and dashboard placeholders (markup cleanup)
### Risks / ambiguities
- Component consumers may exist beyond Task 002 scope; this task must limit its markup/CSS changes to the auth/welcome/dashboard pages/components used there.

## Phase 4 — Strict design-fidelity checks (explicit, measurable)
### Goal
Provide a deterministic checklist that confirms “exact alignment” rather than “looks close”.
### Steps
1. Logo checks (must pass):
   - Confirm the rendered logo `<img>` computed height equals the value from `.brand img` in `style.css`.
   - Confirm the rendered logo is inside a wrapper element with the parent `.brand` class, so sizing is not overridden.
2. Color palette checks (must pass):
   - For each key element (body background, card background, text, input border, button background/text), confirm computed colors match the exact values or CSS variables from `style.css`.
3. Form spacing/alignment checks (must pass):
   - Confirm:
     - label margin-bottom equals the parent value
     - input height and padding match exactly
     - vertical spacing between fields matches the parent rhythm (including button spacing).
4. Button appearance checks (must pass):
   - Confirm:
     - buttons use `.btn` base typography settings (font family, letter-spacing, font-weight)
     - buttons use the exact variant styling selected (`.btn-theme` or `.btn-dark`)
     - hover/focus styles match (via CSS property inspection).
5. Visual hierarchy checks (must pass):
   - Compare title/subtitle sizes/weights/margins to the parent’s heading rhythm used in cards/frames.
6. Ad-hoc style ban (must pass):
   - Ensure:
     - no embedded `<style>` tags in Blade views
     - no `style="..."` inline attributes in modified views
     - no new one-off classes that duplicate styles instead of referencing the fidelity-mapped ones.
### Dependencies
- Phase 2 + Phase 3 must complete.
### Likely files checked
- Modified CSS + modified templates.
### Risks
- “Computed styles” require manual browser verification; if you cannot open devtools, the fidelity checks become approximate—this task forbids approximation, so devtools/browser inspection is expected.

## Recommended execution order for the implementer
1. Phase 1 (extract exact selectors/values from `style.css` and HTML patterns from `index.html`)
2. Phase 2 (update `resources/css/app.css` to make `ds-*` a 1:1 mapping to the extracted selectors)
3. Phase 3 (update layouts/components/markup so the produced DOM matches what the parent selectors expect)
4. Phase 4 (strict design-fidelity checks; resolve any mismatch before testing)

