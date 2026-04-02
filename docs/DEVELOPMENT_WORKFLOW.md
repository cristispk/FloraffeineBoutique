# Floraffeine Boutique — Development Workflow

## Purpose

This document defines the standard workflow for implementing tasks in the Floraffeine Boutique project.

The goal is to ensure:

- consistency
- architectural integrity
- alignment with full product flow
- clean and predictable execution

---

## Core Principle

The Boutique must be developed as a complete standalone product from the beginning.

- The full product flow must always be considered
- Tasks are implemented incrementally
- Architecture must support all modules from the start

---

## Source of Truth

All implementation must align with:

### Business & Flow
- docs/source-of-truth/floraffeine-boutique.docx
- docs/source-of-truth/floraffeine-boutique-tasks-plan.docx

### Project Documentation
- docs/ARCHITECTURE.md
- docs/SCOPE.md
- docs/ROADMAP.md
- docs/DESIGN_CONTINUITY.md
- docs/UI_COMPONENTS_REFERENCE.md

---

## Mandatory Override Rule

This project is fully standalone.

The following must NEVER be assumed:

- shared cart
- shared checkout
- shared orders
- shared payments
- shared merchant/admin systems

All functionality must be implemented locally unless explicitly excluded.

---

## Full Product Thinking Rule

All agents must:

- think in terms of the complete Boutique product
- NOT think in phases
- NOT treat features as isolated modules

---

## Team Roles (Normalized)

### Planner
- selects next task from roadmap
- defines implementation plan
- identifies dependencies

---

### Architect
- validates architecture
- ensures extensibility
- enforces module boundaries

---

### Task Writer
- creates final task in `/tasks`
- ensures clarity and completeness

---

### Reviewer (PRE-IMPLEMENTATION)

Responsible for:

- validating task correctness BEFORE implementation
- validating architecture alignment
- ensuring completeness and clarity

Must NOT:
- act as tester
- validate runtime behavior

---

### Implementer

Responsible for:

- implementing task exactly as defined
- writing clean Laravel code
- respecting architecture and design

Must NOT:
- change scope
- redesign architecture

---

### Tester (POST-IMPLEMENTATION)

Responsible for:

- validating runtime behavior
- testing:
  - acceptance criteria
  - flows
  - edge cases
  - access rules

---

### Visual Auditor (UI TASKS ONLY)

Responsible for:

- validating visual fidelity
- ensuring alignment with design source-of-truth

Required ONLY for UI tasks.

---

### Doc Writer

Responsible for:

- writing final completion document in `tasks/done/`
- summarizing implementation and behavior

Does NOT:
- modify code
- modify architecture

---

### Release Manager

Responsible for:

- validating final completion
- writing release document in `tasks/releases/`
- removing original task from `/tasks`
- performing git closure

Git closure includes:
- pull latest changes
- commit
- push

Must NOT proceed if:
- review not passed
- testing not passed
- UI task not passed visual-auditor
- repo state is unsafe

---

## Standard Task Flow (Corrected)

Every task must follow this exact flow:

1. Planner defines task
2. Architect validates task
3. Task Writer creates task in `/tasks`
4. Reviewer validates task BEFORE implementation
5. Implementer writes code
6. Tester validates behavior
7. Visual Auditor validates UI (if applicable)
8. Doc Writer creates `tasks/done/...`
9. Release Manager finalizes closure

---

## Required Reading Before Implementation

Before coding, always read:

- task file in `/tasks`
- docs/ARCHITECTURE.md
- docs/SCOPE.md
- docs/ROADMAP.md
- docs/DESIGN_CONTINUITY.md
- docs/UI_COMPONENTS_REFERENCE.md

---

## Implementation Rules

- Do not start coding without full understanding
- Do not skip planning or validation
- Do not place business logic in controllers
- Do not mix public, merchant, and admin logic
- Always respect Laravel architecture

---

## Code Organization Expectations

Typical implementation may include:

- routes
- controller
- request validation
- service / action class
- models
- migrations
- blade views
- middleware

---

## Review Checklist

Before implementation:

- task clarity validated
- architecture validated
- flow completeness ensured

---

## Testing Checklist

Testing must include:

- happy path
- invalid input
- unauthorized access
- status-based restrictions
- redirects and flows
- edge cases

---

## Task Authoring Rule

No implementation may start until:

- planner defines the task
- architect validates it
- task-writer creates final version
- reviewer approves it

---

## Task Closure Rule

A task is complete only when:

- implementation is finished
- review is passed
- testing is passed
- visual audit is passed (for UI)
- completion document exists in `tasks/done/`
- release document exists in `tasks/releases/`
- task is removed from `/tasks`

---

## Final Rule

If something is unclear:

- follow source documents
- follow project documentation
- follow design reference
- do NOT improvise