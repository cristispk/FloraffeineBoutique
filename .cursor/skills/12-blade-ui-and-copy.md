# Blade UI & Copy — Floraffeine Boutique

## Purpose

Define UI, Blade templates, and copywriting rules.

The UI must be:

- consistent
- clean
- predictable
- aligned with Floraffeine brand

---

## 1. Blade Principles

- Blade is for presentation only
- NO business logic
- NO database queries

---

## Allowed

- loops
- simple conditionals
- includes / components

---

## NOT allowed

- service calls
- DB queries
- complex logic

---

## 2. Layout Structure

Use consistent layout structure:

- layouts/app.blade.php
- layouts/merchant.blade.php
- layouts/admin.blade.php

---

## Rules

- reuse layouts
- do not duplicate structure
- keep sections consistent

---

## 3. Components

Use Blade components for reusable UI:

- buttons
- inputs
- cards
- modals

---

## Rules

- no duplicated HTML
- extract reusable UI
- keep components simple

---

## 4. Forms

All forms must:

- include CSRF
- display validation errors
- preserve old input

---

## 5. UI Consistency

- consistent spacing
- consistent colors
- consistent typography

---

## 6. Romanian Copy Rule

All user-facing text must be in Romanian.

---

## Examples

- Login → Autentificare
- Register → Creează cont
- Dashboard → Panou de control
- Save → Salvează
- Cancel → Anulează

---

## Rules

- no English in UI
- no mixed languages
- clear and friendly tone

---

## 7. Error Messages

- clear
- human-readable
- not technical

---

## 8. Success Messages

- short
- positive
- confirm action

---

## 9. Empty States

Always handle empty states:

- no products
- no orders
- no data

---

## 10. Forbidden Practices

- broken UI flows
- unclear buttons
- missing feedback
- inconsistent text

---

## Final Principle

UI must feel premium, clean, and intentional.

Every screen must guide the user clearly.
