# Merchant Lifecycle — Floraffeine Boutique

## Purpose

This document defines the complete lifecycle of a merchant (creator) in the Boutique platform.

It controls:

- access to features
- routing permissions
- UI visibility
- business logic transitions

This lifecycle is mandatory and must be enforced at all levels:

- backend (services, policies)
- middleware
- UI
- database constraints

⚠️ Lifecycle is a CORE SYSTEM COMPONENT

---

## Relationship with System

Lifecycle must be consistent with:

- /docs/02-boutique-business-flow.md
- /docs/04-routing-and-access.md
- /docs/05-database-modeling.md

If any layer allows lifecycle bypass:

→ system is broken

---

## Core Principle

Authentication ≠ Permission

A merchant may be authenticated but must NOT access features unless their business status allows it.

---

# 1. Lifecycle States

### Draft

Initial state after registration.

---

### Onboarding

Merchant is completing onboarding steps.

---

### Pending Review

Merchant submitted for admin approval.

---

### Accepted Pending Subscription

Approved but subscription not active.

---

### Active

Fully operational merchant.

---

### Rejected

Merchant rejected by admin.

---

### Suspended

Merchant temporarily disabled.

---

# 2. State Transitions

Allowed transitions:

- draft → onboarding
- onboarding → pending_review
- pending_review → accepted_pending_subscription
- accepted_pending_subscription → active
- pending_review → rejected
- any → suspended

---

## Transition Rules

- ALL transitions MUST be validated
- transitions MUST be executed only via Services
- direct status updates in database are FORBIDDEN
- transitions must be atomic (no partial updates)

---

## Single Source of Truth

- lifecycle states must be defined as constants or enums
- no hardcoded strings across codebase
- values must match database ENUM values

---

# 3. Access Control per State

Each state defines strict access rules.

---

## Draft

- access onboarding only
- no dashboard
- no products

---

## Onboarding

- continue onboarding
- no dashboard access
- no products

---

## Pending Review

- no access to dashboard
- no product management
- view status only

---

## Accepted Pending Subscription

- limited dashboard
- cannot create products
- must activate subscription

---

## Active

- full access:
  - dashboard
  - products
  - orders
  - payouts
  - promotions
  - events

---

## Rejected

- restricted access
- show rejection reason

---

## Suspended

- no operational access
- status page only

---

## Critical Rule

Access must be enforced:

- via middleware
- via policies
- via query filtering
- via services

NOT just UI

---

# 4. Login Redirect Rules

After login:

- draft / onboarding → onboarding
- pending_review → status page
- accepted_pending_subscription → activation page
- active → dashboard
- rejected → status page
- suspended → status page

---

## Rules

- redirect must be enforced server-side
- frontend must NOT decide redirect logic

---

# 5. Middleware Enforcement

Middleware MUST:

- check lifecycle state
- block unauthorized access
- redirect correctly
- never modify state

---

## Important Rule

Middleware ≠ Business Logic

- middleware only checks
- services enforce transitions

---

## Critical Rule

Lifecycle must be validated on EVERY request

---

# 6. Service Enforcement (CRITICAL)

Services MUST:

- validate all lifecycle transitions
- enforce allowed transitions only
- prevent invalid state changes
- ensure data consistency

---

## Forbidden

- updating status directly in controller
- updating status via model
- bypassing lifecycle rules
- multiple services updating lifecycle inconsistently

---

# 7. UI Rules

- UI must always reflect current state
- no hidden states
- clear next action (CTA)
- UI must NOT allow invalid actions

---

## Examples

- onboarding → "Continuă completarea"
- pending_review → "În curs de aprobare"
- accepted_pending_subscription → "Activează planul"

---

# 8. Synchronization Rule

Lifecycle MUST be consistent across:

- database
- services
- middleware
- UI

Any mismatch is a critical bug.

---

# 9. Edge Cases

### Incomplete Onboarding Return

- must resume from last step
- must NOT restart onboarding

---

### Subscription Expired

- active → suspended
- restrict all features
- must be enforced automatically (cron/job)

---

### Admin Rejection

- merchant returns to restricted state
- must display rejection reason

---

### Manual DB Change Risk (CRITICAL)

If lifecycle is modified manually in database:

→ system behavior becomes undefined

All lifecycle updates must go through services

---

# 10. Query Enforcement Rule

All queries involving merchants MUST:

- filter only valid states when required
- exclude inactive/suspended merchants where needed

---

## Example

BAD:

select * from merchant_profiles

GOOD:

select * from merchant_profiles
where status = 'active'

---

# 11. Validation & Review Rule

Lifecycle must be validated through:

- reviewer (PRE → architecture)
- reviewer (POST → enforcement)
- tester (flow + access + edge cases)

If lifecycle can be bypassed:

→ task must be rejected

---

# Final Rule

No feature access without correct lifecycle state.

If lifecycle is bypassed:

→ the system is broken

⚠️ Lifecycle bugs = critical production issues