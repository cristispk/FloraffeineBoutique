# Testing & Fixtures — Floraffeine Boutique

## Purpose

Defines testing strategy, QA coverage, and required fixtures.

Ensures:
- full flow validation
- lifecycle enforcement
- regression safety
- consistent QA process

---

## Core Principle

Do NOT test only happy paths.

System must be validated against:
- invalid data
- wrong states
- edge cases
- lifecycle violations

---

# 1. Test Types

### Unit Tests
- services
- calculations
- business rules

---

### Feature Tests
- full flows
- endpoints
- validation behavior

---

### Manual QA
- UI behavior
- flow consistency
- real user experience

---

# 2. Required Fixtures (CRITICAL)

System must include predefined data for testing.

---

## Merchant Fixtures

Create merchants in ALL states:

- draft
- onboarding
- pending_review
- accepted_pending_subscription
- active
- rejected
- suspended

---

## Product Fixtures

Create products in ALL states:

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

# 3. Happy Path Scenarios

Must test:

### Merchant Flow
- register → onboarding → approval → activation

### Product Flow
- create → submit → approve → visible

### Checkout Flow
- add to cart → checkout → order created

---

# 4. Negative Testing

Must test:

- missing fields
- invalid formats
- invalid IDs
- unauthorized access
- invalid lifecycle transitions

---

# 5. Edge Cases

### Merchant Suspended
- loses access instantly

---

### Product Deactivated
- removed from cart/checkout

---

### Subscription Expired
- merchant blocked

---

### Duplicate Requests
- double submit order

---

### Empty States
- no products
- no orders

---

# 6. Lifecycle Testing (MANDATORY)

Test enforcement of:

- merchant lifecycle
- product lifecycle
- subscription lifecycle

Ensure:
→ no bypass possible

---

# 7. Access Testing

Test:

- role-based access
- ownership checks
- admin-only actions

---

# 8. Regression Testing

After each task:

- re-test auth
- re-test merchant access
- re-test checkout
- re-test UI flows

---

# 9. UI Validation

Check:

- Romanian text consistency
- correct messages
- correct redirects
- no broken flows

---

# 10. Automation Strategy (Optional)

Future:

- CI tests
- automated feature tests
- seeders for fixtures

---

## Final Rule

If a feature is not tested:
→ it is NOT considered complete
