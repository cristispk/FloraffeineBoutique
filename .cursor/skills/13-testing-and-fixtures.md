# Testing & Fixtures — Floraffeine Boutique

## Purpose

Defines testing strategy, QA coverage, and required fixtures.

This document enforces:

- complete flow validation
- strict lifecycle enforcement
- regression safety
- consistent and repeatable QA process

---

## Core Principle

Do NOT test only happy paths.

System MUST be validated against:

- invalid data
- invalid states
- edge cases
- lifecycle violations
- concurrency issues

---

## Architecture Responsibility

Testing MUST cover:

- Services → business logic
- Controllers → request handling
- Middleware → access control
- Policies → authorization rules
- Database → integrity & constraints
- UI → user experience consistency

---

# 1. Test Types

## Unit Tests

Scope:

- services
- calculations
- lifecycle transitions
- business rules

---

## Rules

- isolate logic
- no external dependencies
- deterministic results

---

## Additional Rule

Unit tests MUST NOT hit database unless explicitly required.

---

## Feature Tests

Scope:

- endpoints
- validation behavior
- full flows (checkout, merchant, product)

---

## Rules

- test real HTTP flows
- validate responses
- validate database state

---

## Additional Rule

Feature tests MUST simulate real user scenarios.

---

## Manual QA

Scope:

- UI behavior
- UX consistency
- real user flows

---

## Rule

Manual QA is REQUIRED before task completion.

---

# 2. Required Fixtures (CRITICAL)

System MUST include predefined data sets.

Fixtures MUST cover ALL lifecycle states.

---

## Merchant Fixtures

- draft
- onboarding
- pending_review
- accepted_pending_subscription
- active
- rejected
- suspended

---

## Product Fixtures

- draft
- pending_approval
- active
- rejected
- inactive

---

## Subscription Fixtures

- trial_active
- active_paid
- past_due
- suspended

---

## Order Fixtures

- pending
- paid
- cancelled

---

## Promotion Fixtures

- active
- expired
- invalid

---

## Event Fixtures

- draft
- active
- inactive

---

## Rule

Fixtures MUST be:

- reproducible
- isolated
- resettable between tests

---

## Additional Rule

Fixtures MUST be created via seeders or factories.

---

# 3. Happy Path Testing

System MUST validate all core flows.

---

## Merchant Flow

- register → onboarding → approval → activation

---

## Product Flow

- create → submit → approve → visible

---

## Checkout Flow

- add to cart → checkout → order created

---

## Rule

Happy path MUST always work reliably.

---

# 4. Negative Testing (MANDATORY)

System MUST test invalid scenarios.

---

## Examples

- missing required fields
- invalid formats
- invalid IDs
- unauthorized access
- invalid lifecycle transitions

---

## Additional Rule

Each endpoint MUST have at least one negative test.

---

## Rule

Invalid input MUST NEVER pass silently.

---

# 5. Edge Case Testing

System MUST handle edge cases.

---

## Cases

### Merchant Suspended

→ loses access immediately

---

### Product Deactivated

→ removed from cart / blocked at checkout

---

### Subscription Expired

→ merchant blocked

---

### Duplicate Requests

→ must NOT create duplicate orders

---

### Empty States

- no products
- no orders
- no events

---

## Additional Rule

Edge cases MUST be reproducible via fixtures.

---

## Rule

Edge cases MUST NOT break system behavior.

---

# 6. Lifecycle Testing (MANDATORY)

System MUST enforce:

- merchant lifecycle
- product lifecycle
- subscription lifecycle

---

## Rule

No lifecycle bypass must be possible.

---

## Example

- inactive product MUST NOT be purchasable
- suspended merchant MUST NOT access dashboard

---

## Additional Rule

Lifecycle MUST be tested at:

- middleware level
- service level

---

# 7. Access & Authorization Testing

System MUST test:

- role-based access
- ownership rules
- admin-only actions

---

## Examples

- user cannot access other user's data
- merchant cannot access other merchant products
- admin can access restricted areas

---

## Rule

Unauthorized access MUST return 403.

---

## Additional Rule

Authorization MUST be tested via policies and services.

---

# 8. Data Integrity Testing

System MUST validate:

- totals recalculated server-side
- foreign key consistency
- atomic operations

---

## Example

Order creation MUST:

- create order
- create order items
- NOT allow partial state

---

## Additional Rule

Transactions MUST be tested for rollback behavior.

---

## Rule

No inconsistent data allowed.

---

# 9. Concurrency Testing

System MUST handle:

- duplicate submissions
- race conditions
- parallel requests

---

## Examples

- double checkout click
- simultaneous cart updates

---

## Additional Rule

Concurrency SHOULD be simulated using parallel test execution.

---

## Rule

System MUST remain consistent under concurrent usage.

---

# 10. Regression Testing

After EACH completed task:

System MUST re-test:

- authentication
- merchant access
- product flows
- checkout flow
- UI consistency

---

## Rule

No new feature must break existing functionality.

---

## Additional Rule

Regression tests MUST be automated where possible.

---

# 11. UI Validation

System MUST validate:

- Romanian text consistency
- correct messages
- correct redirects
- no broken flows
- consistent UI behavior

---

## Rule

UI must match backend behavior.

---

## Additional Rule

Critical flows MUST be manually verified in UI.

---

# 12. Automation Strategy (OPTIONAL BUT RECOMMENDED)

Future improvements:

- CI pipeline (GitHub Actions / GitLab CI)
- automated test execution
- database seeders for fixtures
- snapshot testing for UI

---

## Rule

Automation must not replace critical manual QA.

---

# 13. Test Data Isolation

Tests MUST:

- not depend on previous tests
- not share mutable state
- reset database between runs

---

## Rule

Tests must be deterministic.

---

# 14. Completion Rule

A feature is considered COMPLETE ONLY if:

- unit tests pass
- feature tests pass
- manual QA is validated
- edge cases are tested
- lifecycle rules are enforced

---

## Final Rule

If a feature is not tested:

→ it is NOT complete.

If testing is superficial:

→ system reliability is compromised.