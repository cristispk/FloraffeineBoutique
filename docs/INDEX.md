# Documentation Index — Floraffeine Boutique

## Purpose

This document is the central index for all project documentation.

It explains:

- what documentation exists
- what each document controls
- which documents have priority
- where agents, skills, tasks, and workflow artifacts are located

This file is the primary navigation map for the project documentation system.

---

## Core Principle

Documentation must be:

- organized
- traceable
- non-contradictory
- easy to navigate

If documentation is hard to navigate:

→ execution quality drops  
→ agents improvise  
→ project consistency breaks

---

## Documentation Priority Order

If multiple documents overlap, use this priority order:

1. `.cursor/rules/project.mdc`
2. `docs/agents/agent-config.md`
3. `docs/AGENT_WORKFLOW.md`
4. `docs/DEVELOPMENT_WORKFLOW.md`
5. active task file in `/tasks`
6. task architecture / planning / review / test artifacts
7. project docs in `/docs`
8. skills in `.cursor/skills/`

If a contradiction exists:

- follow the higher-priority source
- flag the inconsistency
- do NOT improvise

---

# 1. Core Project Rules

## `.cursor/rules/project.mdc`
Defines:

- global project rules
- execution principles
- architecture mindset
- task ordering
- UI/design enforcement
- workflow persistence

This is the highest-priority operational rule source.

---

# 2. Agent System

## `docs/agents/agent-config.md`
Defines:

- agent list
- execution order
- handoff rules
- validation gates
- task closure flow
- git closure ownership

Use this as the central orchestration document for all agents.

---

## `docs/AGENT_WORKFLOW.md`
Defines:

- concise workflow order
- file output mapping
- workflow integrity rules
- task closure definition

Use this as the concise workflow reference.

---

## `docs/DEVELOPMENT_WORKFLOW.md`
Defines:

- end-to-end development process
- role responsibilities
- implementation sequence
- validation and closure expectations

Use this as the operational workflow document.

---

# 3. Product & Architecture Documentation

## `docs/ARCHITECTURE.md`
Defines:

- system structure
- module separation
- high-level architecture
- boundaries between public / merchant / admin

---

## `docs/SCOPE.md`
Defines:

- included functionality
- excluded functionality
- scope boundaries
- MVP vs future expansion if documented

---

## `docs/ROADMAP.md`
Defines:

- official task order
- implementation sequence
- dependency direction between modules

Tasks must follow this unless documentation is explicitly normalized.

---

## `docs/source-of-truth/floraffeine-boutique.docx`
Defines:

- core product intent
- business direction
- functional expectations

---

## `docs/source-of-truth/floraffeine-boutique-tasks-plan.docx`
Defines:

- intended task flow
- feature sequencing support
- execution framing for the product

---

# 4. UI & Design Documentation

## `docs/DESIGN_CONTINUITY.md`
Defines:

- visual continuity rules
- alignment with Floraffeine brand
- high-level design behavior

---

## `docs/UI_COMPONENTS_REFERENCE.md`
Defines:

- reusable UI patterns
- expected component structure
- UI consistency rules

---

## `docs/ui/design-fidelity.md`
Defines:

- strict visual fidelity rules
- anti-approximation rules
- visual consistency expectations
- visual fail conditions

Use this together with Blade UI and visual-auditor workflow.

---

# 5. Skills Index

Skills are located in:

- `.cursor/skills/`

These define reusable execution and design rules for the project.

---

## `01-project-principles.md`
Defines:

- fundamental project principles
- non-negotiable execution mindset

---

## `02-boutique-business-flow.md`
Defines:

- full business flow of Boutique
- merchant / product / order flow relationships

---

## `03-laravel-architecture.md`
Defines:

- Laravel layer responsibilities
- controller / request / service / model structure

---

## `04-routing-and-access.md`
Defines:

- route separation
- middleware rules
- access and ownership structure

---

## `05-database-modeling.md`
Defines:

- domain structure
- table responsibilities
- relational rules
- integrity expectations

---

## `06-merchant-lifecycle.md`
Defines:

- merchant states
- access restrictions
- lifecycle transitions
- redirect logic

---

## `07-product-lifecycle.md`
Defines:

- product states
- visibility conditions
- eligibility rules
- moderation lifecycle

---

## `08-checkout-and-pickup.md`
Defines:

- checkout flow
- pickup-only fulfillment
- order creation integrity
- cart/order rules

---

## `09-promotions-and-subscriptions.md`
Defines:

- Creator Plan rules
- subscription enforcement
- promotion eligibility
- monetization constraints

---

## `10-events-and-showcase.md`
Defines:

- event campaigns
- showcase logic
- curated visibility constraints

---

## `11-security-and-validation.md`
Defines:

- validation rules
- auth/authz rules
- anti-tampering rules
- data integrity protection

---

## `12-blade-ui-and-copy.md`
Defines:

- Blade presentation rules
- UI structure
- Romanian copy rules
- UX messaging rules

---

## `13-testing-and-fixtures.md`
Defines:

- testing strategy
- fixture expectations
- QA depth
- regression coverage

---

## `14-git-and-task-closure.md`
Defines:

- task-driven development
- closure rules
- git hygiene
- completion constraints

---

# 6. Tasks & Workflow Artifacts

## Active Tasks
Location:
- `tasks/`

Contains:
- final executable tasks not yet fully closed

---

## Planning Artifacts
Location:
- `tasks/planning/`

Contains:
- planner output

---

## Architecture Artifacts
Location:
- `tasks/architecture/`

Contains:
- architect output

---

## Review Artifacts
Location:
- `tasks/reviews/`

Contains:
- reviewer output

---

## Test Artifacts
Location:
- `tasks/tests/`

Contains:
- tester output

---

## Visual Review Artifacts
Location:
- `tasks/visual-reviews/`

Contains:
- visual-auditor output for UI tasks

---

## Completed Tasks
Location:
- `tasks/done/`

Contains:
- completed task documentation

---

## Release Artifacts
Location:
- `tasks/releases/`

Contains:
- release-manager closure notes
- git / closure summary

---

## Screenshot Artifacts
Location:
- `tasks/artifacts/screenshots/{task-name}/`

Contains:
- UI screenshots
- implementer verification note
- visual audit support evidence

---

# 7. Task Status Model

A task may exist in one of these states:

## Planned
Exists in:
- `tasks/planning/`

---

## Architected
Exists in:
- `tasks/architecture/`

---

## Ready for Implementation
Exists in:
- `tasks/`

and has been reviewed for implementation readiness

---

## Under Validation
Has artifacts in:
- `tasks/reviews/`
- `tasks/tests/`
- `tasks/visual-reviews/` (if UI)

---

## Completed
Exists in:
- `tasks/done/`

---

## Released / Closed
Has closure note in:
- `tasks/releases/`

and no longer exists as active task in:
- `tasks/`

---

# 8. Directory Rules

## Valid Task Closure Paths

Use:

- `tasks/`
- `tasks/done/`
- `tasks/releases/`

Do NOT use:
- `tasks-done/`

If `tasks-done/` exists from older workflow versions:

- treat it as deprecated
- migrate valid content if needed
- remove it from active workflow

---

# 9. Reading Order for Agents

When an agent starts work, recommended reading order is:

1. `.cursor/rules/project.mdc`
2. `docs/agents/agent-config.md`
3. `docs/AGENT_WORKFLOW.md`
4. `docs/DEVELOPMENT_WORKFLOW.md`
5. relevant task file
6. relevant task artifacts
7. relevant supporting docs
8. relevant skills

This keeps execution deterministic.

---

# 10. Maintenance Rule

This index must be updated whenever:

- new core docs are added
- agent flow changes
- new skill files are introduced
- task artifact structure changes
- closure rules change

---

## Final Principle

A clear documentation map creates a clear execution system.

If documentation is not organized:

→ agents improvise  
→ teams lose consistency  
→ the product becomes unstable