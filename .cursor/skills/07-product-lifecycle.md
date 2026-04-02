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

# 1. Lifecycle States

### Draft

- created but not submitted
- editable by merchant
- not visible
- not purchasable

---

### Pending Approval

- submitted by merchant
- waiting for admin review
- not visible
- not editable (recommended)

---

### Active

- approved by admin
- visible in catalog
- purchasable
- eligible for promotions and events

---

### Rejected

- denied by admin
- editable by merchant
- not visible

---

### Inactive

- manually disabled or archived
- not visible
- not purchasable

---

# 2. State Transitions

Allowed transitions:

- draft → pending_approval
- pending_approval → active
- pending_approval → rejected
- active → inactive
- inactive → draft (optional)
- rejected → draft

---

## Transition Rules

- ALL transitions MUST be validated
- transitions MUST be executed ONLY via Services
- direct status updates are FORBIDDEN
- transitions MUST NOT be done in controllers or models
- transitions MUST be atomic (no partial updates)

---

## Single Source of Truth

- statuses must be defined as constants or enums
- no hardcoded strings allowed
- database values must match application constants

---

# 3. Availability Rule (CRITICAL)

Product availability is NOT determined by product status alone.

A product is available ONLY if:

- product.status = active
- merchant.status = active

---

## Derived Availability

Availability must be computed, NOT stored.

Availability MUST always be determined at runtime using both product and merchant state.

---

# 4. Dependency Rules

### Merchant Dependency

If merchant becomes:

- suspended → all products become unavailable
- inactive → all products become unavailable

---

## Enforcement Rule

This dependency MUST be enforced in:

- queries
- services
- checkout validation

NOT only in UI

---

# 5. Visibility Rules

Product is visible ONLY when:

- product.status = active
- merchant.status = active

---

## Query Enforcement (CRITICAL)

All queries that return public products MUST filter:

- product.status = active
- merchant.status = active

Queries MUST NOT expose products of inactive or suspended merchants.

---

# 6. Edit Rules

If an ACTIVE product is edited:

→ product MUST return to pending_approval

(No exceptions)

---

## Additional Rule

Edits MUST NOT update active products silently.

Any change that affects product content MUST trigger re-approval.

---

# 7. Admin Control

Admin can:

- approve product
- reject product (with reason)
- deactivate product

---

## Rules

- rejection SHOULD include reason
- deactivation MUST remove product from visibility immediately

---

# 8. Checkout Eligibility

Product can be purchased ONLY if:

- product.status = active
- merchant.status = active

---

## Validation Rule (CRITICAL)

Validation MUST happen in backend at checkout time.

Frontend validation is optional and only for UX.

---

## Runtime Protection

At checkout:

- product state MUST be revalidated
- merchant state MUST be revalidated

If invalid:

→ checkout MUST be blocked

---

# 9. Promotions & Events Dependency

Product can enter:

- promotions
- events

ONLY if:

- product.status = active
- merchant.status = active

---

## Enforcement Rule

Validation MUST happen:

- when adding product to promotion/event
- when rendering promotion/event

---

# 10. Order Integrity Rule

Once a product is part of an order:

- product changes must NOT affect the order
- order_items must store:
  - price
  - product snapshot if needed

---

## Critical Rule

Order data MUST NOT depend on current product table values.

---

# 11. Service Enforcement (CRITICAL)

Services MUST:

- validate all lifecycle transitions
- enforce dependencies (merchant + product)
- enforce checkout eligibility
- prevent invalid state changes

---

## Forbidden

- updating status directly in controller
- updating status via model
- bypassing lifecycle rules

---

# 12. Synchronization Rule

Lifecycle must be consistent across:

- database
- services
- middleware
- UI

Any mismatch is a critical bug.

---

# 13. Edge Cases

### Merchant Suspended

→ all products become unavailable

---

### Product Edited After Approval

→ returns to pending_approval

---

### Product Deleted

→ must NOT affect existing orders

---

### Product Becomes Invalid After Added to Cart

→ must be revalidated at checkout

---

# Final Rule

If product lifecycle is not respected:

→ catalog and checkout become invalid.