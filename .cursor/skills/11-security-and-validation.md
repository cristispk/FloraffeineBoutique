# Security & Validation — Floraffeine Boutique

## Purpose

Defines all security, validation, and protection rules for the Boutique platform.

This document ensures:
- data integrity
- access control
- protection against misuse or attacks
- enforcement of business rules

---

## Core Principle

Never trust the frontend.

ALL validation and authorization must be enforced on the backend.

---

# 1. Input Validation

## Rules

- all input must be validated via Form Requests
- no validation in controllers
- no direct trust in request data

---

## Examples

- required fields
- correct formats (email, phone)
- numeric validation (price, quantity)
- enum validation (status values)

---

# 2. Authentication Security

- use Laravel built-in authentication
- passwords must be hashed
- use CSRF protection on all forms
- protect all sensitive routes with `auth`

---

# 3. Authorization (CRITICAL)

## Role-Based Access

- user
- merchant
- admin

---

## State-Based Access

Access must ALSO depend on:

- merchant status
- product status
- subscription status

---

## Example

Merchant CANNOT:

- access dashboard if not active
- create products without active subscription

---

# 4. Ownership Rules

Users must access ONLY their own data.

---

## Examples

- merchant can access only their products
- merchant can access only their orders
- user can access only their orders

---

## Protection

- always check `user_id`
- never trust route parameters alone

---

# 5. Route Protection

All routes must be protected with:

- auth middleware
- role middleware
- custom lifecycle middleware

---

## Examples

- merchant.active
- merchant.subscription
- admin.only

---

# 6. Lifecycle Protection

Prevent bypass of lifecycle rules.

---

## Examples

- accessing dashboard before activation
- creating product without approval
- purchasing inactive product

---

## Rule

If lifecycle is bypassed:
→ system is broken

---

# 7. Data Integrity

## Rules

- recalculate totals server-side
- never trust frontend prices
- enforce foreign key constraints
- use transactions where needed

---

# 8. Anti-Tampering Protection

## URL Manipulation

- validate all IDs
- ensure ownership

---

## Form Manipulation

- validate all fields
- reject unexpected input

---

# 9. Duplicate & Replay Protection

## Orders

- prevent duplicate submissions
- use tokens or idempotency

---

# 10. Admin Security

## Rules

- admin actions must be controlled
- sensitive operations must be validated

---

## Optional

- log admin actions
- audit trail

---

# 11. Error Handling

## Rules

- no sensitive data in error messages
- log errors internally
- show user-friendly messages

---

# 12. Logging

Use logs for:

- security events
- failed access attempts
- suspicious behavior

---

# 13. Blade Security

## Rules

- escape all output
- no raw HTML unless safe
- no DB calls in Blade

---

# 14. Edge Cases

### Merchant Suspended

→ block all actions

---

### Product Invalid

→ block checkout

---

### Unauthorized Access

→ return 403

---

## Final Rule

Security is NOT optional.

If validation or access control fails:
→ entire system is compromised
