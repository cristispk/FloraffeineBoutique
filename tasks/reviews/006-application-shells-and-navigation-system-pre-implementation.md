# Review — 006 Application Shells and Navigation System (Pre-Implementation)

## Scope reviewed

- `tasks/006-application-shells-and-navigation-system.md`
- `tasks/planning/006-application-shells-and-navigation-system.md`
- `tasks/architecture/006-application-shells-and-navigation-system.md`
- `docs/UI_SHELL_BLUEPRINT.md`
- `docs/ARCHITECTURE.md`
- `docs/SCOPE.md`
- `docs/ROADMAP.md`
- `docs/DESIGN_CONTINUITY.md`
- `docs/UI_VISUAL_STANDARDS.md`
- `docs/UI_COMPONENTS_REFERENCE.md`
- current layout/page structure in `resources/views/*` and shell CSS baseline in `resources/css/app.css`

---

## Must-fix issues

1. **Navigation entries can unintentionally force route/page creation (scope-creep risk).**
   - Task section `8) Exact navigation requirements` lists mandatory public entries (`Despre Boutique`, `Evenimente / Showcase`, `Cum funcționează`, etc.), but it does not explicitly state what to do when those routes are not implemented yet.
   - Architecture explicitly allows route-safe placeholders/disabled nav items for unavailable routes; task should mirror this guardrail.
   - **Required fix:** Add a strict rule in the task: missing-route entries must render as placeholder/disabled state (or link to existing safe fallback) and must not create new routes/pages/business modules in this task.

2. **Task omits explicit layout contract details needed for zero-guess implementation.**
   - The architecture defines required shell section contracts and shell identifiers (`@yield('public_hero')`, `@yield('page_title')`, optional `breadcrumbs`/`page_actions`, plus `body[data-shell="..."]` contracts).
   - The task includes component contracts but not the shell layout section/body contracts explicitly.
   - **Required fix:** Add a dedicated subsection in the task with exact layout contracts per shell (public/app/admin/auth transitional) so implementer does not need to infer shell API.

---

## Optional improvements

1. **Clarify migration scope for merchant dashboard partials.**
   - Task currently uses glob form `resources/views/merchant/dashboard/partials/*.blade.php` “only if needed”.
   - Add a note that only shell-structure-touching partials are editable, and status/business messaging should remain unchanged.

2. **Strengthen acceptance evidence format.**
   - Add explicit evidence expectations for verification (for example: screenshots for public/app/admin at desktop/tablet/mobile and confirmation that `css/app.css` link exists in rendered HTML of migrated pages).
   - This improves tester/visual-auditor consistency and reduces interpretation variance.

3. **Add a one-line roadmap normalization note.**
   - Current roadmap label `006 — Merchant Dashboard` differs from this task naming sequence.
   - A brief note in the task preventing confusion during release/review handoff would reduce procedural ambiguity.

---

## Validation by requested dimensions

### 1) Task clarity

- **Executable without ambiguity:** mostly yes, with the must-fix layout-contract gap.
- **Implementation order:** clear and strict; wireframe-first gate is explicit.
- **Create/update file list:** concrete and sufficiently specific.
- **Migration targets:** clear and bounded.
- **Wireframe requirement:** explicit and enforceable.

### 2) Scope discipline

- **Shell/nav/responsive only:** clearly enforced.
- **No hidden business expansion:** generally strong; nav missing-route handling needs explicit guard (must-fix #1).
- **No controller/service/middleware refactor pressure:** clearly constrained.
- **No full-project redesign pressure:** clearly constrained to explicit migration targets.

### 3) Architecture alignment

- **Respects architecture document:** high alignment.
- **Shell separation:** well preserved.
- **Public/app/admin differentiation:** clearly mandated.
- **Transitional auth path:** preserved and explicitly required.

### 4) Implementation safety

- **Shell structure guesswork:** low overall, but layout contract details should be embedded in task (must-fix #2).
- **Responsive behavior guesswork:** low; breakpoints and behavior are explicit enough.
- **Migration order guesswork:** low.
- **Guardrails against hacks/scope creep:** good baseline; route-placeholder rule should be added.

### 5) Quality gates

- **Acceptance criteria:** strong and multi-dimensional.
- **Fail conditions:** strict and useful.
- **Wireframe-first gate:** present and strong.
- **Responsive/runtime/visual validation:** present; evidence format can be improved (optional).

---

## Review verdict

**Status: Changes required before implementation start.**

The task is strong and close to execution-ready, but the two must-fix items above should be applied to remove avoidable ambiguity and prevent accidental scope creep during navigation implementation.
