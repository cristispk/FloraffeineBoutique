# Design Fidelity — Floraffeine Boutique

## Purpose

Ensures that all UI implementations strictly follow the design source-of-truth.

This document enforces:

- visual consistency across the platform
- faithful implementation of design elements
- elimination of approximation and guesswork
- alignment between design, UI code, and final output

---

## Core Principle

Design must be replicated, NOT interpreted.

If a design reference exists:

→ it MUST be followed exactly

If no reference exists:

→ follow existing system patterns consistently

---

## Source of Truth

Design decisions MUST be based on:

- existing UI components
- defined layout patterns
- established visual style of the platform

---

## Forbidden

- inventing colors
- guessing spacing
- redefining component structure
- mixing multiple visual styles

---

## Rule

Consistency is more important than creativity.

---

# 1. Visual Consistency (MANDATORY)

UI MUST be consistent across:

- pages
- components
- layouts
- flows

---

## Elements to Validate

- color palette
- spacing system
- typography
- component sizes
- alignment
- icon usage

---

## Rule

Same element MUST look identical everywhere.

---

# 2. Component Fidelity

All UI MUST reuse existing components.

---

## Rules

- do NOT rebuild components differently
- do NOT duplicate variations unnecessarily
- use shared Blade components

---

## Examples

- buttons must have identical styles
- inputs must follow same structure
- cards must use same spacing and layout

---

## Forbidden

- creating "almost similar" components
- slight variations in padding/margin
- inconsistent border radius or shadows

---

# 3. Layout Fidelity

Layouts MUST follow predefined structure:

- header
- content
- footer
- spacing rhythm

---

## Rules

- do not shift layout structure randomly
- do not break alignment rules
- do not mix layout systems

---

## Forbidden

- inconsistent container widths
- random spacing between sections
- misaligned elements

---

# 4. Spacing & Proportions

Spacing MUST be consistent.

---

## Rules

- use consistent spacing scale
- reuse spacing patterns
- maintain visual rhythm

---

## Examples

- same padding inside cards
- same gap between sections
- same margin for titles

---

## Forbidden

- arbitrary spacing values
- visual imbalance
- inconsistent proportions

---

# 5. Typography Fidelity

Typography MUST be consistent.

---

## Rules

- consistent heading hierarchy
- consistent font sizes
- consistent font weights

---

## Examples

- H1, H2, H3 must follow same pattern everywhere
- body text must be consistent across pages

---

## Forbidden

- random font sizes
- inconsistent heading usage
- mixing styles

---

# 6. Form Consistency

Forms MUST follow unified design.

---

## Rules

- consistent input size
- consistent label placement
- consistent error display

---

## Examples

- same spacing between fields
- same button alignment
- same validation message style

---

## Forbidden

- different input styles across pages
- inconsistent error positioning
- mixed form layouts

---

# 7. Color Fidelity

Colors MUST come from system palette.

---

## Rules

- use predefined colors only
- maintain consistent usage (primary, secondary, error)

---

## Forbidden

- random hex values
- slightly different shades
- inconsistent color usage

---

## Rule

Color system MUST be predictable.

---

# 8. Feedback & States

UI states MUST be consistent.

---

## Includes

- hover states
- active states
- disabled states
- loading states
- error states

---

## Rule

Same interaction MUST behave identically everywhere.

---

# 9. Message Consistency

Messages MUST be consistent with UI and tone.

---

## Rules

- same structure for alerts
- same tone of voice
- same placement of messages

---

## Forbidden

- different styles for same message type
- inconsistent tone

---

# 10. Visual QA (MANDATORY)

All UI MUST be visually validated before completion.

---

## Checklist

- matches existing design patterns
- no visual inconsistencies
- no spacing issues
- no color deviations
- no broken layout

---

## Rule

Visual QA is REQUIRED before task closure.

---

# 11. Relationship with Other Systems

Design fidelity MUST align with:

- Blade UI & Copy rules
- Testing & QA validation
- Task closure requirements

---

## Rule

UI cannot pass if:

- it works functionally
- but fails visually

---

# 12. Fail Conditions

A UI implementation is NOT acceptable if:

- visually inconsistent
- uses guessed values
- does not match existing patterns
- feels unpolished
- breaks design rhythm

---

## Rule

"Almost correct" = NOT correct

---

## Final Principle

Design fidelity ensures:

- premium experience
- user trust
- visual clarity
- product identity

If design is inconsistent:

→ product quality is compromised.