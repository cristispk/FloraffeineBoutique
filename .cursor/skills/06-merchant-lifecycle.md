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

---

## Single Source of Truth

- lifecycle states must be defined as constants or enums
- no hardcoded strings across codebase

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

# 4. Login Redirect Rules

After login:

- draft / onboarding → onboarding
- pending_review → status page
- accepted_pending_subscription → activation page
- active → dashboard
- rejected → status page
- suspended → status page

---

# 5. Middleware Enforcement

Middleware MUST:

- check lifecycle state
- block unauthorized access
- never modify state

---

## Important Rule

Middleware ≠ Business Logic

- middleware only checks
- services enforce transitions

---

# 6. Service Enforcement (CRITICAL)

Services MUST:

- validate all lifecycle transitions
- enforce allowed transitions only
- prevent invalid state changes

---

## Forbidden

- updating status directly in controller
- updating status via model
- bypassing lifecycle rules

---

# 7. UI Rules

- UI must always reflect current state
- no hidden states
- clear next action (CTA)

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

---

### Subscription Expired

- active → suspended
- restrict all features

---

### Admin Rejection

- merchant returns to restricted state

---

# Final Rule

No feature access without correct lifecycle state.

If lifecycle is bypassed:

→ the system is broken.