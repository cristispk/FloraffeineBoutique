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

## Lifecycle States

### 1. Draft

Initial state after registration.

#### Description
- merchant account exists
- onboarding not started or incomplete

#### Access
- can access onboarding
- cannot access dashboard
- cannot create products

#### Routes Allowed
- /merchant/onboarding

---

### 2. Onboarding

Merchant is actively completing onboarding steps.

#### Description
- partial data saved
- multi-step flow

#### Access
- can continue onboarding
- cannot access dashboard
- cannot access products

#### Rules
- incomplete data is allowed
- progress must be persisted

---

### 3. Pending Review

Merchant completed onboarding and submitted for approval.

#### Description
- waiting for admin decision

#### Access
- cannot modify onboarding data (optional rule)
- cannot access products or dashboard

#### UI Behavior
- show status page
- display "În curs de aprobare"

---

### 4. Accepted Pending Subscription

Merchant approved by admin but has not activated Creator Plan.

#### Description
- approved but not operational

#### Access
- can access limited dashboard
- cannot create products
- must activate subscription

#### Required Action
- activate Creator Plan

#### Routes Allowed
- /merchant/activate

---

### 5. Active

Fully operational merchant.

#### Description
- subscription active
- full access granted

#### Access
- dashboard
- products
- orders
- payouts
- promotions
- events

---

### 6. Rejected

Merchant rejected by admin.

#### Description
- cannot operate

#### Access
- restricted
- may allow reapply (optional)

#### UI Behavior
- show rejection reason

---

### 7. Suspended

Merchant temporarily disabled.

#### Description
- due to admin action or payment issue

#### Access
- no operational features
- may access status page

---

## State Transitions

Allowed transitions:

- draft → onboarding
- onboarding → pending_review
- pending_review → accepted_pending_subscription
- accepted_pending_subscription → active
- any → suspended
- pending_review → rejected

---

## Login Redirect Rules

After login:

- draft / onboarding → redirect to onboarding
- pending_review → redirect to status page
- accepted_pending_subscription → redirect to activation page
- active → redirect to dashboard
- rejected → redirect to status page
- suspended → redirect to status page

---

## Middleware Enforcement

Examples:

- merchant.onboarding
- merchant.pending_review
- merchant.active
- merchant.subscription

Each route must be protected based on lifecycle state.

---

## UI Rules

- merchant must always see current status
- no hidden states
- clear CTA for next step

Examples:

- onboarding → "Continuă completarea"
- pending_review → "În curs de aprobare"
- accepted_pending_subscription → "Activează planul"

---

## Edge Cases

### Incomplete Onboarding Return

- merchant leaves and returns later
- must resume from last step

### Subscription Expired

- active → suspended
- restrict all features

### Admin Rejection After Edits

- product or merchant may return to restricted state

---

## Final Rule

No feature access without correct lifecycle state.

If lifecycle is bypassed:
→ system is considered broken.
