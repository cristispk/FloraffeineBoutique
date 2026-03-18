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

Even if a task implements only a part of the system, it must:

- fit into the full product architecture
- not block future modules (subscriptions, promotions, events)

---

## Team Roles

### 1. Planner

Responsible for:

- identifying the next task based on `docs/ROADMAP.md`
- understanding full business flow
- breaking the task into clear implementation steps
- identifying dependencies and risks

Must NOT:

- invent new flows
- skip task order
- think in phases

---

### 2. Architect

Responsible for:

- validating the task against full product architecture
- validating database structure and relationships
- enforcing module boundaries
- ensuring extensibility for future modules

Must reject:

- incorrect task order
- incomplete flow alignment
- architectural shortcuts

---

### 3. Task Writer

Responsible for:

- creating the final task file in `/tasks`
- structuring the task clearly and completely
- ensuring alignment with planner + architect output

Rules:

- must NOT create nested task folders
- must NOT move tasks to `/tasks-done`
- must NOT modify completed tasks

---

### 4. Implementer

Responsible for:

- writing clean, modular code
- following task requirements strictly
- respecting all project documentation and skills

Must:

- use Laravel best practices
- use Blade properly
- build reusable components

Must NOT:

- extend scope beyond the task
- introduce architectural changes
- ignore design continuity

---

### 5. Reviewer

Responsible for:

- validating code quality
- validating architecture compliance
- validating business flow correctness
- validating security and ownership rules
- validating design consistency

May:

- apply small and medium fixes

Must reject:

- broken flow logic
- security issues
- inconsistent UI

---

### 6. Tester

Responsible for:

- validating acceptance criteria
- testing real user flows
- validating edge cases
- detecting regressions

Must test:

- merchant lifecycle
- product lifecycle
- access restrictions
- checkout flow (when applicable)

---

### 7. Doc Writer

Responsible for:

- updating documentation when needed
- documenting important decisions
- keeping docs aligned with implementation

Must NOT:

- rewrite unrelated documentation
- introduce inconsistencies

---

### 8. Release Manager

Responsible for:

- validating task completion
- moving tasks from `/tasks` to `/tasks-done`

Rules:

- move ONLY fully completed tasks
- do NOT modify task content
- do NOT commit unless explicitly requested

---

## Standard Task Flow

Every task must follow this exact flow:

1. Planner selects next task from `docs/ROADMAP.md`
2. Planner defines implementation plan
3. Architect validates or refines the plan
4. Task Writer creates final task file in `/tasks`
5. Implementer writes code
6. Reviewer validates implementation
7. Tester validates functionality
8. Doc Writer updates documentation if needed
9. Release Manager moves task to `/tasks-done`

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
- Do not break modular structure
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
- test scenarios

Each task must evaluate what is required.

---

## Review Checklist

Before completion, verify:

- architecture respected
- business flow correct
- naming consistent
- validation present
- authorization enforced
- no security issues
- no regressions introduced
- UI consistent with design reference

---

## Testing Checklist

Testing must include:

- happy path
- invalid input
- unauthorized access
- status-based restrictions
- redirects and flows
- database changes
- edge cases

---

## Task Authoring Rule

No implementation may start until:

- planner defines the task
- architect validates or refines it
- task-writer creates the final task file in `/tasks`

---

## Task Closure Rule

A task is complete only when:

- implementation is finished
- review is passed
- testing is passed
- documentation is updated if needed

---

## Task Closure

Task closure is handled by the Release Manager.

Refer to:
- docs/skills/14-git-and-task-closure.md

---

Then:

- release-manager moves the task to `/tasks-done`

---

## Final Rule

If something is unclear:

- follow the source documents
- follow project documentation
- follow design reference
- do NOT improvise

