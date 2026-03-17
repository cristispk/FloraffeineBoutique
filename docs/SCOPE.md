# Floraffeine Boutique — Scope (MVP)

## Overview

This document defines the scope for version 1 (MVP) of the Floraffeine Boutique platform.

The goal is to build a fully functional standalone marketplace where:
- creators (merchants) can register and sell products
- users can browse, add to cart, and place orders
- admins can manage and control the platform

---

## Included in MVP

### 1. Public Area

- Homepage
- Boutique listing page (list of creators)
- Creator public page
- Product page
- Cart system
- Checkout flow
- Basic promotions display
- Events listing

---

### 2. Merchant Area

- Register / Login
- Onboarding flow (basic)
- Dashboard (basic stats)
- Product CRUD (create, edit, delete)
- Orders list (basic)
- Basic settings

---

### 3. Admin Area

- Admin login
- Creator approval (approve/reject)
- Categories management
- Products moderation (optional)
- Basic reporting (counts, simple stats)

---

### 4. Core Systems

- Authentication (users, merchants, admin roles)
- Cart system (session or DB-based)
- Checkout system
- Orders system
- Basic payment simulation (no real integration yet)

---

### 5. Database (minimum)

- users
- creators
- categories
- products
- carts
- cart_items
- orders
- order_items

---

## NOT Included in MVP (Phase 2+)

- Real payment integrations (Stripe, etc.)
- Advanced promotions engine
- Subscriptions (Creator Plans)
- Payout automation
- Advanced analytics
- Notifications system (emails, push)
- Events management (full version)
- Discount codes system

---

## Priority Order

1. Authentication (users + merchants)
2. Creator onboarding
3. Product system
4. Public browsing
5. Cart
6. Checkout
7. Orders
8. Admin approval system

---

## Success Criteria

MVP is considered complete when:

- A merchant can:
  - register
  - add products
  - see orders

- A user can:
  - browse creators
  - view products
  - add to cart
  - place an order

- An admin can:
  - approve a merchant