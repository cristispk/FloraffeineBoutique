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

## Main Domains

The system is divided into the following data domains:

1. Users & Roles
2. Merchants & Onboarding
3. Subscriptions (Creator Plan)
4. Products & Catalog
5. Cart & Checkout
6. Orders & Payments
7. Promotions
8. Events & Showcase
9. Payouts
10. System Settings

---

# 1. Users & Roles

## users

- id
- name
- email
- password
- role (user | merchant | admin)
- created_at
- updated_at

---

# 2. Merchants & Onboarding

## merchant_profiles

- id
- user_id
- business_name
- description
- status (draft, onboarding, pending_review, accepted_pending_subscription, active, rejected, suspended)
- created_at
- updated_at

## merchant_onboarding_data

- id
- merchant_id
- step
- data (JSON)
- completed (boolean)

---

# 3. Subscriptions

## merchant_subscriptions

- id
- merchant_id
- status (trial_active, active_paid, past_due, suspended)
- starts_at
- ends_at
- created_at
- updated_at

---

# 4. Products & Catalog

## categories

- id
- name
- slug

## products

- id
- merchant_id
- category_id
- title
- description
- price
- status (draft, pending_approval, active, rejected, inactive)
- created_at
- updated_at

## product_images

- id
- product_id
- path
- position

## product_approvals

- id
- product_id
- admin_id
- status (approved, rejected)
- reason
- created_at

---

# 5. Cart & Checkout

## carts

- id
- user_id (nullable for guest)
- created_at

## cart_items

- id
- cart_id
- product_id
- quantity

---

# 6. Orders & Payments

## orders

- id
- user_id
- total_amount
- status (pending, paid, cancelled)
- pickup_type (pickup)
- created_at

## order_items

- id
- order_id
- product_id
- price
- quantity

---

# 7. Promotions

## promotions

- id
- name
- type (featured, boost)
- starts_at
- ends_at

## product_promotions

- id
- product_id
- promotion_id

---

# 8. Events & Showcase

## events

- id
- name
- description
- starts_at
- ends_at
- status (active, inactive)

## event_registrations

- id
- event_id
- merchant_id
- status (pending, approved, rejected)

---

# 9. Payouts

## payouts

- id
- merchant_id
- amount
- status (pending, paid)
- created_at

---

# 10. System Settings

## system_settings

- id
- key
- value

---

## Relationships Summary

- user → merchant_profile (1:1)
- merchant → products (1:N)
- product → category (N:1)
- cart → cart_items (1:N)
- order → order_items (1:N)
- product → promotions (N:M)
- merchant → subscriptions (1:N)
- merchant → payouts (1:N)

---

## Final Rule

Database must reflect business logic clearly.

If a concept exists in business:
→ it MUST exist in database structure.
