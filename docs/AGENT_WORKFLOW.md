# Agent Workflow

## Standard Flow
1. planner -> tasks/planning/
2. architect -> tasks/architecture/
3. task-writer -> tasks/
4. implementer -> code changes
5. reviewer -> tasks/reviews/
6. tester -> tasks/tests/
7. doc-writer -> tasks/done/
8. release-manager -> tasks/releases/

## Rule
Each agent must persist its output to file.
No handoff may rely only on chat output.