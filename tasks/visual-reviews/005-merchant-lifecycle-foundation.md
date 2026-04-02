# Visual Review — Task 005 Merchant Lifecycle Foundation

## Scope

- Task: `tasks/005-merchant-lifecycle-foundation.md`
- Review / tests: referenced for scope only (no code review here).
- **Method:** Rendered HTML inspected via authenticated HTTP requests to `http://localhost:8080` (Docker), plus Blade structure review for layout/components/copy.
- **Focus:** Visible UI quality, design-system alignment, Romanian merchant-facing copy, absence of raw/unstyled pages.

---

## What was verified (visible / rendered)

### Asset loading (no “raw browser” pages)

For **merchant onboarding**, **merchant activation**, **merchant dashboard**, and **admin merchants index**, the returned HTML includes:

- `css/app.css` (Floraffeine design bundle)
- Bootstrap 5.3.3 (utilities / tables)
- Shared `layouts.auth` shell: `site-header`, `nav-glass`, `.brand` + logo, `.ai-form-section`, `.ai-form`, **`form-card`** via `<x-ui.card>`

**Conclusion:** New pages are **not** unstyled; they use the same global CSS path as the rest of the auth experience.

---

## Merchant onboarding (`/merchant/onboarding`)

- **Structure:** Uses the **form layout path** (`form_action`, `form_fields`, `form_actions`, `form_links`) inside `x-ui.card` → **`form-card`** + **`form-grid`** + **`form-actions`**, matching existing auth forms.
- **Controls:** `x-ui.input`, `x-ui.form-error`, `x-ui.button-primary` / `x-ui.button-secondary` (rounded-pill project buttons).
- **Readability:** Labels use `.label`; fields use `.field`; description uses `textarea.input` (aligned with text inputs).
- **Copy:** Titles and actions are **Romanian** (e.g. „Profil comerciant”, „Salvează ciorna”, „Trimite spre verificare”, „Înapoi la panou”).
- **Verdict:** Visually consistent with the current design system; readable hierarchy (title → subtitle → fields → actions → link).

---

## Merchant activation (`/merchant/activation`)

- **Structure:** Uses the **non-form** branch of `layouts.auth` (content inside `x-ui.card`), appropriate for informational copy + single confirm action.
- **Copy:** Romanian; clearly states billing is **future** and **no payment data** at this stage (meets clarity expectations).
- **Actions:** Single primary submit + secondary navigation link; spacing via `mt-3` / `mt-4` (Bootstrap), consistent with mixed content pages.
- **Verdict:** Readable and on-brand; not a broken or “plain HTML only” screen.

---

## Merchant dashboard (status partials)

- **Structure:** Dashboard composes **`@include($statusPartial)`** then logout form with `x-ui.button-secondary` and `mt-4`.
- **Partials:** Short **`brand-serif`** lead lines + body copy + links where required; **draft** and **accepted_pending_subscription** include clear CTAs (onboarding / activation).
- **Rejected / suspended:** Explanatory text + optional reason lines; **no** misleading CTA to onboarding for rejected (terminal state).
- **Verdict:** Partials are **lightweight** but consistent with the card layout; no obvious clutter or missing card wrapper.

---

## Admin — merchants list (`/admin/merchants`)

- **Structure:** Same auth layout + card; filter links as text links; **Bootstrap `table` + `table-responsive`** for data.
- **Usability:** Table headers and rows are scannable; empty state message in Romanian.
- **Styling note:** Filter toggles are **inline text links** (not pill buttons); still readable, slightly less “product UI” than merchant forms.
- **Stare column:** Displays **raw enum values** (e.g. `pending_review`) — readable for admins but not Romanian labels (see optional improvements).

---

## Admin — merchant detail (`/admin/merchants/{id}`)

- **Structure:** Read-only fields as stacked `<p><strong>…</strong>`; action blocks use **`field` / `label` / `textarea.input`** + `x-ui` buttons for approve / reject / suspend / reactivate.
- **Verdict:** Usable and consistent with form patterns where inputs exist; detail section is **functional** rather than a dense “settings panel” layout (acceptable for v1 admin).

---

## Design-system alignment (observable)

| Element | Alignment |
|--------|-----------|
| Layout shell | `layouts.auth` + `ai-form-section` / `ai-form` / `form-card` |
| Typography | `brand-serif` for emphasis headings |
| Buttons | `x-ui.button-primary` / `x-ui.button-secondary` (Bootstrap pill + dark/outline) |
| Form fields | `.field`, `.label`, `.input`, `form-grid`, `form-actions` |
| Tables (admin) | Bootstrap `table` + `table-responsive` |
| Links | In-card links inherit themed styling from existing auth CSS (per prior Task 003 patterns) |

---

## Romanian copy (merchant-facing)

- Onboarding, activation, dashboard partials, and merchant navigation strings reviewed in templates: **Romanian** for user-visible sentences and buttons.
- **Exception (admin):** Status **values** in list/detail are **technical enum strings** (not Romanian labels). This affects **admin** readability more than merchant-facing UX.

---

## Must-fix issues (visible UI)

- **None identified.** Pages load project CSS, use the shared auth layout and card/form patterns, and do not render as bare unstyled HTML.

---

## Optional improvements

1. **Admin list/detail — status column:** Replace raw `pending_review` / `accepted_pending_subscription` with **Romanian labels** (or a small legend) for clearer scanning.
2. **Admin index — filters:** Present „Doar în așteptare” / „Toți comercianții” as **secondary buttons** or **nav pills** to match the rest of the UI hierarchy.
3. **Laravel pagination** (`{{ $merchants->links() }}`): Default markup may differ visually from custom cards; optional to style with Bootstrap pagination classes or a shared partial later.
4. **Activation page:** Optional extra vertical rhythm (e.g. wrapping copy blocks in a single `form-grid` or spacing utility) if design docs require stricter rhythm—current state is acceptable.
5. **Screenshots archive:** For future audits, attach PNGs to `tasks/artifacts/screenshots/` for pixel-level comparison (not performed in this pass).

---

## Overall visual verdict

The new merchant lifecycle surfaces **inherit the Floraffeine auth layout and CSS**, use **card + form components** consistently on merchant flows, present **clear Romanian copy** on merchant-facing screens, and keep admin views **usable and Bootstrap-styled** without visible “broken” or unformatted pages. Remaining items are **polish** (admin status labels, filter affordances, pagination styling), not blockers.
