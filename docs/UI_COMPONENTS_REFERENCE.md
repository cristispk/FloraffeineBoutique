# UI Components Reference — Floraffeine Boutique

## Purpose

This document defines the UI components and patterns extracted from the parent Floraffeine website.

It serves as the implementation reference for all UI in the Boutique application.

---

## Source of Truth

All components must visually align with:

- docs/design-source-of-truth/website-parent/index.html

---

## General Rule

All components must be:

- reusable
- consistent
- implemented using Blade components
- styled based on parent website design

---

## Terminology Consistency

All UI labels must follow:
- docs/BOUTIQUE-GLOSSARY.md

No alternative wording allowed.

---

# 1. Layout Structure

## Main Layout

The application must follow a consistent layout:

- Header (navigation)
- Main content
- Footer

Blade structure:
layouts/app.blade.php


---

## Header

### Characteristics

- Clean navigation bar
- Logo on left
- Navigation links center/right
- CTA button (if exists)

### Behavior

- Sticky or fixed (if present in design)
- Responsive (mobile menu)

---

## Footer

### Characteristics

- Multiple columns (links/info)
- Social links
- Branding consistency

---

# 2. Typography

## Titles

- Large, elegant headings
- Consistent spacing

## Subtitles

- Slightly smaller than titles
- Used for sections

## Body Text

- Clean and readable
- Not overcrowded

---

# 3. Buttons

## Primary Button

### Usage

- Main actions:
  - „Creează cont”
  - „Activează plan”
  - „Adaugă produs”

### Characteristics

- Strong color (brand color)
- Rounded edges
- Hover effect

---

## Secondary Button

### Usage

- Less important actions

### Characteristics

- Outline or lighter color
- Subtle hover

---

## Rules

- No random button styles
- Must reuse same component

---

## Blade Example
components/button-primary.blade.php
components/button-secondary.blade.php


---

# 4. Cards

## Product Card

### Must include:

- Image
- Title
- Short description (optional)
- Price (if applicable)
- CTA (view / add)

### Characteristics

- Consistent spacing
- Image ratio preserved
- Subtle shadow or border

---

## Creator Card

### Must include:

- Profile image
- Name
- Short description
- CTA (view profile)

---

## Rules

- Same padding across all cards
- No inconsistent layouts

---

# 5. Forms

## Input Fields

### Characteristics

- Clean input borders
- Consistent height
- Proper spacing

---

## Labels

- Clear and readable
- Positioned consistently

---

## Errors

- Displayed under fields
- Visible and clear

---

## Required Fields

- Clearly marked

---

## Blade Example

components/input.blade.php
components/select.blade.php
components/form-error.blade.php


---

# 6. Sections

## Hero Section

### Characteristics

- Strong visual impact
- Title + subtitle
- CTA button
- Background image or color

---

## Content Sections

- Consistent spacing between sections
- Centered or grid-based layout

---

## Grid Layout

Used for:

- products
- creators
- promotions

---

# 7. Navigation & UX Patterns

## Navigation Flow

- Clear path between pages
- No confusion in structure

---

## Empty States

Must include:

- message
- optional CTA

Example:

„Nu ai produse încă. Adaugă primul tău produs.”

---

## Loading States

- Optional but recommended
- No blank screens

---

# 8. Status Badges

## Usage

- product status
- merchant status
- promotion status

---

## Types

- Active → green
- Pending → yellow
- Rejected → red
- Inactive → grey

---

# 9. Alerts & Messages

## Success

- Green message
- Clear confirmation

## Error

- Red message
- Clear explanation

---

# 10. Tables / Lists

## Usage

- orders
- products
- payouts

---

## Characteristics

- Clean rows
- Consistent spacing
- Readable data

---

# 11. Reusability Rule

All UI must be component-based.

Examples:

components/card-product.blade.php
components/card-creator.blade.php
components/badge-status.blade.php
components/alert.blade.php


---

# 12. Responsiveness

All components must:

- work on mobile
- adapt layout correctly
- avoid overflow issues

---

# 13. What NOT to do

- Do not create one-off components
- Do not duplicate similar UI
- Do not mix styles
- Do not ignore parent design

---

# 14. Final Rule

If a component is unclear:

- check parent website
- reuse existing pattern
- do NOT invent a new one