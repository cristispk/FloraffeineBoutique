# Design Fidelity

## Purpose
Ensure that UI work follows the actual design source-of-truth and does not rely on approximation.

## Rules
- Use exact visual tokens when available
- Do not invent color palettes
- Do not approximate spacing/proportions if source values or patterns exist
- Prefer faithful adaptation over reinterpretation
- Reuse visual patterns consistently across pages

## Required Checks
- color palette fidelity
- logo scale and placement
- card proportions
- form spacing rhythm
- input/button consistency
- heading hierarchy
- message/alert consistency

## Fail Conditions
A UI result is not acceptable if:
- it is structurally clean but visually weak
- it uses guessed colors instead of source colors
- it feels inconsistent across pages
- it does not resemble the intended design direction