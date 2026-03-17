# Floraffeine Boutique — Development Workflow

## Purpose

This document defines the standard workflow for implementing any task in the Floraffeine Boutique project.

The goal is to ensure that development is consistent, modular, and predictable.

---

## Team Roles

### 1. Planner
Responsible for:
- understanding the task
- breaking the task into implementation steps
- identifying impacted files, routes, tables, and logic
- identifying edge cases

### 2. Architect
Responsible for:
- validating the technical direction
- checking database structure and relationships
- checking module boundaries
- preventing poor architectural decisions

### 3. Implementer
Responsible for:
- writing the code
- following the task requirements
- respecting project architecture
- keeping code clean and modular

### 4. Reviewer
Responsible for:
- checking code quality
- checking naming consistency
- checking logic consistency
- checking side effects and regressions

### 5. Tester
Responsible for:
- defining test scenarios
- validating happy paths
- validating edge cases
- checking acceptance criteria

### 6. Doc Writer
Responsible for:
- updating task notes when needed
- updating architecture or scope docs if impacted
- documenting important implementation decisions

---

## Standard Task Flow

For every task, the workflow must be:

1. Read the task file
2. Review architecture and scope documents
3. Break the task into implementation steps
4. Validate technical approach
5. Implement code
6. Review code
7. Define and execute test scenarios
8. Update documentation if needed

---

## Required Reading Before Coding

Before implementing any task, always read:

- `docs/ARCHITECTURE.md`
- `docs/SCOPE.md`
- `docs/DEVELOPMENT_WORKFLOW.md`
- the specific task file in `/tasks`

---

## Implementation Rules

- Do not code before understanding the task
- Do not skip planning
- Do not place business logic directly in controllers
- Do not introduce new frameworks without approval
- Do not mix public, merchant, and admin responsibilities
- Always respect Laravel + Blade architecture
- Prefer modular and readable code over clever shortcuts

---

## Code Organization Expectations

Typical implementation may include:
- routes
- controller
- request validation
- service class
- model
- migration
- blade views
- middleware
- test checklist

Not every task requires all of them, but every implementation must be evaluated properly.

---

## Review Checklist

Before considering a task complete, verify:

- architecture respected
- code readable
- naming consistent
- validation implemented
- authorization respected
- edge cases considered
- acceptance criteria covered

---

## Testing Checklist

Testing must cover:

- happy path
- invalid input
- unauthorized access
- role access restrictions
- expected redirects
- database changes where relevant

---

## Documentation Rule

If a task changes architecture, scope, or development standards, the related documentation must be updated.

After the planner creates the initial task draft, the architect must review and refine it into the final technical task definition before implementation begins.

No implementation starts until the architect validates or refines the task definition.

## Task Authoring Rule

After the planner drafts the task and the architect validates or refines it, the task-writer agent must create or update the final task file inside `/tasks` before implementation begins.

No implementation starts before the task file exists in finalized form.


## Boutique Task Interpretation Rule

For all Floraffeine Boutique tasks, the planner, architect, and task-writer must read and align with:

- docs/source-of-truth/floraffeine-boutique.docx
- docs/source-of-truth/floraffeine-boutique-tasks-plan.docx

However, all Boutique tasks must be interpreted using this mandatory override:

- this project is fully standalone
- no shared cart, checkout, order flow, or payment logic may be assumed
- all referenced Boutique functionality must be implemented inside this project unless explicitly excluded in the current task scope

No implementation may start based on assumptions from a previous project.

## Task Authoring Rule

After the planner drafts the task and the architect validates or refines it, the task-writer agent must create or update the final task file inside `/tasks` before implementation begins.

No implementation starts before the task file exists in finalized form.