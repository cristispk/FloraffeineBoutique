# Project Principles — Floraffeine Boutique

## Purpose

This document defines the fundamental principles that govern the entire Floraffeine Boutique project.

All agents, developers, and decisions must follow these principles.

These are NOT optional guidelines.
They are mandatory rules.

⚠️ These principles override implementation decisions when conflicts occur.

---

## Relationship with Agent System

This document defines WHAT must be respected.

Execution flow is defined in:

- /docs/agents/agent-config.md
- /docs/agents/AGENT_WORKFLOW.md
- /docs/DEVELOPMENT_WORKFLOW.md

If any execution contradicts these principles:

→ the execution is invalid, even if technically correct

---

## 1. Full Product Thinking

Floraffeine Boutique is a complete product from the beginning.

This means:

- the system must always be thought of as a whole
- implementation is incremental, but architecture must remain complete
- no feature should be treated as isolated

### Rules

- do NOT build temporary solutions
- do NOT create logic that will need later rewrite
- always consider future modules:
  - subscriptions
  - payouts
  - promotions
  - events
  - showcase logic

Compatibility with future modules must be preserved, even when current scope is smaller.

---

## 2. Roadmap-Driven Development

The project is implemented according to:

- docs/ROADMAP.md

### Rules

- do NOT skip tasks
- do NOT reorder tasks arbitrarily
- do NOT jump ahead
- do NOT implement future features early

If roadmap order and actual task history diverge:

- normalize documentation and workflow before continuing major work
- do NOT silently continue in contradiction

If unsure:

→ follow the next valid step in the roadmap and project workflow

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

If source documents suggest shared functionality:

→ reinterpret it as local functionality inside this project

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

Business flow correctness is more important than generic implementation habits.

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

Area boundaries must remain explicit and predictable.

---

## 6. Service-Oriented Architecture

Laravel structure must be respected.

### Rules

- controllers must be thin
- business logic must be in Services or Actions
- validation must be in Form Requests
- models must represent data, not hidden business flow

The system must prefer clear structure over convenience.

---

## 7. No Shortcut Rule

Shortcuts create technical debt.

### Rules

- do NOT implement quick fixes as permanent solutions
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
- follow docs/design-fidelity.md
- reuse patterns and components
- do NOT create disconnected UI

For UI tasks, visual continuity is mandatory, not optional.

---

## 9. Romanian UI Rule

All user-facing UI text must be in Romanian.

### Rules

- no English in UI
- consistent terminology
- clear and friendly wording

Internal code remains in English.

---

## 10. Security by Default

Security is mandatory, not optional.

### Rules

- validate all input server-side
- use CSRF protection
- enforce authentication and roles
- enforce ownership checks
- escape Blade output
- never trust client input

Correctness without security is not acceptable.

---

## 11. No Partial Implementation

A feature is either complete or not complete.

### Rules

- do NOT leave TODOs
- do NOT deliver partial flows as finished
- do NOT skip edge cases
- do NOT fake completeness

A task is not complete until:

- implementation is finished
- reviewer (PRE + POST) passed
- tester passed
- documentation reflects the real result
- closure is performed correctly

---

## 12. Consistency Over Speed

Speed is useless without consistency.

### Rules

- follow naming conventions
- keep structure predictable
- avoid randomness in code or UI
- align with existing patterns

Correct and consistent execution is always preferred over rushed output.

---

## 13. Task-Driven Execution

All work must be based on tasks.

### Rules

- do NOT implement without a task file
- do NOT extend scope without validation
- do NOT improvise outside task scope

Task execution must follow:

- agent workflow
- validation gates
- task lifecycle

---

## 14. Documentation Must Reflect Reality

Documentation must always be accurate.

### Rules

- do NOT leave outdated docs
- do NOT document assumptions as facts
- always update docs when decisions change
- reflect actual implementation and actual validation outcomes

Documentation must describe the real system, not the intended one.

---

## 15. No Improvisation Rule

When something is unclear:

- follow source documents
- follow architecture
- follow workflow
- follow validated task scope

### Rules

- do NOT guess critical behavior
- do NOT invent missing flow
- do NOT replace missing clarity with assumptions

If something is unclear:

→ stop and resolve ambiguity before continuing

---

## Final Principle

If a decision conflicts with:

- roadmap
- architecture
- business flow
- standalone rule
- validated task workflow
- agent system

→ the implementation is WRONG

No exceptions.