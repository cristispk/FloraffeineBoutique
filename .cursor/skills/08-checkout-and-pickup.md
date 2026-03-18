# Checkout & Pickup — Floraffeine Boutique

## Purpose

Defines the complete checkout flow, order creation logic, and pickup behavior.

This document ensures:
- consistent order creation
- strict validation rules
- correct product eligibility
- predictable customer experience

---

## Core Principle

Checkout is allowed ONLY if:

- product status = active
- merchant status = active
- cart is valid and not empty

If any of these fail:
→ checkout must be blocked

---

## Supported Fulfillment Types

### 1. Pickup (MANDATORY)

Primary fulfillment method.

Flow:
- user places order
- merchant prepares order
- customer picks up from location

---

### 2. Delivery (NOT IN SCOPE — FOR NOW)

- not implemented
- must NOT be partially implemented
- must NOT affect checkout logic

---

## Checkout Flow (Step-by-Step)

### Step 1 — Cart Review
- user sees cart items
- quantities editable
- invalid products removed automatically

---

### Step 2 — Proceed to Checkout
User clicks:
→ "Finalizează comanda"

---

### Step 3 — Customer Data

Required fields:
- nume
- email
- telefon

Optional:
- observații

---

### Step 4 — Validation

System validates:

- cart is not empty
- all products are still active
- all merchants are active
- quantities are valid
- prices are current

If validation fails:
→ user is redirected back with errors

---

### Step 5 — Order Creation

System creates:

#### orders table

- id
- user_id (nullable for guest)
- total_amount
- status (pending, paid, cancelled)
- fulfillment_type (pickup)
- customer_name
- customer_email
- customer_phone
- created_at

---

#### order_items table

- id
- order_id
- product_id
- merchant_id
- product_title_snapshot
- product_price_snapshot
- quantity

---

## Snapshot Rule (CRITICAL)

At order time, store:

- product title
- product price

Why:
→ future product changes MUST NOT affect existing orders

---

## Cart Behavior

- one active cart per user
- cart stored per session or user_id
- cart is cleared AFTER successful order creation

---

## Product Validation Rules

Before order creation:

- product must exist
- product must be active
- merchant must be active

If NOT:
→ product is removed OR checkout blocked

---

## Price Integrity Rule

At checkout:

- system recalculates total
- does NOT trust frontend price

If mismatch:
→ backend price wins

---

## Order Status Flow

- pending → initial state
- paid → after payment confirmation (future)
- cancelled → manually or automatically

---

## Merchant Perspective

Merchant sees:

- incoming orders
- order items
- customer details

Merchant CANNOT:
- modify order content
- change price
- alter snapshots

---

## Edge Cases

### Product Becomes Inactive During Checkout

→ must block checkout OR remove item

---

### Merchant Suspended

→ all their products become unavailable instantly

---

### Empty Cart Access

→ redirect to cart page

---

### Duplicate Submission

→ prevent using:
- token
- idempotency logic (recommended)

---

### Guest Checkout

Allowed (optional):

- user_id = null
- customer data required

---

## Security Rules

- validate ALL input server-side
- never trust frontend data
- enforce ownership where needed
- prevent manual order injection

---

## UX Rules

- clear error messages
- no silent failures
- clear success confirmation

Examples:

- "Produsul nu mai este disponibil"
- "Comanda a fost plasată cu succes"

---

## Final Rule

Checkout must be:

- safe
- predictable
- strictly validated

If checkout allows invalid data:
→ entire system integrity is compromised
