# Design Continuity — Floraffeine Boutique

## Purpose

This document defines how the Boutique application must visually and structurally align with the parent Floraffeine website.

The Boutique is a standalone Laravel application, but it must feel like a natural extension of the main Floraffeine website.

---

## Source of Truth (Design)

Primary design reference:

- docs/design-source-of-truth/website-parent/index.html

If multiple files are added in the future, the entire folder becomes the design reference.

---

## Core Principle

The Boutique MUST NOT copy the parent website code directly.

Instead, it must:

- replicate the visual identity
- reuse design patterns
- maintain UX consistency
- reimplement components cleanly in Laravel Blade

---

## Visual Identity Rules

The following must remain consistent with the parent website:

### Colors
- Primary color palette must match the parent website
- Accent colors must be reused consistently
- No new random colors allowed

### Typography
- Font family must match the parent website
- Font sizes and hierarchy must be respected
- Titles, subtitles, and body text must follow the same visual rhythm

### Spacing & Layout
- Use consistent spacing between sections
- Maintain the same padding/margin philosophy
- Avoid cramped or overly spaced layouts

---

## UI Components Consistency

The following components must follow the same style as the parent website:

### Buttons
- Same shape (rounded / square)
- Same color usage
- Same hover effects
- Same hierarchy (primary / secondary)

### Cards
- Product cards must match visual structure
- Shadows, borders, spacing must be consistent
- Image ratios must be preserved

### Forms
- Inputs must follow same styling
- Labels and placeholders must be consistent
- Error messages must be clean and visible

### Navigation
- Header must follow the same structure style
- Footer must match layout and tone
- Menu behavior must feel identical

---

## UX Consistency

The user should NOT feel that Boutique is a different system.

### Must feel:
- same brand
- same flow style
- same interaction patterns

### Must NOT feel:
- like a different theme
- like a different UI framework
- like a different company

---

## What MUST NOT be done

The following are strictly forbidden:

- Copy-paste full HTML pages from the parent site
- Reusing old JS logic blindly
- Embedding static templates without Laravel structure
- Mixing different design styles

---

## Implementation Rules

All UI must be built using:

- Laravel Blade templates
- Reusable Blade components
- Clean and modular structure

Example:

- components/button.blade.php
- components/card.blade.php
- layouts/app.blade.php

---

## Designer vs Developer Responsibility

### Developer (Implementer Agent)

Must:
- interpret design correctly
- recreate components cleanly
- ensure responsiveness
- maintain consistency

Must NOT:
- invent new UI styles
- ignore reference design

---

## Reviewer Responsibility

The reviewer must validate:

- visual consistency with parent website
- component reuse logic
- spacing and layout alignment
- absence of random styles

---

## Evolution Rule

If the parent website design evolves:

- Boutique must adapt gradually
- design changes must be intentional and controlled
- no breaking visual consistency

---

## Final Rule

If a design decision is unclear:

- always follow the parent website style
- never improvise a new design direction