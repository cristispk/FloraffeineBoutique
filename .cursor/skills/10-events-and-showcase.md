# Events & Showcase — Floraffeine Boutique

## Purpose

Defines the structure and behavior of:
- events (campaign-based collections)
- showcase (curated product visibility)

These features enhance:
- product discovery
- merchant exposure
- seasonal or thematic campaigns

---

## Core Principle

Events and showcases must NOT override core rules:

- product must be active
- merchant must be active
- subscription must be valid

---

# 1. Events

## Definition

An event is a curated campaign with:
- a theme (e.g. Valentine's Day, Spring Collection)
- a time window
- selected merchants or products

---

## events table

- id
- name
- description
- starts_at
- ends_at
- status (draft, active, inactive)

---

## Event Lifecycle

### Draft
- created by admin
- not visible

### Active
- visible in public area
- products included in listings

### Inactive
- expired or manually disabled

---

## Event Participation

Merchants can:

- apply to events
- submit products

---

## event_registrations table

- id
- event_id
- merchant_id
- status (pending, approved, rejected)

---

## Participation Rules

Merchant can join event ONLY if:

- merchant status = active
- subscription = active

---

## Product Eligibility for Event

- product must be active
- product must belong to merchant
- product must be approved if moderation exists

---

## Admin Controls

Admin can:

- create events
- approve/reject registrations
- add/remove products manually
- activate/deactivate events

---

## Event Visibility

Event is visible ONLY if:

- status = active
- current date is within range

---

# 2. Showcase

## Definition

Showcase is a curated display of:
- selected products
- selected merchants

It is NOT time-bound like events (optional).

---

## Showcase Behavior

- manually curated by admin
- highlights premium or high-quality items

---

## showcase_items table (optional)

- id
- product_id
- position
- active

---

## Visibility Rules

Product appears in showcase ONLY if:

- product = active
- merchant = active

---

## Relationship with Promotions

- showcase = curated (manual or strategic)
- promotion = paid boost

A product may be:
- in showcase
- in promotion
- in both

---

## Edge Cases

### Merchant Suspended

→ remove all event and showcase entries

---

### Product Deactivated

→ remove from event and showcase

---

### Event Expired

→ hide event and related listings

---

## UX Rules

- events must be clearly themed
- showcase must feel curated and premium
- avoid clutter

---

## Final Rule

Events and showcase must enhance discovery,
NOT break core product visibility rules.
