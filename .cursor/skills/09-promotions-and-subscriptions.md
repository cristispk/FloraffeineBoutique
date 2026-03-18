# Promotions & Subscriptions — Floraffeine Boutique

## Purpose

Defines monetization logic for merchants through:
- Creator Plan (subscription)
- product promotions

Controls:
- merchant activation
- product visibility boosts
- access to premium features

---

## Core Principle

A merchant can operate ONLY if:
- merchant status = active
- subscription status = active_paid OR trial_active

---

# 1. Creator Plan (Subscription)

## Definition

Creator Plan is required for merchants to:
- access dashboard
- create products
- sell products

---

## Subscription States

### trial_active
- temporary free access
- full functionality allowed

---

### active_paid
- subscription paid
- full functionality allowed

---

### past_due
- payment failed
- restricted access (grace period optional)

---

### suspended
- access fully blocked
- merchant becomes non-operational

---

## State Transitions

- accepted_pending_subscription → trial_active / active_paid
- trial_active → active_paid
- active_paid → past_due
- past_due → suspended
- suspended → active_paid (after payment)

---

## Access Rules

| Subscription Status | Access |
|--------------------|--------|
| trial_active       | full   |
| active_paid        | full   |
| past_due           | limited|
| suspended          | none   |

---

## Past Due Behavior

Options:

### Strict
→ immediate restriction

### Grace Period (recommended)
→ allow limited access for X days

---

# 2. Promotions

## Definition

Promotions allow products to gain higher visibility.

---

## Promotion Types

### Featured
- product appears in highlighted sections

### Boost
- product gets priority in listings

---

## promotions table

- id
- name
- type (featured, boost)
- starts_at
- ends_at

---

## product_promotions table

- id
- product_id
- promotion_id

---

## Eligibility Rules

Product can be promoted ONLY if:

- product status = active
- merchant status = active
- subscription = active

---

## Promotion Effects

### Featured
- homepage sections
- curated lists

### Boost
- higher ranking in category pages

---

## Promotion Validation

At runtime:

- promotion must be active (date-based)
- product must still be active
- merchant must still be active

---

## Edge Cases

### Promotion Expired
→ remove visibility boost

---

### Merchant Suspended
→ remove all promotions instantly

---

### Product Deactivated
→ remove from promotion

---

# 3. Relationship with Events

Promotions and events may overlap but are independent.

- promotion = paid visibility
- event = curated campaign

---

## Final Rule

Monetization must NEVER break core logic.

If subscription or promotion rules are bypassed:
→ system integrity is compromised
