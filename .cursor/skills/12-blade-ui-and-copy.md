# Blade UI & Copy — Floraffeine Boutique

## Purpose

Defines UI structure, Blade usage, and copywriting rules.

This document enforces:

- consistent UI architecture
- strict separation of logic and presentation
- premium visual experience
- unified Romanian copy across the platform

---

## Core Principle

Blade is for presentation ONLY.

UI must be:

- clean
- predictable
- consistent
- brand-aligned
- user-guiding

---

## Architecture Responsibility

UI layer MUST respect:

- Controllers → no UI logic
- Services → no UI knowledge
- Blade → no business logic

Separation is mandatory.

Blade MUST consume prepared data only.

---

# 1. Blade Principles

## Allowed

- loops (foreach)
- simple conditionals (if)
- includes
- Blade components

---

## NOT allowed

- service calls
- DB queries
- business logic
- lifecycle decisions
- price calculations

---

## Rule

If Blade contains business logic:

→ architecture is broken

---

# 2. Layout Structure

System MUST use consistent layouts:

- layouts/app.blade.php
- layouts/merchant.blade.php
- layouts/admin.blade.php

---

## Rules

- reuse layouts everywhere
- no duplicated layout structure
- sections must be consistent (header, content, footer)

---

## Additional Rule

Layouts MUST define global structure (navigation, alerts, flash messages).

---

## Forbidden

- inline layout duplication
- mixing admin/merchant layouts
- inconsistent structure across pages

---

# 3. Components (MANDATORY)

Reusable UI MUST be component-based.

---

## Examples

- buttons
- inputs
- cards
- alerts
- modals
- badges

---

## Rules

- no duplicated HTML blocks
- extract reusable UI into components
- components must remain simple and predictable

---

## Component Responsibility

Components MUST:

- receive data via props
- not fetch data
- not contain business logic

---

## Additional Rule

Components MUST be deterministic (same input → same output).

---

# 4. Forms

All forms MUST:

- include CSRF token
- display validation errors clearly
- preserve old input
- follow consistent layout

---

## Validation UX

- errors must appear near fields
- errors must be readable
- no technical messages

---

## Additional Rule

Validation errors MUST use the same format across the system.

---

## Forbidden

- forms without validation feedback
- silent failures
- inconsistent form layouts

---

# 5. UI Consistency

UI MUST be consistent across:

- spacing
- colors
- typography
- button styles
- input styles

---

## Rule

No visual randomness.

Every element must follow a defined pattern.

---

## Additional Rule

Design system (tokens) SHOULD be centralized (CSS variables / Tailwind config).

---

# 6. Romanian Copy Rule (MANDATORY)

ALL user-facing text MUST be in Romanian.

---

## Examples

- Login → Autentificare
- Register → Creează cont
- Dashboard → Panou de control
- Save → Salvează
- Cancel → Anulează

---

## Rules

- NO English in UI
- NO mixed languages
- NO technical jargon for users
- tone must be clear and friendly

---

## Tone of Voice

Floraffeine tone must be:

- elegant
- calm
- friendly
- confident

NOT:

- robotic
- aggressive
- overly technical

---

## Additional Rule

Copy MUST be consistent across all screens (same action → same wording).

---

# 7. Error Messages

Error messages MUST be:

- clear
- human-readable
- actionable

---

## Examples

- "Produsul nu mai este disponibil"
- "Datele introduse nu sunt corecte"
- "Comerciantul nu este activ"

---

## Additional Rule

Errors MUST NOT blame the user.

---

## Forbidden

- raw validation errors
- technical messages
- system exceptions displayed to user

---

# 8. Success Messages

Success messages MUST:

- confirm action
- be short
- be positive

---

## Examples

- "Comanda a fost plasată cu succes"
- "Datele au fost salvate"
- "Produsul a fost creat"

---

## Additional Rule

Success messages MUST appear consistently (toast / alert pattern).

---

# 9. Empty States (MANDATORY)

System MUST handle empty states.

---

## Examples

- no products
- no orders
- no results
- no events

---

## Rule

Empty state MUST:

- explain situation
- guide next action

---

## Example

"Nu există produse încă. Adaugă primul produs."

---

## Additional Rule

Empty states SHOULD include CTA (call-to-action).

---

# 10. Navigation & Actions

Buttons and actions MUST be:

- clearly labeled
- consistent across system
- positioned predictably

---

## Rules

- primary action must be obvious
- destructive actions must be clear
- no ambiguous buttons

---

## Additional Rule

Destructive actions MUST require confirmation.

---

## Forbidden

- vague labels (e.g. "Click aici")
- inconsistent button placement
- hidden critical actions

---

# 11. Feedback & Interaction

System MUST always provide feedback.

---

## Examples

- loading states
- success messages
- validation errors
- disabled buttons when invalid

---

## Rule

User must ALWAYS understand:

- what is happening
- what just happened
- what they can do next

---

## Additional Rule

Async actions MUST show loading indicators.

---

# 12. Blade Security

## Rules

- escape all output by default
- use raw output ONLY when safe
- never render user input unescaped

---

## Forbidden

- {!! user_input !!}
- inline scripts with dynamic unsafe data

---

# 13. Performance Rules

UI MUST:

- avoid unnecessary re-renders
- avoid duplicated components
- avoid heavy Blade logic

---

## Additional Rule

Large datasets MUST be paginated.

---

## Rule

Rendering must remain fast and predictable.

---

# 14. Synchronization Rule

UI MUST reflect:

- product lifecycle
- merchant lifecycle
- subscription state
- promotion visibility
- event logic

---

## Example

If product is inactive:

→ UI MUST NOT show "Buy" button

---

## Rule

UI must NEVER contradict backend rules.

---

## Final Principle

UI must feel:

- premium
- intentional
- consistent
- trustworthy

Every screen must guide the user clearly.

If UI is inconsistent or misleading:

→ user trust is lost.