# Boutique Glossary — Floraffeine Boutique

## Purpose

This document defines all key business, technical, and UI terms used across the Boutique project.

It ensures consistency between:
- developers
- designers
- agents
- documentation
- implementation

No term should be interpreted differently across the system.

---

## Core Roles

### User

A general visitor or customer of the Boutique.

Can:
- browse products
- add to cart
- place orders

Cannot:
- access merchant dashboard
- manage products

---

### Merchant (Creator)

A registered user who sells products in Boutique.

Requires:
- onboarding completion
- admin approval
- active subscription

Access depends on **merchant lifecycle status**.

---

### Admin

Internal operator with full system access.

Responsible for:
- approving merchants
- approving products
- managing categories
- managing promotions
- managing events
- overseeing payouts

---

## Merchant Lifecycle Terms

### Draft

Initial state after registration.

- onboarding not completed
- no access to dashboard

---

### Onboarding

Merchant is completing required information.

- multi-step flow
- incomplete data allowed

---

### Pending Review

Merchant has completed onboarding.

- waiting for admin approval
- cannot access operational features

---

### Accepted Pending Subscription

Merchant approved by admin but has not activated Creator Plan.

- limited access
- must activate plan to continue

---

### Active

Fully operational merchant.

- full dashboard access
- can create products
- can manage orders

---

### Rejected

Merchant application rejected by admin.

- access restricted
- cannot operate

---

### Suspended

Merchant temporarily disabled.

- cannot access operational features
- may be reactivated later

---

## Product Terms

### Product

An item created by a merchant for sale.

Includes:
- title
- description
- price
- images
- category

---

### Product Draft

Product created but not submitted for approval.

- not visible publicly

---

### Pending Approval

Product submitted and awaiting admin review.

---

### Active Product

Approved and visible in public area.

---

### Rejected Product

Product denied by admin.

- not visible
- may require edits

---

### Inactive Product

Manually disabled or archived.

---

## Customer Flow Terms

### Cart

Temporary collection of products selected by user.

---

### Cart Item

Single product entry in cart with quantity.

---

### Checkout

Process of finalizing order.

Includes:
- customer details
- payment selection
- order confirmation

---

### Order

A completed purchase.

---

### Order Item

Individual product inside an order.

---

### Pickup

Customer collects order from physical location.

---

## Subscription Terms

### Creator Plan

Subscription required for merchants to operate.

---

### Trial Active

Temporary free access period.

---

### Active Paid

Subscription fully active and paid.

---

### Past Due

Payment failed or overdue.

- may restrict access

---

### Suspended Subscription

Subscription disabled due to non-payment or admin action.

---

## Promotion Terms

### Promotion

A paid or free visibility boost for products.

---

### Featured Product

Product highlighted in special sections.

---

### Promotion Slot

A position where promoted products appear.

---

## Event Terms

### Event

A curated campaign or themed collection.

---

### Showcase

A display area for selected products or creators.

---

### Event Registration

Process for merchants to join an event.

---

## Access Control Terms

### Role

User type:
- user
- merchant
- admin

---

### Business Status

Operational state independent of role.

---

### Ownership

Ensures a user can only access their own data.

---

## UI Terms

### Dashboard

Main control panel for merchants/admins.

---

### Status Page

Page showing merchant current lifecycle state.

---

### Empty State

UI shown when no data exists.

---

### CTA (Call To Action)

Buttons or actions guiding user behavior.

---

## System Terms

### Source of Truth

Primary reference documents for business logic.

---

### Design Continuity

Requirement to visually match parent website.

---

### Standalone Project

Boutique is fully independent system.

---

## Final Rule

All terms defined here are mandatory.

If a term is unclear or missing:
- it must be added here
- not reinterpreted differently elsewhere
