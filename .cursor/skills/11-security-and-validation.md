# Security & Validation — Floraffeine Boutique

## Purpose

Defines all security, validation, and protection rules for the Boutique platform.

This document enforces:

- strict backend validation
- access control based on role AND lifecycle
- data integrity across all flows
- protection against misuse, tampering, and attacks

---

## Core Principle

Never trust the frontend.

ALL validation, authorization, and critical logic MUST be enforced on the backend.

Frontend is informational only.

Backend is authoritative.

---

## Architecture Responsibility

Security MUST be enforced across:

- Form Requests → input validation
- Services → business validation
- Middleware → access control
- Policies → authorization logic

Models MUST contain structure only (NO business logic).

Controllers MUST remain thin.

Services MUST be the final enforcement layer for critical operations.

---

# 1. Input Validation (MANDATORY)

## Rules

- ALL input MUST be validated via Form Requests
- NO validation in controllers
- NO direct trust in request data
- NO partial validation

---

## Validation Scope

Must include:

- required fields
- correct formats (email, phone)
- numeric validation (price, quantity)
- enum validation (status values)
- existence checks (exists in DB)
- relationship validation (product belongs to merchant)
- strict type validation (no implicit casting)

---

## Critical Rule

Unknown or unexpected fields MUST be rejected.

Never allow silent acceptance of extra data.

---

## Forbidden

- trusting frontend validation
- skipping validation for "internal" routes
- accepting unknown fields silently

---

# 2. Authentication Security

System MUST:

- use Laravel authentication system
- hash all passwords (bcrypt/argon)
- enforce CSRF protection
- protect all sensitive routes with `auth`

---

## Session Rules

- sessions must be secure (httpOnly, secure cookies)
- session regeneration after login
- logout must invalidate session
- no sensitive data stored in session

---

# 3. Authorization (CRITICAL)

Authorization is BOTH:

- role-based
- state-based (lifecycle)

---

## Roles

- user
- merchant
- admin

---

## Lifecycle Conditions

Access MUST also depend on:

- merchant.status
- product.status
- subscription.status

---

## Example Rules

Merchant CANNOT:

- access dashboard if not active
- create products without active subscription
- publish products if not approved

User CANNOT:

- access other user orders

Admin CAN:

- override flows (within controlled rules)

---

## Enforcement

Authorization MUST be enforced via:

- policies
- middleware
- service-level checks

Never only in UI.

---

# 4. Ownership Rules

Users MUST access ONLY their own data.

---

## Enforcement Rules

- always validate ownership via user_id
- never trust route parameters alone
- validate ownership in service layer

---

## Examples

- merchant can access only their products
- merchant can access only their orders
- user can access only their orders

---

# 5. Route Protection

All routes MUST be protected.

---

## Required Middleware

- auth
- role-based middleware
- lifecycle middleware

---

## Examples

- merchant.active
- merchant.subscription.active
- admin.only

---

## Rule

No sensitive route must be accessible without proper middleware.

---

# 6. Lifecycle Protection

System MUST enforce lifecycle rules strictly.

---

## Protected Actions

- dashboard access
- product creation
- product publishing
- checkout
- event participation
- promotions

---

## Rule

Lifecycle MUST be validated again in services (not only middleware).

If lifecycle rules are bypassed:

→ system integrity is broken

---

# 7. Data Integrity

System MUST ensure:

- totals are recalculated server-side
- frontend values are ignored
- foreign keys are enforced
- critical operations use transactions

---

## Example (Atomic Operation)

~~~php
DB::transaction(function () {
    // critical operations
});
~~~

---

## Additional Rule

All multi-step operations MUST be atomic.

---

## Forbidden

- trusting client totals
- partial writes without transaction
- orphan records

---

# 8. Anti-Tampering Protection

## URL Manipulation

System MUST:

- validate all IDs
- validate ownership
- validate existence

---

## Form Manipulation

System MUST:

- validate all fields
- reject unexpected input
- sanitize input where needed

---

## Forbidden

- trusting hidden inputs
- trusting client-submitted IDs blindly

---

# 9. Duplicate & Replay Protection

System MUST prevent:

- duplicate order creation
- repeated form submissions
- replay attacks

---

## Methods

- idempotency tokens (recommended for payments)
- unique constraints (DB-level)
- server-side duplicate checks

---

## Rule

Same action MUST NOT produce multiple unintended results.

---

# 10. Admin Security

Admin actions MUST be controlled.

---

## Rules

- sensitive operations must be validated
- dangerous actions must be explicit
- audit logging is strongly recommended

---

## Examples

- deleting data
- modifying lifecycle states
- overriding validations

---

# 11. Error Handling

System MUST:

- never expose sensitive data
- log errors internally
- show user-friendly messages

---

## Forbidden

- exposing stack traces
- exposing SQL queries
- exposing internal logic

---

# 12. Logging

System SHOULD log:

- failed access attempts
- suspicious behavior
- critical actions
- security-related events

---

## Logging MUST NOT:

- expose sensitive user data
- store plain passwords or tokens

---

# 13. Blade Security

## Rules

- escape all output by default
- use raw output ONLY when explicitly safe
- no database queries in Blade
- no business logic in Blade

---

## Forbidden

- rendering unescaped user input
- mixing logic and presentation

---

# 14. API Security (IMPORTANT)

If APIs are used:

System MUST:

- validate all requests
- authenticate properly (token/session)
- enforce rate limiting (recommended)
- validate payload structure strictly

---

## Additional Rule

API responses MUST be consistent and predictable.

---

## Forbidden

- accepting arbitrary JSON without validation
- exposing internal endpoints without auth

---

# 15. Edge Cases

### Merchant Suspended

→ block ALL actions

---

### Subscription Expired

→ block dashboard, product creation, promotions

---

### Product Invalid

→ block checkout

---

### Unauthorized Access

→ return 403

---

### Invalid Input

→ return validation errors

---

# 16. Synchronization Rule

Security rules MUST be consistent across:

- controllers
- services
- middleware
- database constraints
- UI behavior

Any mismatch is a critical vulnerability.

---

## Final Rule

Security is NOT optional.

If validation or access control fails:

→ entire system is compromised.