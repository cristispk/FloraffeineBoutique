# Product Lifecycle — Floraffeine Boutique

## Purpose

Defines the full lifecycle of a product inside the Boutique platform.

This lifecycle controls:
- product visibility in public area
- merchant permissions
- admin moderation
- eligibility for checkout, promotions, and events

---

## Core Principle

A product is NOT visible or sellable unless:
- merchant is ACTIVE
- product status is ACTIVE

---

## Lifecycle States

### 1. Draft

#### Description
- product created but not submitted

#### Access
- editable by merchant
- not visible publicly
- not eligible for checkout

---

### 2. Pending Approval

#### Description
- submitted by merchant
- waiting for admin review

#### Access
- not visible publicly
- cannot be edited (optional rule)

---

### 3. Active

#### Description
- approved by admin
- visible in catalog

#### Access
- visible in public area
- can be added to cart
- can be purchased
- eligible for promotions and events

---

### 4. Rejected

#### Description
- denied by admin

#### Access
- not visible
- editable by merchant

---

### 5. Inactive

#### Description
- manually disabled or archived

#### Access
- not visible publicly
- cannot be purchased

---

## State Transitions

Allowed transitions:

- draft → pending_approval
- pending_approval → active
- pending_approval → rejected
- active → inactive
- inactive → draft (optional)
- rejected → draft (after edit)

---

## Dependency Rules

### Merchant Status Dependency

Product can be ACTIVE only if:
- merchant status = active

If merchant becomes suspended:
- all products must become unavailable

---

## Visibility Rules

Product is visible ONLY when:
- status = active
- merchant = active

---

## Edit Rules

If an ACTIVE product is edited:

Option A (strict):
→ product goes back to pending_approval

Option B (relaxed):
→ minor edits allowed without reapproval

(Recommended: Option A for Boutique)

---

## Admin Control

Admin can:
- approve product
- reject product with reason
- deactivate product

---

## Checkout Eligibility

A product can be purchased ONLY if:
- product status = active
- merchant status = active

---

## Promotions & Events Dependency

Product can enter:
- promotions
- events

ONLY if:
- product = active
- merchant = active

---

## Edge Cases

### Merchant Suspended

→ all products become unavailable

### Product Edited After Approval

→ goes back to pending_approval

### Product Deleted

→ must not affect existing orders

---

## Final Rule

If product lifecycle is not respected:
→ catalog and checkout become invalid
