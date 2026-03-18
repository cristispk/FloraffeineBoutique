# Project Principles — Floraffeine Boutique

## Purpose

This document defines the fundamental principles that govern the entire Floraffeine Boutique project.

All agents, developers, and decisions must follow these principles.

These are NOT optional guidelines.
They are mandatory rules.

---

## 1. Full Product Thinking

Floraffeine Boutique is a complete product from the beginning.

This means:

- the system must always be thought as a whole
- implementation is incremental, but architecture is complete
- no feature should be treated as isolated

### Rules

- do NOT build temporary solutions
- do NOT create logic that will need to be rewritten later
- always consider future modules:
  - subscriptions
  - payouts
  - promotions
  - events
  - showcase logic

---

## 2. Roadmap-Driven Development

The project is implemented strictly according to:

- docs/ROADMAP.md

### Rules

- do NOT skip tasks
- do NOT reorder tasks
- do NOT jump ahead
- do NOT implement future features early

If unsure:

→ always follow the next step in the roadmap

---

## 3. Standalone Product Rule

Floraffeine Boutique is a fully independent application.

### Rules

- do NOT assume shared infrastructure
- do NOT assume shared modules
- everything must be implemented locally:
  - cart
  - checkout
  - orders
  - payments
  - merchant flows
  - admin flows

If the source documents suggest shared functionality:

→ reinterpret it as local functionality

---

## 4. Business Flow First

The Boutique is NOT a generic e-commerce platform.

It follows a specific business model defined in:

- docs/source-of-truth/floraffeine-boutique.docx

### Rules

- do NOT apply generic marketplace assumptions
- do NOT simplify business flow
- always respect:
  - merchant lifecycle
  - product lifecycle
  - approval flows
  - activation rules

---

## 5. Clear Separation of Areas

The system is divided into:

- public
- merchant
- admin

### Rules

- do NOT mix logic between areas
- do NOT expose merchant logic in public
- do NOT expose admin logic to merchants
- always use proper middleware and access control

---

## 6. Service-Oriented Architecture

Laravel structure must be respected.

### Rules

- controllers must be thin
- business logic must be in Services or Actions
- validation must be in Form Requests
- models must represent data, not business logic

---

## 7. No Shortcut Rule

Shortcuts create technical debt.

### Rules

- do NOT implement "quick fixes"
- do NOT bypass proper structure
- do NOT duplicate logic instead of structuring it properly

If something feels like a shortcut:

→ it is probably wrong

---

## 8. Design Continuity Rule

Boutique must visually and behaviorally align with the parent Floraffeine platform.

### Rules

- follow docs/DESIGN_CONTINUITY.md
- follow docs/UI_COMPONENTS_REFERENCE.md
- reuse patterns and components
- do NOT create disconnected UI

---

## 9. Romanian UI Rule

All user-facing UI text must be in Romanian.

### Rules

- no English in UI
- consistent terminology
- clear and friendly wording

Examples:

- Login → Autentificare
- Register → Creează cont
- Dashboard → Panou de control

---

## 10. Security by Default

Security is mandatory, not optional.

### Rules

- validate all input server-side
- use CSRF protection
- enforce authentication and roles
- enforce ownership checks
- escape all Blade output
- never trust client input

---

## 11. No Partial Implementation

A feature is either complete or not implemented.

### Rules

- do NOT leave TODOs
- do NOT deliver partial flows
- do NOT skip edge cases
- do NOT fake completeness

---

## 12. Consistency Over Speed

Speed is useless without consistency.

### Rules

- follow naming conventions
- keep structure predictable
- avoid randomness in code or UI
- align with existing patterns

---

## 13. Task-Driven Execution

All work must be based on tasks.

### Rules

- do NOT implement without a task file
- do NOT extend scope without validation
- do NOT improvise outside task scope

---

## 14. Documentation Must Reflect Reality

Documentation must always be accurate.

### Rules

- do NOT leave outdated docs
- do NOT document assumptions
- always update docs when decisions change

---

## Final Principle

If a decision conflicts with:

- roadmap
- architecture
- business flow
- standalone rule

→ the implementation is WRONG

No exceptions.