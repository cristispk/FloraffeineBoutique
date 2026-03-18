# Git & Task Closure — Floraffeine Boutique

## Purpose

Define how tasks, git, and releases are handled.

Ensures clean workflow and repository consistency.

---

## 1. Task-Driven Development

All work must be based on tasks.

---

## Rules

- no implementation without task file
- no work outside defined scope
- tasks must follow roadmap order

---

## 2. Task Lifecycle

1. created (task-writer)
2. planned (planner)
3. implemented (implementer)
4. reviewed (reviewer)
5. tested (tester)
6. documented (doc-writer)
7. closed (release-manager)

---

## 3. Task Closure Rules

A task can be closed ONLY if:

- implementation complete
- review approved
- testing passed
- documentation updated
- no critical issues remain

---

## 4. File Organization

- active tasks → /tasks
- completed tasks → /tasks-done

---

## Rules

- do NOT move incomplete tasks
- preserve filenames
- keep structure clean

---

## 5. Git Workflow

### Steps

1. implement changes
2. stage relevant files
3. create clean commit
4. (optional) push

---

## Rules

- no random commits
- no unrelated files
- no debug code

---

## 6. Commit Messages

Must be clear and structured.

---

## Examples

- Complete task 002: merchant onboarding
- Complete task 005: product lifecycle

---

## Rules

- no vague messages
- no "fix stuff"
- reflect real scope

---

## 7. Push Rules

- NEVER push automatically
- push only with explicit approval

---

## 8. Repository Hygiene

- no temp files
- no unused code
- no commented-out blocks

---

## 9. Forbidden Practices

- closing incomplete tasks
- committing broken code
- skipping review/testing

---

## Final Principle

Clean workflow = stable project.

If process is broken → project will fail.
