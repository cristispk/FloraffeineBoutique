# Agent Workflow — Floraffeine Boutique

## Standard Flow

1. planner -> tasks/planning/
2. architect -> tasks/architecture/
3. task-writer -> tasks/
4. reviewer -> tasks/reviews/
5. implementer -> code changes
6. tester -> tasks/tests/
7. visual-auditor -> tasks/visual-reviews/ (UI tasks only)
8. doc-writer -> tasks/done/
9. release-manager -> tasks/releases/

---

## Role Clarification

### planner
- defines task scope and intent
- structures work into actionable planning documents

### architect
- defines technical architecture and constraints
- ensures alignment with system design and future scalability

### task-writer
- writes the final executable task
- must be complete, unambiguous, and implementation-ready

### reviewer (PRE-IMPLEMENTATION)
- reviews task and architecture BEFORE implementation
- validates:
  - completeness
  - correctness
  - architectural alignment
- must NOT act as tester or implementer

### implementer
- executes the task exactly as defined
- must respect architecture and project rules
- must NOT redesign or reinterpret scope

### tester (POST-IMPLEMENTATION)
- validates runtime behavior and flow
- covers:
  - acceptance criteria
  - happy paths
  - edge cases
  - role and lifecycle constraints
- must NOT review architecture

### visual-auditor (UI TASKS ONLY)
- validates visual fidelity and design consistency
- must follow design source-of-truth strictly
- required only for UI-related tasks

### doc-writer
- creates final completion documentation in `tasks/done/`
- summarizes:
  - implementation
  - behavior
  - changes
- must NOT modify implementation or architecture

### release-manager
- performs final task closure
- validates that:
  - reviewer passed
  - tester passed
  - visual-auditor passed (for UI tasks)
- writes release output in `tasks/releases/`
- removes the original task file from `tasks/`
- performs git closure when safe

---

## Workflow Persistence Rule

Each agent MUST persist its output to file.

Mapping:

- planner -> tasks/planning/
- architect -> tasks/architecture/
- task-writer -> tasks/
- reviewer -> tasks/reviews/
- tester -> tasks/tests/
- visual-auditor -> tasks/visual-reviews/
- doc-writer -> tasks/done/
- release-manager -> tasks/releases/

No handoff may rely only on chat output.

---

## Workflow Integrity Rules

- Agents MUST follow the defined order strictly
- reviewer MUST run before implementer
- tester MUST run after implementer
- visual-auditor is mandatory only for UI tasks
- doc-writer runs only after validation is complete
- release-manager performs final closure

Agents must NOT:
- skip steps
- reorder flow
- assume previous steps are complete without file evidence

---

## Task Closure Definition

A task is considered CLOSED only when:

- completion document exists in `tasks/done/`
- release document exists in `tasks/releases/`
- all required validations passed
- the original task file is removed from `tasks/`

A task must NOT exist in both:
- `tasks/`
- `tasks/done/`

---

## Git Closure Rule

Release-manager is responsible for git closure.

Steps:
- pull latest changes safely
- stage relevant changes
- commit with clear message
- push

Must NOT proceed if:
- validation steps are incomplete
- repository state is unsafe
- conflicts are present