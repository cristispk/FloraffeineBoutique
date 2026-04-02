# Database Modeling — Floraffeine Boutique

## Purpose

This document defines the database architecture for the Boutique platform.

The database must support the FULL product from the beginning, even if implementation is incremental.

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

---

## Indexing (REQUIRED)

Indexes MUST exist for:

- all foreign keys
- status fields
- frequently queried columns

---

## Naming Convention

- tables → plural (products, orders)
- columns → snake_case
- foreign keys → entity_id (user_id, product_id)

---

## Status Fields

- use ENUM or strictly controlled values
- NEVER allow free-text status fields

---

## Soft Deletes

Use soft deletes for:

- products
- merchants
- orders (if needed)

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

## merchant_onboarding_data

- id
- merchant_id (FK → merchant_profiles.id)
- step
- data (JSON)
- completed (boolean)

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

# 11. Payouts

## payouts

- id
- merchant_id (FK)
- amount
- status (pending, paid)
- created_at

---

# 12. System Settings

## system_settings

- id
- key (unique)
- value

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

# Final Rule

Database must reflect business logic clearly.

If a concept exists in business:
→ it MUST exist in database structure.

If database is wrong:
→ the entire system will fail.