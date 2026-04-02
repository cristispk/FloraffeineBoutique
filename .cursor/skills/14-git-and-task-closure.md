# Git & Task Closure — Floraffeine Boutique

## Purpose

Defines how tasks, git workflow, and task closure are handled.

This document enforces:

- strict task-driven development
- controlled execution flow between agents
- clean and consistent git history
- predictable delivery process

---

## Dependency on Agent System

All task execution and closure MUST follow the agent flow defined in:

/docs/agents/agent-config.md

This document does NOT redefine agent responsibilities.

It enforces closure rules within that system.

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

⚠️ Official flow is defined in:
`/docs/agents/agent-config.md`

Each task MUST follow this execution order:

1. planner → defines task direction
2. architect → validates architecture
3. task-writer → creates final task
4. reviewer → validates task BEFORE implementation
5. implementer → writes code
6. reviewer → validates code AFTER implementation
7. tester → validates behavior
8. visual-auditor → validates UI (if applicable)
9. doc-writer → documents completion
10. release-manager → closes task

---

## Rule

- No step may be skipped
- No step may be reordered
- No implicit approval is allowed

---

# 3. Task Closure Rules (CRITICAL)

A task can be closed ONLY if ALL conditions are met:

- implementation is complete
- task review (pre-implementation) is approved
- code review (post-implementation) is approved
- tests pass (unit + feature + QA)
- visual audit passes (for UI tasks)
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

### Release Notes

- stored in: `/tasks/releases`

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
5. push ONLY when approved by release-manager

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

- push ONLY after release-manager validation
- push ONLY after all checks pass
- push ONLY after clean commit

---

## Forbidden

- automatic push
- pushing incomplete work
- pushing unreviewed code

---

# 8. Agent Responsibility

Agent responsibilities are defined in:

`/docs/agents/agent-config.md`

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
- process returns to the appropriate step

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