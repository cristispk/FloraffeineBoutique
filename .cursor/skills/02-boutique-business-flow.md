# Boutique Business Flow — Floraffeine Boutique

## Purpose

This document defines the complete business flow of Floraffeine Boutique.

It describes:

- how users interact with the platform
- how merchants are onboarded and activated
- how products become available
- how orders are created and fulfilled

This is NOT a generic e-commerce flow.
This is a curated, controlled boutique experience.

⚠️ This document is a SOURCE OF TRUTH for business behavior.

---

## Relationship with System

This document defines WHAT the system must do.

Execution must follow:

- /docs/agents/agent-config.md
- /docs/agents/AGENT_WORKFLOW.md
- /docs/DEVELOPMENT_WORKFLOW.md

If implementation contradicts this flow:

→ implementation is invalid

---

## Core Concept

Floraffeine Boutique is:

- curated
- controlled
- experience-driven
- not an open marketplace

This means:

- merchants are approved, not freely active
- products are controlled and contextual
- ordering is intentional, not chaotic

---

## Flow Implementation Rule

This business flow must be implemented strictly through task-driven execution.

### Rules

- do NOT implement flow logic outside task scope
- do NOT improvise missing steps
- every part of the flow must be:
  - implemented
  - reviewed (PRE + POST)
  - tested
  - validated
  - aligned with documentation

---

# 1. Actors

## 1. Public User (Client)

- browses products
- explores categories and collections
- places orders
- may create account or checkout as guest (if allowed)

---

## 2. Merchant

- onboarded through structured flow
- cannot sell immediately
- must be approved
- must be activated
- manages own products and availability

---

## 3. Admin

- validates merchants
- controls approvals
- monitors activity
- manages global settings

---

# 2. Merchant Lifecycle (CRITICAL)

A merchant MUST follow this lifecycle:

1. draft  
2. onboarding  
3. pending_review  
4. accepted_pending_subscription  
5. active  
6. suspended / rejected (optional states)

---

## Lifecycle Rules

### draft
- merchant account created
- no access to platform features

---

### onboarding
- merchant fills required data:
  - business info
  - identity
  - location
  - operational details

- cannot access:
  - products
  - orders
  - dashboard

---

### pending_review
- onboarding completed
- waiting for admin validation

- merchant is locked

---

### accepted_pending_subscription
- approved by admin
- must complete activation step (subscription/payment)

---

### active
- full access:
  - dashboard
  - product management
  - orders
  - availability

---

### suspended / rejected
- access restricted or denied
- cannot operate

---

## HARD RULE

No merchant can:

- create products  
- access dashboard  
- receive orders  

UNLESS status = active

⚠️ This must be enforced:
- server-side (mandatory)
- query-level filtering
- middleware / policies

---

# 3. Merchant Onboarding Flow

## Steps

1. Merchant registers account  
2. Merchant enters onboarding flow  
3. Completes all required steps  
4. Submits onboarding  
5. Status → pending_review  
6. Admin reviews  
7. Admin accepts → accepted_pending_subscription  
8. Merchant activates (subscription/payment)  
9. Status → active  

---

## Rules

- onboarding must be sequential  
- steps cannot be skipped  
- incomplete onboarding cannot be submitted  
- direct URL access to later steps must be blocked  
- lifecycle transitions must be enforced server-side  
- frontend must NOT be trusted for flow control  

---

# 4. Product Lifecycle

Products are NOT immediately public.

## Steps

1. Merchant creates product (only if active)  
2. Product saved as draft  
3. Merchant completes required data:
   - name
   - description
   - pricing
   - images
   - availability  
4. Product becomes valid  
5. Product becomes visible in catalog  

---

## Rules

- only active merchants can create products  
- incomplete products are not visible  
- products must respect availability rules  
- products must belong to active merchants  
- visibility must be controlled at query level  
- no accidental exposure via API or direct URL  

---

# 5. Catalog Flow (Public)

Users interact with:

- homepage  
- collections  
- categories  
- product pages  

---

## Rules

- only valid products are visible  
- no draft products  
- no inactive merchant products  
- no invalid state leakage to public UI  
- filters and queries must enforce validity  

---

# 6. Ordering Flow

## Steps

1. User selects product  
2. Adds to cart  
3. Proceeds to checkout  
4. Provides:
   - contact details  
   - pickup/delivery info  
   - optional message (Boutique specific)  
5. Confirms order  
6. Order is created  

---

## Rules

- no checkout without valid products  
- no checkout with inactive merchant  
- no checkout with unavailable items  
- availability must be validated at checkout time  
- merchant status must be revalidated before order creation  
- cart must be revalidated at submission time  

---

# 7. Order Lifecycle

1. created  
2. confirmed  
3. in_progress  
4. ready  
5. completed  
6. cancelled (optional)  

---

## Rules

- orders belong to a specific merchant  
- merchant sees only their orders  
- admin can see all orders  
- invalid transitions must be blocked  
- lifecycle transitions must be enforced server-side  

---

# 8. Availability Logic (IMPORTANT)

Products are not always available.

Availability depends on:

- merchant schedule  
- product configuration  
- time slots (if applicable)  

---

## Rules

- user cannot order unavailable products  
- system must validate availability before checkout  
- availability must be revalidated at order creation  
- availability must NOT rely only on frontend logic  
- no invalid orders allowed  

---

# 9. Admin Flow

Admin responsibilities:

- review merchants  
- accept / reject onboarding  
- manage platform rules  
- monitor activity  

---

## Rules

- admin actions must be protected  
- admin decisions affect lifecycle transitions  
- admin actions must not bypass lifecycle integrity  
- all critical admin actions should be auditable (logs recommended)  

---

# 10. Forbidden Shortcuts

The following are STRICTLY forbidden:

- activating merchant without onboarding  
- skipping pending_review  
- creating products before activation  
- exposing products before ready  
- placing orders on inactive merchants  

---

# 11. Flow Integrity Rule

Every part of the system must respect:

- lifecycle states  
- dependencies between modules  
- sequence of actions  

If any part allows bypassing:

→ the system is broken  

---

# 12. Validation Rule

This business flow must be validated through:

- reviewer (PRE + POST)
- tester (behavior + lifecycle + access)
- visual-auditor (for UI-related flows)

A flow is NOT considered correct until:

- behavior is validated
- lifecycle restrictions are enforced
- UI does not allow misleading or broken interaction
- backend enforces all constraints independently

---

# Final Principle

The system must guide the user and merchant through the correct flow.

Not allow them to break it.

Structure > Freedom  
Control > Chaos  
Experience > Speed  

⚠️ If the system allows invalid flow paths:

→ implementation is WRONG