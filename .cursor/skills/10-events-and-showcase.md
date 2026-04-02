# Events & Showcase — Floraffeine Boutique

## Purpose

Defines the structure and behavior of:

- events (campaign-based collections)
- showcase (curated product visibility)

This document ensures:

- consistent product exposure rules
- controlled campaign participation
- strict eligibility enforcement
- alignment with product and merchant lifecycle

---

## Core Principle

Events and showcase MUST NOT override core system rules.

A product can appear ONLY if:

- product.status = active
- merchant.status = active
- subscription.status ∈ (trial_active, active_paid)

If any of these fail:

→ product MUST NOT be displayed

---

## Architecture Responsibility

Logic MUST be handled via services.

Recommended:

- EventService
- ShowcaseService
- EventRegistrationService

Controllers MUST remain thin.

Models MUST NOT contain business logic.

Services MUST be the single source of truth for event and showcase logic.

---

# 1. Events

## Definition

An event is a curated campaign defined by:

- theme (e.g. Valentine's Day, Spring Collection)
- time window
- selected merchants and/or products

Events are time-bound and controlled by admin.

---

## Database Structure

### events

- id
- name
- description
- starts_at
- ends_at
- status (draft, active, inactive)
- created_at
- updated_at

---

### event_registrations

- id
- event_id
- merchant_id
- status (pending, approved, rejected)
- created_at

---

### event_products (RECOMMENDED)

- id
- event_id
- product_id
- created_at

---

## Event Lifecycle

### draft

- created by admin
- not visible publicly
- registrations may be open (optional)

---

### active

- visible to users
- products included in listings
- must respect time window

---

### inactive

- expired OR manually disabled
- no longer visible

---

## Event Visibility Rules

Event is visible ONLY if:

- status = active
- current_date ≥ starts_at
- current_date ≤ ends_at

If outside range:

→ event MUST NOT be displayed

---

## Runtime Validation (CRITICAL)

At render time, system MUST validate:

- event is active
- event is within time window

---

## Event Participation

Merchants may:

- apply to event
- submit products for inclusion

---

## Participation Eligibility

Merchant can participate ONLY if:

- merchant.status = active
- subscription.status ∈ (trial_active, active_paid)

If not:

→ registration must be rejected

---

## Product Eligibility for Events

A product can be included ONLY if:

- product.status = active
- product belongs to merchant
- merchant is active
- subscription is valid
- product passes moderation (if enabled)

Validation MUST be done server-side.

---

## Admin Controls

Admin can:

- create events
- update event data
- activate/deactivate events
- approve/reject merchant registrations
- manually attach/detach products
- override automatic inclusion rules (if needed)

---

## Event Integrity Rule

Events MUST NEVER:

- expose inactive products
- expose suspended merchants
- bypass subscription rules
- override product lifecycle

Events are secondary to core eligibility.

---

## Critical Rule

Event listings MUST be filtered at query level using:

- product.status
- merchant.status
- subscription.status

NOT only at UI level.

---

# 2. Showcase

## Definition

Showcase is a curated display of:

- selected products
- selected merchants

It is NOT necessarily time-bound.

---

## Showcase Behavior

- manually curated by admin
- designed to highlight premium products
- may be persistent or periodically updated

---

## Database Structure

### showcase_items

- id
- product_id
- position (ordering priority)
- active (boolean)
- created_at
- updated_at

---

## Showcase Visibility Rules

A product appears in showcase ONLY if:

- showcase_item.active = true
- product.status = active
- merchant.status = active
- subscription.status ∈ (trial_active, active_paid)

If any condition fails:

→ product MUST NOT be displayed

---

## Runtime Validation (CRITICAL)

At render time, system MUST validate:

- product status
- merchant status
- subscription status

Invalid entries MUST be ignored dynamically.

---

## Ordering Rules

- lower position value = higher priority
- ordering must be deterministic
- no duplicate product entries allowed

---

## Relationship with Promotions

- showcase = curated (manual or strategic)
- promotion = paid visibility

A product may exist:

- only in showcase
- only in promotion
- in both

They MUST NOT conflict.

---

## Edge Cases

### Merchant Suspended

→ remove all event registrations  
→ remove all showcase entries  

---

### Subscription Expired

→ remove from events  
→ remove from showcase  

---

### Product Deactivated

→ remove from events  
→ remove from showcase  

---

### Event Expired

→ event must be hidden completely  
→ associated listings must disappear  

---

### Invalid Data

→ system must ignore invalid records  
→ must NOT crash or expose broken state  

---

## Concurrency & Consistency

System MUST ensure:

- no duplicate event-product relationships
- no duplicate showcase entries
- clean attach/detach logic

Recommended:

- unique constraints (event_id + product_id)
- unique constraints (product_id in showcase)

---

## Security Rules

System MUST:

- validate all event registrations server-side
- validate all product attachments
- enforce merchant ownership for submitted products
- prevent manual injection of event or showcase data

Forbidden:

- trusting client-provided product_id blindly
- bypassing eligibility rules
- attaching products without validation

---

## UX Rules

Events:

- must have clear theme
- must feel seasonal or intentional
- must not contain invalid products

Showcase:

- must feel curated
- must feel premium
- must not be cluttered

No silent failures.

---

## Synchronization Rule

Events and showcase must be consistent with:

- product lifecycle
- merchant lifecycle
- subscription system
- promotion system

Any mismatch is a critical issue.

---

## Final Rule

Events and showcase must:

- enhance discovery
- respect all system rules
- remain consistent and predictable

They must NEVER break core product visibility logic.

If they do:

→ system integrity is compromised.