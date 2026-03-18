# Floraffeine Boutique — Roadmap

## Purpose

This document defines the official implementation order for the Floraffeine Boutique platform.

The product is defined as a complete system, but implementation is done incrementally.

This roadmap ensures:

- correct task sequence
- proper business flow implementation
- no skipped dependencies
- no premature features

---

## Core Rule

All agents must follow this roadmap strictly.

- Do NOT skip tasks
- Do NOT reorder tasks
- Do NOT jump ahead

Each task builds on the previous ones.

---

## Full Product Coverage

The Boutique includes the following domains:

1. Merchant lifecycle
2. Product lifecycle
3. Public experience
4. Cart and checkout
5. Orders and payouts
6. Subscriptions (Creator Plan)
7. Promotions
8. Events and showcase

---

## Implementation Order

### 001 — Merchant Registration & Authentication

- Merchant register
- Merchant login
- Role-based authentication
- Basic auth flow

---

### 002 — Merchant Onboarding

- Onboarding form
- Required data collection
- Onboarding persistence

---

### 003 — Merchant Status Flow

- pending_review state
- rejected state
- status visibility page
- restrictions based on status

---

### 004 — Admin Merchant Approval

- Admin merchant list
- Merchant detail view
- Approve / Reject logic

---

### 005 — Creator Plan Activation

- accepted_pending_subscription state
- activation page
- transition to active

---

### 006 — Merchant Dashboard

- Basic dashboard
- Status-based access
- Entry point after activation

---

### 007 — Merchant Products Management

- Create product
- Edit product
- Delete product
- Product validation

---

### 008 — Admin Product Approval

- Product moderation panel
- Approve / reject products
- Product status logic

---

### 009 — Public Boutique Pages

- Boutique listing
- Creator profile page
- Product detail page

---

### 010 — Cart System

- Add to cart
- Remove from cart
- Update quantities
- Cart persistence

---

### 011 — Checkout System (Pickup-Based)

- Checkout flow
- Customer details
- Pickup logic
- Order creation

---

### 012 — Orders System

- Order storage
- Order status
- Merchant order visibility
- Basic admin visibility

---

### 013 — Payout Logic (Basic)

- Fee calculation
- Payout tracking (basic)
- Merchant payout view

---

### 014 — Subscription Logic (Creator Plan)

- Subscription states
- Activation state
- Basic subscription tracking

---

### 015 — Promotion System

- Product promotion
- Promotion visibility
- Promotion status

---

### 016 — Promotion Payments (Basic)

- Payment simulation for promotions
- Promotion activation after payment

---

### 017 — Featured / Promoted Logic

- Highlight promoted products
- Featured placement logic

---

### 018 — Merchant Promotions Dashboard

- Promotion management
- Promotion status view

---

### 019 — Events — Public Pages

- Events listing
- Event detail page

---

### 020 — Merchant Events

- Event participation
- Merchant event view

---

### 021 — Event Registration & Payment (Basic)

- Event registration
- Payment simulation

---

### 022 — Admin Events Management

- Event creation
- Event moderation
- Event control

---

### 023 — Event Promotion Integration

- Promotions linked to events
- Visibility boosts

---

### 024 — Showcase / Creator Exposure

- Creator showcase logic
- Exposure system
- Highlight creators

---

## Task Execution Rule

Each task must be:

- fully implemented
- reviewed
- tested
- documented

before moving to the next one.

---

## Dependency Rule

A task must NOT be implemented if:

- previous required tasks are incomplete
- required flow is not yet available

---

## Final Rule

If unsure what to implement next:

→ always take the next unfinished task from this roadmap

No exceptions.