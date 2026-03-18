# Floraffeine Boutique — Scope

## Overview

This document defines the full product scope of the Floraffeine Boutique platform.

Floraffeine Boutique is a standalone marketplace platform where:

- creators (merchants) can register, onboard, and sell products
- users can browse, select, and purchase products
- administrators manage approvals, visibility, and platform rules

The product must be treated as a complete system from the beginning.

Implementation may happen incrementally, but the scope must always reflect the full product.

---

## Core Principle

The Boutique is NOT developed in isolated phases.

Instead:

- the full product is defined from the start
- all modules are part of the same system
- implementation order does NOT redefine product scope

---

## Full Product Coverage

The Boutique platform includes the following functional domains.

---

### 1. Public Experience

Accessible to all users.

Includes:

- homepage / entry point
- boutique listing (creators)
- creator public profile
- product listing and product detail pages
- cart
- checkout
- order confirmation
- promotional pages
- events pages
- informational pages (FAQ, terms, contact)

---

### 2. Merchant Experience

Accessible to creators (merchants).

Includes:

- registration and authentication
- onboarding flow
- merchant status visibility
- creator plan activation
- dashboard
- product management
- order visibility
- payout visibility
- subscription management
- promotion management
- event participation
- settings

Merchant functionality must always respect lifecycle and status restrictions.

---

### 3. Admin Experience

Accessible to administrators.

Includes:

- merchant approval and management
- product moderation
- category management
- promotions management
- event moderation
- payout oversight
- reporting and analytics
- system configuration

---

### 4. Core Systems

The platform includes the following core systems:

- authentication (users, merchants, admin roles)
- merchant lifecycle management
- product lifecycle management
- cart system
- checkout system
- order system
- payout logic
- subscription (creator plan) logic
- promotion logic
- event and showcase logic

---

## Merchant Lifecycle (Mandatory Scope)

The system must support the full merchant lifecycle:

1. Registration / Login
2. Onboarding
3. `pending_review`
4. Admin approval or rejection
5. `accepted_pending_subscription`
6. Creator plan activation
7. `active` merchant
8. Full access to platform features

No merchant functionality may bypass this lifecycle.

---

## Product Lifecycle (Mandatory Scope)

The system must support:

1. Product creation
2. Product review / approval
3. Active public visibility
4. Optional promotion
5. Status changes (inactive, rejected, etc.)

---

## Customer Flow (Mandatory Scope)

The system must support:

1. Browsing creators and products
2. Viewing product details
3. Adding products to cart
4. Checkout process
5. Order placement
6. Pickup-based fulfillment (where applicable)

---

## Monetization Scope

The system must include:

- creator plan activation and subscription logic
- product promotions
- featured / promoted visibility
- payout and fee logic
- event-based monetization (where applicable)

---

## Database Scope

The architecture must support all core entities from the start.

Includes:

- users
- merchants / creator profiles
- merchant onboarding data
- subscriptions / creator plans
- categories
- products
- product approval metadata
- carts
- cart_items
- orders
- order_items
- payouts
- promotions
- promotion payments
- events
- event registrations
- system settings

Even if not all features are implemented immediately, the architecture must assume their existence.

---

## Implementation Strategy

Implementation is incremental, but scope is complete.

This means:

- tasks are executed in sequence (see `docs/ROADMAP.md`)
- each implementation must respect future modules
- no temporary architecture that requires major rewrites later

---

## Explicit Non-Restrictions

The following are NOT excluded from scope:

- subscriptions
- promotions
- payouts
- events
- analytics
- notifications

These are part of the full product and must be considered during architecture and implementation, even if implemented later in sequence.

---

## Standalone Product Rule

Floraffeine Boutique is a fully independent product.

This includes its own:

- product catalog
- cart system
- checkout system
- order flow
- merchant system
- admin system
- payment-related logic

No dependency on another project is allowed.

---

## Design Continuity Rule

Although standalone, the Boutique must visually align with the parent Floraffeine website.

This includes:

- consistent UI patterns
- consistent typography and spacing
- consistent component behavior
- consistent brand feel

Implementation must follow:

- docs/DESIGN_CONTINUITY.md
- docs/UI_COMPONENTS_REFERENCE.md

---

## Final Rule

If any ambiguity exists:

- follow source documents
- follow architecture rules
- follow design continuity
- do NOT assume missing features are out of scope