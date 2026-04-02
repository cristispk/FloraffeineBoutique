# Agent Configuration — Floraffeine Boutique

## Purpose

Defines how AI agents operate, interact, and execute tasks inside the Floraffeine Boutique project.

This document enforces:

- structured execution flow
- strict role separation
- predictable handoff
- controlled task closure
- zero chaotic execution

---

## Core Principle

Agents do NOT improvise.

Agents execute only their defined responsibility.

If something is unclear:

→ stop, report, and return control to the correct step

---

## Source of Truth Priority

If multiple documents exist, agents must follow this order of priority:

1. `project.mdc`
2. `docs/AGENT_WORKFLOW.md`
3. `docs/DEVELOPMENT_WORKFLOW.md`
4. task file in `/tasks`
5. relevant architecture / planning / review / test artifacts
6. supporting docs and skills

If a contradiction exists:

→ do NOT improvise  
→ follow the highest-priority valid document  
→ flag the contradiction clearly

---

## Agent List

The project uses the following agents:

- planner
- architect
- task-writer
- reviewer
- implementer
- tester
- visual-auditor
- doc-writer
- release-manager

---

## Official Execution Flow

All task execution MUST follow this exact order:

1. planner
2. architect
3. task-writer
4. reviewer
5. implementer
6. tester
7. visual-auditor (UI tasks only)
8. doc-writer
9. release-manager

---

## Flow Rules

- no step may be skipped
- no step may run out of order
- no agent may assume previous completion without file evidence
- visual-auditor is mandatory only for UI-related tasks
- release-manager is always the final step

---

## Agent Responsibilities

### 1. Planner
Responsible for:

- defining the task direction
- aligning with roadmap order
- identifying dependencies
- breaking work into implementation planning

Output:
- `tasks/planning/{task-name}.md`

Planner must NOT:
- write code
- decide implementation details that belong to architect
- skip roadmap order

---

### 2. Architect
Responsible for:

- validating planner direction
- making final architecture decisions
- defining exact file strategy and ownership
- removing ambiguity from planning

Output:
- `tasks/architecture/{task-name}.md`

Architect must NOT:
- write code
- leave critical implementation decisions undefined
- allow multiple open-ended approaches

---

### 3. Task Writer
Responsible for:

- transforming architecture into final executable task
- writing the implementation contract
- making task scope explicit and unambiguous

Output:
- `tasks/{task-name}.md`

Task-writer must NOT:
- change architecture
- simplify business logic
- leave implementer to guess

---

### 4. Reviewer
Responsible for TWO modes:

#### A. Pre-implementation task review
- validate task quality
- validate clarity
- reject ambiguity
- ensure task is implementation-ready

#### B. Post-implementation code review
- validate code quality
- validate architecture compliance
- validate logic, security, and regressions
- apply only small scoped fixes when appropriate

Output:
- `tasks/reviews/{task-name}.md`

Reviewer must NOT:
- act as tester
- invent new scope
- approve ambiguous tasks

---

### 5. Implementer
Responsible for:

- implementing the task exactly as written
- respecting architecture and scope
- producing complete, production-ready code

Output:
- code changes in repository
- screenshots + verification note for UI tasks when required

Implementer must NOT:
- redesign architecture
- reinterpret task scope
- add unrelated features

---

### 6. Tester
Responsible for:

- validating runtime behavior
- validating acceptance criteria
- validating edge cases and regressions
- validating lifecycle and access restrictions

Output:
- `tasks/tests/{task-name}.md`

Tester must NOT:
- review architecture
- act as reviewer
- run unsafe heavy automation if lightweight checks are sufficient

---

### 7. Visual Auditor
Responsible only for UI-related tasks.

Validates:

- rendered screenshots
- visual fidelity
- spacing, hierarchy, proportions
- alignment with design source-of-truth

Output:
- `tasks/visual-reviews/{task-name}.md`

Visual-auditor must NOT:
- review code
- validate backend logic
- approve UI without sufficient screenshot evidence

---

### 8. Doc Writer
Responsible for:

- documenting completed work
- updating documentation where justified
- moving completed task into `tasks/done/`

Outputs:
- `tasks/done/{task-name}.md`
- documentation updates when needed

Doc-writer must NOT:
- document incomplete work
- leave duplicate task file in both active and done locations

---

### 9. Release Manager
Responsible for:

- final closure validation
- creating release note
- removing original active task from `/tasks`
- performing git closure when safe

Output:
- `tasks/releases/{task-name}.md`

Release-manager must NOT:
- close incomplete tasks
- push unsafe repo state
- ignore unresolved must-fix issues

---

## Required File Outputs

Each agent MUST persist output to file.

### Required mapping

- planner → `tasks/planning/`
- architect → `tasks/architecture/`
- task-writer → `tasks/`
- reviewer → `tasks/reviews/`
- tester → `tasks/tests/`
- visual-auditor → `tasks/visual-reviews/`
- doc-writer → `tasks/done/`
- release-manager → `tasks/releases/`

No handoff may rely only on chat output.

---

## Validation Gates

A step may proceed only if the previous step is valid.

### Gate rules

- architect starts only after planner output exists
- task-writer starts only after architecture exists
- reviewer must validate task before implementer starts
- implementer starts only after reviewer approval of task
- tester starts only after implementation is complete
- visual-auditor starts only after tester for UI tasks
- doc-writer starts only after required validations pass
- release-manager starts only after closure documentation exists

---

## Failure Handling

If an agent detects blocking issues:

- STOP the flow
- do NOT continue to next agent
- clearly report the reason
- return the task to the correct previous step

Examples:

- planner issue → stop before architect
- architecture ambiguity → stop before task-writer
- task ambiguity → stop before implementer
- failed runtime behavior → stop before doc-writer
- failed visual audit → stop before release-manager

No silent continuation is allowed.

---

## Scope Control

All agents MUST respect task scope.

Agents must NOT:

- add extra features
- modify unrelated modules
- rewrite validated scope
- silently reduce required behavior

If a task is too broad or unclear:

→ reject or return it to the correct step  
→ do NOT compensate with improvisation

---

## UI Task Rule

A UI task requires:

- reviewer pass
- tester pass
- visual-auditor pass

A UI task is NOT complete without all three.

If visual-auditor fails:

→ task returns for rework  
→ doc-writer and release-manager must NOT close it

---

## Non-UI Task Rule

A non-UI task requires:

- reviewer pass
- tester pass

Visual-auditor is not required unless the task changes visible UI in a meaningful way.

---

## Task Closure Rule

A task is considered CLOSED only when:

- completion document exists in `tasks/done/`
- release document exists in `tasks/releases/`
- original active task file has been removed from `tasks/`
- all required validations have passed

A completed task must NOT remain duplicated in both:

- `tasks/`
- `tasks/done/`

---

## Git Closure Rule

Git closure belongs to release-manager only.

Git closure includes:

- safe pull
- staging relevant files only
- clean commit
- push when repository state is safe and closure conditions are complete

No other agent should perform final git closure.

---

## Repository Hygiene Rule

Agents must preserve repository cleanliness.

Forbidden:

- temp files
- stray artifacts
- duplicate task files
- deprecated closure paths like `tasks-done/` remaining in active workflow
- unrelated staged files in final closure

---

## Communication Rule

All agent output must be:

- structured
- explicit
- actionable
- deterministic

Forbidden patterns:

- vague statements
- maybe / probably style conclusions
- “should work” without validation
- silent assumptions

---

## Final Principle

Controlled agents create a predictable system.

Uncontrolled agents create a broken product.

Execution discipline is mandatory.