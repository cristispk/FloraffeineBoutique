# Promotions & Subscriptions — Floraffeine Boutique

## Purpose

Defines monetization logic for merchants through:

- Creator Plan (subscription)
- product promotions

This document ensures:

- controlled merchant activation
- consistent access to platform features
- predictable visibility rules
- strict enforcement of subscription dependency

---

## Core Principle

A merchant can operate ONLY if:

- merchant.status = active
- subscription.status ∈ (trial_active, active_paid)

If subscription is not valid:

→ merchant MUST NOT be operational

---

## Architecture Responsibility

Monetization logic MUST be handled via services.

Recommended:

- SubscriptionService
- PromotionService
- MerchantAccessService

Controllers MUST remain thin.

Models MUST NOT contain business logic.

---

# 1. Creator Plan (Subscription)

## Definition

Creator Plan is REQUIRED for merchants to:

- access dashboard
- create products
- publish products
- sell products

Without valid subscription:

→ merchant cannot operate

---

## Subscription States

### trial_active

- temporary free access
- full functionality allowed
- limited duration

---

### active_paid

- subscription is paid
- full functionality allowed

---

### past_due

- payment failed
- restricted access
- grace period may apply

---

### suspended

- access fully blocked
- merchant becomes non-operational

---

## State Transitions

Allowed transitions:

- accepted_pending_subscription → trial_active / active_paid
- trial_active → active_paid
- active_paid → past_due
- past_due → suspended
- suspended → active_paid (after successful payment)

Invalid transitions are FORBIDDEN.

---

## Access Rules

| Subscription Status | Access Level |
|--------------------|-------------|
| trial_active       | full        |
| active_paid        | full        |
| past_due           | limited     |
| suspended          | none        |

---

## Past Due Behavior

Two possible strategies:

### Strict Mode

→ immediate restriction

---

### Grace Period (RECOMMENDED)

→ limited access for a defined number of days

During grace period:

- merchant may access dashboard
- new product creation may be blocked (optional)
- checkout availability may remain (configurable)

After grace expires:

→ subscription becomes suspended

---

## Subscription Enforcement

System MUST enforce subscription status at:

- login
- dashboard access
- product creation
- product publishing
- checkout eligibility

Subscription validation must NOT be done only at UI level.

---

# 2. Promotions

## Definition

Promotions provide increased product visibility.

They DO NOT affect:

- product price
- checkout logic
- product validity

They ONLY affect visibility.

---

## Promotion Types

### Featured

- product appears in homepage sections
- product appears in curated lists

---

### Boost

- product gains higher ranking in listings
- product appears earlier in category pages

---

## Database Structure

### promotions

- id
- name
- type (featured, boost)
- starts_at
- ends_at
- created_at
- updated_at

---

### product_promotions

- id
- product_id
- promotion_id
- created_at

---

## Promotion Eligibility

A product can be promoted ONLY if:

- product.status = active
- merchant.status = active
- subscription.status ∈ (trial_active, active_paid)

If any condition fails:

→ promotion MUST NOT be applied

---

## Promotion Runtime Validation

At runtime, system MUST validate:

- promotion is within valid date range
- product is still active
- merchant is still active
- subscription is still valid

If validation fails:

→ promotion is ignored dynamically

---

## Promotion Effects

### Featured

- homepage visibility
- curated exposure

---

### Boost

- higher ranking in lists
- improved discoverability

---

## Promotion Integrity Rule

Promotions MUST NEVER:

- override product status
- override merchant status
- bypass subscription validation
- allow inactive products to appear

Promotions are secondary to core eligibility rules.

---

# 3. Relationship with Events

Promotions and events are independent systems.

- promotion = paid visibility
- event = curated exposure

They may overlap but must NOT interfere with each other.

---

## Edge Cases

### Promotion Expired

→ product loses visibility boost immediately

---

### Merchant Suspended

→ all promotions are disabled instantly

---

### Subscription Expired

→ all promotions are disabled instantly

---

### Product Deactivated

→ product is removed from all promotions

---

### Invalid Promotion Data

→ promotion is ignored at runtime
→ system must not crash

---

## Concurrency & Consistency

System MUST ensure:

- promotions are not duplicated incorrectly
- product_promotions integrity is maintained
- invalid states cannot persist

Recommended:

- unique constraints (product_id + promotion_id)
- validation before attach/detach

---

## Security Rules

System MUST:

- validate all promotion assignments server-side
- prevent manual promotion injection
- enforce merchant ownership for product promotion actions
- prevent unauthorized promotion assignment

Forbidden:

- trusting client-submitted promotion_id blindly
- assigning promotions without validation
- bypassing subscription checks

---

## UX Rules

System must clearly communicate:

- subscription status
- promotion status
- access restrictions

Examples:

- "Abonamentul tău a expirat"
- "Promovarea nu mai este activă"
- "Acces restricționat din cauza plății eșuate"

No silent failures.

---

## Final Rule

Monetization logic must be:

- strictly enforced
- consistent across system
- independent from UI
- backend-authoritative

If subscription or promotion rules are bypassed:

→ system integrity is compromised.