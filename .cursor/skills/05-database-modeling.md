# Database Modeling — Floraffeine Boutique

## Purpose

This document defines the database architecture for the Boutique platform.

The database must support the FULL product from the beginning, even if implementation is incremental.

⚠️ Database structure is the foundation of the system.
Wrong schema = broken system.

---

## Relationship with System

Database must reflect:

- /docs/02-boutique-business-flow.md (business logic)
- /docs/03-laravel-architecture.md (implementation)
- lifecycle states and constraints

If database allows invalid states:

→ system integrity is broken

---

## Core Principles

- relational, normalized structure
- clear separation of domains
- no mixed responsibilities in tables
- support lifecycle states explicitly
- support auditability and future scaling

---

# 1. Mandatory Database Rules

## Foreign Keys (REQUIRED)

All relationships MUST use foreign keys.

- enforce referential integrity
- define cascade rules explicitly
- NEVER leave orphan records possible

---

## Indexing (REQUIRED)

Indexes MUST exist for:

- all foreign keys
- status fields
- frequently queried columns
- composite indexes for common filters (e.g. merchant_id + status)

---

## Naming Convention

- tables → plural (products, orders)
- columns → snake_case
- foreign keys → entity_id (user_id, product_id)

---

## Status Fields

- use ENUM or strictly controlled values
- NEVER allow free-text status fields
- values MUST match business flow definitions

---

## Soft Deletes

Use soft deletes for:

- products
- merchants
- orders (if needed)

---

## Rules

- soft-deleted data must NOT appear in queries
- queries must explicitly filter active records

---

## Auditability

Tables SHOULD support:

- created_at
- updated_at
- optional: created_by, updated_by

---

## JSON Fields

- use JSON only when necessary
- structure must be documented
- must NOT replace relational structure
- must NOT store critical relational logic

---

# 2. Main Domains

The system is divided into the following data domains:

1. Users & Roles
2. Merchants & Onboarding
3. Subscriptions
4. Products & Catalog
5. Cart & Checkout
6. Orders & Payments
7. Promotions
8. Events & Showcase
9. Payouts
10. System Settings

---

# 3. Users & Roles

## users

- id
- name
- email (unique)
- password
- role (user | merchant | admin)
- created_at
- updated_at

---

## Rules

- role must be controlled (no free values)
- authentication must rely on this table only

---

# 4. Merchants & Onboarding

## merchant_profiles

- id
- user_id (FK → users.id)
- business_name
- description
- status (draft, onboarding, pending_review, accepted_pending_subscription, active, rejected, suspended)
- created_at
- updated_at

---

## Rules

- user_id must be unique (1:1 relation)
- status must follow lifecycle defined in business flow
- inactive merchants must NOT appear in public queries

---

## merchant_onboarding_data

- id
- merchant_id (FK → merchant_profiles.id)
- step
- data (JSON)
- completed (boolean)

---

## Rules

- onboarding must be sequential
- steps must be validated server-side
- JSON structure must be documented

---

# 5. Subscriptions

## merchant_subscriptions

- id
- merchant_id (FK)
- status (trial_active, active_paid, past_due, suspended)
- starts_at
- ends_at
- created_at
- updated_at

---

## Rules

- only active_paid merchants can be fully active
- subscription must align with merchant lifecycle

---

# 6. Products & Catalog

## categories

- id
- name
- slug (unique)

---

## products

- id
- merchant_id (FK)
- category_id (FK)
- title
- description
- price
- status (draft, pending_approval, active, rejected, inactive)
- created_at
- updated_at

---

## Rules

- products must belong to active merchants
- only active products are visible
- product status must align with approval flow

---

## product_images

- id
- product_id (FK)
- path
- position

---

## product_approvals

- id
- product_id (FK)
- admin_id (FK → users.id)
- status (approved, rejected)
- reason
- created_at

---

## Rules

- product cannot be active without approval (if approval required)
- approval history must be preserved

---

# 7. Cart & Checkout

## carts

- id
- user_id (nullable FK)
- created_at

---

## cart_items

- id
- cart_id (FK)
- product_id (FK)
- quantity

---

## Rules

- cart must not contain invalid or inactive products
- cart must be revalidated before checkout

---

# 8. Orders & Payments

## orders

- id
- user_id (FK)
- total_amount
- status (pending, paid, cancelled)
- pickup_type (pickup)
- created_at

---

## order_items

- id
- order_id (FK)
- product_id (FK)
- price
- quantity

---

## Rules

- order must belong to valid merchant context
- product data should be snapshotted (price, name if needed)
- order must not depend on mutable product state

---

# 9. Promotions

## promotions

- id
- name
- type (featured, boost)
- starts_at
- ends_at

---

## product_promotions

- id
- product_id (FK)
- promotion_id (FK)

---

## Rules

- promotions must be time-bound
- expired promotions must not affect visibility

---

# 10. Events & Showcase

## events

- id
- name
- description
- starts_at
- ends_at
- status (active, inactive)

---

## event_registrations

- id
- event_id (FK)
- merchant_id (FK)
- status (pending, approved, rejected)

---

## Rules

- only approved merchants can participate
- events must respect lifecycle

---

# 11. Payouts

## payouts

- id
- merchant_id (FK)
- amount
- status (pending, paid)
- created_at

---

## Rules

- payouts must be traceable
- status transitions must be controlled

---

# 12. System Settings

## system_settings

- id
- key (unique)
- value

---

## Rules

- key must be unique
- values must be validated before use

---

# 13. Relationships Summary

- user → merchant_profile (1:1)
- merchant → products (1:N)
- product → category (N:1)
- cart → cart_items (1:N)
- order → order_items (1:N)
- product → promotions (N:M)
- merchant → subscriptions (1:N)
- merchant → payouts (1:N)

---

# 14. Data Integrity Rule (CRITICAL)

Database MUST prevent:

- orphan records
- invalid lifecycle states
- inconsistent relationships

If DB allows invalid state:

→ application logic will eventually fail

---

# 15. Query Safety Rule

All queries MUST:

- filter active merchants
- filter active products
- respect soft deletes
- respect lifecycle states

---

## Example

BAD:

select * from products

GOOD:

select * from products
where status = 'active'
and deleted_at is null

---

# Final Rule

Database must reflect business logic clearly.

If a concept exists in business:
→ it MUST exist in database structure.

If database is wrong:
→ the entire system will fail

⚠️ Database mistakes are the most expensive to fix later