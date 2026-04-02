# Git & Task Closure — Floraffeine Boutique

## Purpose

Defines how tasks, git workflow, and task closure are handled.

This document enforces:

- strict task-driven development
- controlled execution flow between agents
- clean and consistent git history
- predictable delivery process

---

## Core Principle

No work exists outside a task.

No task is complete without validation.

No code is accepted without full process completion.

---

# 1. Task-Driven Development (MANDATORY)

All work MUST be based on task files.

---

## Rules

- NO implementation without task file
- NO work outside defined scope
- tasks MUST follow roadmap order
- tasks MUST be clear, structured, and complete before implementation

---

## Forbidden

- coding without task
- modifying scope during implementation
- skipping planning phase

---

# 2. Task Lifecycle (STRICT FLOW)

Each task MUST follow this exact order:

1. task-writer → creates task
2. planner → structures implementation
3. implementer → writes code
4. reviewer → validates code quality
5. tester → validates behavior
6. doc-writer → updates documentation
7. release-manager → closes task

---

## Rule

No step may be skipped.

No role may self-approve without validation (except where explicitly defined).

---

# 3. Task Closure Rules (CRITICAL)

A task can be closed ONLY if ALL conditions are met:

- implementation is complete
- code review is approved
- tests pass (unit + feature + QA)
- documentation is updated
- no critical issues remain
- no broken flows exist

---

## Rule

If ANY condition fails:

→ task MUST NOT be closed

---

# 4. File Organization

### Active Tasks

- stored in: `/tasks`

---

### Completed Tasks

- stored in: `/tasks/done`

---

## Rules

- ONLY completed tasks are moved
- NEVER move incomplete tasks
- filenames MUST remain unchanged
- folder structure MUST remain clean

---

## Forbidden

- using `/tasks-done`
- renaming tasks randomly
- mixing active and completed tasks

---

# 5. Git Workflow (STRICT)

Git actions MUST follow controlled flow.

---

## Steps

1. implement changes
2. validate locally
3. stage ONLY relevant files
4. create clean commit
5. push ONLY when approved

---

## Rules

- NO random commits
- NO unrelated files in commit
- NO debug code
- NO temporary changes
- commits MUST reflect task scope

---

## Forbidden

- committing partial work
- committing broken code
- committing unrelated changes

---

# 6. Commit Messages

Commit messages MUST be clear and structured.

---

## Format

- Complete task XXX: short description

---

## Examples

- Complete task 002: merchant onboarding
- Complete task 005: product lifecycle
- Complete task 008: checkout flow

---

## Rules

- NO vague messages
- NO "fix stuff"
- MUST reflect actual changes

---

# 7. Push Rules (CRITICAL)

Push is NOT automatic.

---

## Rules

- push ONLY with explicit approval
- push ONLY after validation
- push ONLY after clean commit

---

## Forbidden

- automatic push
- pushing incomplete work
- pushing unreviewed code

---

# 8. Agent Responsibility

Each agent has STRICT responsibility.

---

## Implementer

- writes code
- follows architecture rules
- respects scope

---

## Reviewer

- validates code quality
- enforces architecture
- rejects invalid implementations

---

## Tester

- validates flows
- tests edge cases
- ensures no regressions

---

## Doc-Writer

- updates documentation
- ensures consistency with code

---

## Release Manager

- validates full completion
- moves task to `/tasks/done`
- confirms readiness for delivery

---

## Rule

Roles MUST NOT overlap irresponsibly.

---

# 9. Repository Hygiene

Repository MUST remain clean.

---

## Rules

- NO temporary files
- NO unused code
- NO commented-out blocks
- NO debug leftovers

---

## Rule

Every file in repo must have a purpose.

---

# 10. Validation Before Closure

Before closing a task, system MUST verify:

- no broken routes
- no UI inconsistencies
- no lifecycle violations
- no security issues
- no failing tests

---

## Rule

Closure without validation is FORBIDDEN.

---

# 11. Failure Handling

If issues are found:

- task remains open
- issues are fixed
- process restarts from relevant step

---

## Rule

Never force-close a broken task.

---

# 12. Synchronization Rule

Task closure MUST be consistent across:

- codebase
- documentation
- database behavior
- UI behavior
- test results

---

## Rule

If mismatch exists:

→ task is NOT complete

---

## Final Principle

Clean workflow = stable system.

Broken process = broken product.

If task lifecycle is not respected:

→ entire project integrity is compromised.