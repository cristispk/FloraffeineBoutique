# Floraffeine Boutique — Architecture

## Overview

Floraffeine Boutique is a fully standalone Laravel-based web application built using:

- Laravel (PHP)
- Blade templating
- MySQL
- Docker-based local environment

This application must be treated as a complete independent product from the beginning.

It has its own:

- public area
- merchant area
- admin area
- cart
- checkout
- order flow
- payment-related logic
- subscription logic
- promotion logic
- event and showcase logic

No shared infrastructure with any previous or external project may be assumed.

---

## Core Architectural Principle

The product must be designed as a complete system from the start.

Implementation may happen incrementally, but:

- architecture must support the full product flow
- no module should be designed as if future modules do not exist
- no task should force major rewrites later because of short-term thinking

---

## Source of Truth

### Business & Flow Source Documents

The following documents are the primary source of truth for business logic, page flow, module order, and intended functionality:

- docs/source-of-truth/floraffeine-boutique.docx
- docs/source-of-truth/floraffeine-boutique-tasks-plan.docx

### Design Source of Truth

Visual and structural UI continuity must align with:

- docs/design-source-of-truth/website-parent/index.html
- docs/DESIGN_CONTINUITY.md
- docs/UI_COMPONENTS_REFERENCE.md

---

## Mandatory Project Override

Although the source documents may conceptually reference previously shared or common platform features, this implementation must interpret everything as local to this standalone project.

This means:

- no shared cart
- no shared checkout
- no shared orders
- no shared payments
- no shared merchant infrastructure
- no shared admin infrastructure
- no shared public catalog infrastructure

If the source documents imply shared/common behavior, it must be reinterpreted as functionality to be implemented locally inside this project.

---

## Application Areas

The application is divided into three main areas.

### 1. Public Area

Accessible to all users and visitors.

Includes:

- homepage / landing context for Boutique
- boutique entry pages
- creators listing
- creator public profile page
- product listing and product detail pages
- cart
- checkout
- order confirmation
- promotional public pages
- events public pages
- FAQ / terms / contact pages when relevant

### 2. Merchant Area

Accessible to registered merchants/creators.

Includes:

- register / login
- onboarding flow
- merchant status page
- creator plan activation
- dashboard
- product management
- order visibility
- payout visibility
- subscription status and billing-related visibility
- promotion management
- event participation area
- settings

This area must always enforce merchant lifecycle restrictions.

### 3. Admin Area

Accessible only to administrators.

Includes:

- merchant review and approval
- merchant detail management
- category management
- product approval / visibility control
- payouts review / validation
- promotions oversight
- event moderation / management
- reporting and analytics
- global system settings

---

## Full Product Flow

The Boutique must support the full business flow from the start.

### Merchant Lifecycle

Canonical merchant flow:

1. Apply / discover Boutique
2. Register / login
3. Complete onboarding
4. Merchant enters `pending_review`
5. Admin approves or rejects
6. If approved, merchant enters `accepted_pending_subscription`
7. Merchant activates Creator Plan
8. Merchant becomes `active`
9. Merchant gains access to dashboard and operational features

Merchant features must never be available before the required lifecycle stage.

### Product Lifecycle

Canonical product flow:

1. Merchant creates product
2. Product enters review / pending approval when applicable
3. Admin approves or rejects
4. Product becomes active and visible in public area
5. Optional promotions may apply
6. Product can later be paused, edited, or removed according to business rules

### Customer Flow

Canonical public/customer flow:

1. Browse creators / products
2. View product details
3. Add to cart
4. Proceed to checkout
5. Complete order
6. Receive order confirmation
7. Use pickup flow where applicable

### Monetization Flow

Canonical monetization logic includes:

- creator plan activation
- subscription state
- promotional products / featured visibility
- payout and fee logic
- event-related monetization when applicable

---

## Status-Driven Access Architecture

The system must enforce access not only by role, but also by business status.

### Role-Based Access

Primary roles:

- user
- merchant
- admin

### Merchant Business Status

Merchant-specific statuses must be treated separately from authentication role.

Examples include:

- draft
- pending_review
- accepted_pending_subscription
- active
- rejected
- suspended

A merchant may be authenticated but still blocked from operational features depending on status.

### Product Status

Product visibility and management must depend on product status.

Examples include:

- draft
- pending_approval
- active
- rejected
- inactive

### Subscription / Plan Status

Subscription state must also be treated independently where needed.

Examples include:

- trial_active
- active_paid
- past_due
- suspended

These state machines must remain separate in architecture, even if some tasks implement them later.

---

## Routing Strategy

Routes must remain clearly separated by application area.

### Public Routes

Defined in:

- routes/web.php

Examples:

- boutique pages
- public product pages
- cart
- checkout
- public informational pages

### Merchant Routes

Prefixed with:

- `/merchant`

Examples:

- `/merchant/register`
- `/merchant/login`
- `/merchant/onboarding`
- `/merchant/status`
- `/merchant/activate`
- `/merchant/dashboard`
- `/merchant/products`
- `/merchant/orders`
- `/merchant/payouts`
- `/merchant/promotions`
- `/merchant/events`

### Admin Routes

Prefixed with:

- `/admin`

Examples:

- `/admin/login`
- `/admin/merchants`
- `/admin/products`
- `/admin/categories`
- `/admin/payouts`
- `/admin/promotions`
- `/admin/events`

---

## Access Control Architecture

Middleware must enforce:

- authentication
- role access
- ownership
- business status restrictions

Examples:

- `auth`
- `role:user`
- `role:merchant`
- `role:admin`

In addition, merchant/business status rules must be applied where required so that access is limited correctly by lifecycle state.

---

## Authentication Architecture

Authentication is implemented using:

- a single `users` table
- a `role` column (`user`, `merchant`, `admin`)
- a single session-based `web` guard
- Laravel password broker for user accounts

Business logic for registration, login, and logout belongs in dedicated auth services and related validation layers.

Authentication role alone is not sufficient for merchant access; merchant business status must also be respected.

---

## View Architecture

All UI must be built using Blade.

### View Structure

```text
resources/views/
- public/
- merchant/
- admin/
- layouts/
- components/
- partials/
```

### UI Rules

- all visible UI text must be in Romanian
- visual continuity with the parent website is mandatory
- Blade components should be used for reusable UI
- design patterns must follow the parent site reference

---

## Component-Driven UI Architecture

Reusable UI should be implemented through Blade components where appropriate.

Examples:

- buttons
- alerts
- inputs
- cards
- status badges
- tables
- empty states

The Boutique must feel like a natural extension of the parent Floraffeine website, not a visually disconnected application.

---

## Services Layer

Business logic must live in service or action classes, not in controllers.

Typical service areas include:

- AuthService
- MerchantOnboardingService
- MerchantStatusService
- CreatorPlanService
- ProductService
- ProductApprovalService
- CatalogService
- CartService
- CheckoutService
- OrderService
- PayoutService
- PromotionService
- EventService

Controllers must remain thin.

---

## Database Architecture

Database: MySQL

The data model must support the full product from the start, even if implementation is incremental.

Core entities include:

- users
- merchants / merchant profiles
- merchant onboarding data
- merchant subscriptions / creator plans
- categories
- products
- product approvals / moderation metadata
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

The exact table design may evolve, but architecture must already assume all these domains exist.

---

## Separation of Concerns

The following separations are mandatory:

### Public vs Merchant vs Admin

These areas must never be mixed carelessly.

### Auth Role vs Business Status

Authentication does not equal operational permission.

### Product State vs Merchant State

A product being active does not imply merchant state logic is irrelevant, and vice versa.

### UI Structure vs Business Logic

Visual rendering belongs in Blade.
Logic belongs in requests, services, policies, and models where appropriate.

---

## Design Continuity Rule

The Boutique is visually independent in implementation, but must preserve continuity with the parent website.

This means:

- same visual language
- same brand feeling
- same spacing philosophy
- same button and form style
- same component logic where relevant

It must NOT:

- blindly copy old HTML pages
- copy legacy JS without review
- invent a different visual system

---

## Language & UI Text Rules

All user-facing text in the application must be written in Romanian.

### Standard Terminology

Use the following canonical terminology:

- Login → Autentificare
- Register → Creează cont
- Logout → Deconectare
- Email → Email
- Password → Parolă
- Confirm Password → Confirmă parola
- Forgot Password → Ai uitat parola?
- Reset Password → Resetează parola
- Dashboard → Panou de control
- Merchant → Comerciant
- Admin → Administrator

Rules:

- do not mix English and Romanian in UI
- keep text clear, warm, and concise
- maintain consistency across all modules

---

## Development Environment

The project runs locally using Docker-based development infrastructure.

Includes:

- PHP / Laravel app container
- Nginx
- MySQL
- phpMyAdmin (development only)
- optional future supporting services such as Redis / Mailpit

Default local URL:

- http://localhost:8080

---

## Architectural Quality Rules

The following are mandatory:

- do not introduce new frameworks without approval
- do not place business logic in controllers
- do not design modules as isolated temporary solutions
- do not assume future rewrites are acceptable
- keep code readable, modular, and extensible
- always align implementation with the full Boutique product flow
- always respect the standalone project rule
- always respect design continuity with the parent website

### Terminology Source

All business and system terms are defined in:

- docs/BOUTIQUE-GLOSSARY.md

This document is mandatory for interpreting:
- merchant statuses
- product states
- subscription states
- system flows