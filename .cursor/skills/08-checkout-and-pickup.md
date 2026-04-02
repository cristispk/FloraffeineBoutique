# Checkout & Pickup — Floraffeine Boutique

## Purpose

Defines the complete checkout flow, order creation logic, and pickup behavior.

This document ensures:

- consistent order creation
- strict validation rules
- correct product eligibility
- predictable customer experience
- backend authority over all order-critical data

---

## Core Principle

Checkout is allowed ONLY if:

- product.status = active
- merchant.status = active
- cart is valid
- cart is not empty

If any of these fail:

→ checkout MUST be blocked

---

## Supported Fulfillment Types

### 1. Pickup (MANDATORY)

Primary fulfillment method.

Flow:

- user places order
- merchant prepares order
- customer picks up from merchant location

---

### 2. Delivery (NOT IN SCOPE)

- not implemented
- must NOT be partially implemented
- must NOT affect current checkout logic
- must NOT appear as fake selectable option

---

## Architecture Responsibility

Checkout logic MUST be split clearly.

### Controllers

Controllers must:

- receive request
- call service
- return response

Controllers must NOT:

- calculate totals
- validate cart business rules directly
- create orders inline
- trust frontend values

---

### Services

Checkout flow should be handled through clear services such as:

- CartService
- CheckoutService
- OrderService

Services MUST enforce:

- cart validity
- product eligibility
- merchant eligibility
- pricing integrity
- order creation integrity

---

## Checkout Flow (Step-by-Step)

### Step 1 — Cart Review

User sees cart items.

Rules:

- quantities may be editable
- invalid products may be removed automatically
- unavailable items must not be silently ordered

---

### Step 2 — Proceed to Checkout

User clicks:

→ "Finalizează comanda"

If cart is empty:

→ redirect back to cart

---

### Step 3 — Customer Data

Required fields:

- nume
- email
- telefon

Optional:

- observații

If guest checkout is allowed:

- user_id may be null
- customer data remains mandatory

---

### Step 4 — Server Validation

System MUST validate server-side:

- cart is not empty
- products still exist
- products are active
- merchants are active
- quantities are valid
- prices are current
- fulfillment type is valid
- customer data is valid

If validation fails:

→ checkout must stop
→ user must be redirected back with clear errors

---

### Step 5 — Atomic Order Creation

Order creation MUST be atomic.

Use database transaction for:

- order creation
- order item creation
- total calculation persistence
- cart cleanup after successful completion

Example:

~~~php
DB::transaction(function () use ($validatedData, $cart) {
    // create order
    // create order items
    // clear cart
});
~~~

Partial creation is FORBIDDEN.

It must NEVER be possible to have:

- order without order items
- cleared cart without order
- order items without parent order

---

## Database Structure

### orders table

Suggested fields:

- id
- user_id (nullable for guest)
- total_amount
- status (pending, paid, cancelled)
- fulfillment_type (pickup)
- customer_name
- customer_email
- customer_phone
- customer_notes (nullable)
- payment_status (pending, paid, failed) — future-safe
- external_payment_reference (nullable) — future-safe
- created_at
- updated_at

---

### order_items table

Suggested fields:

- id
- order_id
- product_id
- merchant_id
- product_title_snapshot
- product_price_snapshot
- quantity
- line_total
- created_at
- updated_at

---

## Snapshot Rule (CRITICAL)

At order time, store snapshots of:

- product title
- product price

Recommended additional snapshot fields when relevant:

- merchant name
- fulfillment label
- option labels if later introduced

Reason:

→ future product changes MUST NOT affect existing orders

---

## Price Integrity Rule

At checkout:

- system recalculates total on backend
- frontend values are never trusted

If mismatch exists:

→ backend values win

Frontend price is informational only.
Backend price is authoritative.

---

## Cart Rules

- one active cart per user or guest session
- cart should be persisted predictably
- cart must be cleared ONLY after successful order creation
- cart must NOT be cleared on failed validation
- invalid items must not survive unnoticed into completed order flow

Recommended:

- database-backed cart for consistency
- guest cart linked to session token
- authenticated cart linked to user_id

---

## Product Validation Rules

Before order creation:

- product must exist
- product must be active
- merchant must be active
- product must remain checkout-eligible

If NOT:

→ item must be removed from cart OR checkout must be blocked explicitly

No invalid product may enter an order.

---

## Merchant Dependency Rule

Checkout eligibility depends on merchant status.

If merchant becomes:

- suspended
- rejected
- inactive

Then:

→ all merchant products become unavailable for checkout immediately

This must be enforced at checkout time, not only catalog time.

---

## Availability Rule

Checkout must validate final availability at the moment of order creation.

Listing visibility alone is not sufficient.

System must recheck:

- product state
- merchant state
- any fulfillment constraints in scope
- any inventory / slot constraints if implemented later

---

## Duplicate Submission Protection

System MUST prevent duplicate order creation from repeated submissions.

Recommended protections:

- idempotency token
- one-time checkout token
- server-side duplicate submission guard
- transaction-safe flow

Duplicate submission must NOT create multiple valid orders unintentionally.

---

## Order Status Flow

Supported states:

- pending → initial state
- paid → after payment confirmation
- cancelled → manual or automatic

Rules:

- order starts as pending
- payment-related transitions must be explicit
- future payment integrations must not break base order flow

---

## Merchant Perspective

Merchant can:

- view incoming orders
- view order items
- view customer details needed for fulfillment

Merchant cannot:

- modify order content
- change product snapshots
- change price snapshots
- rewrite already placed order data

Order integrity is immutable after placement, except allowed status handling.

---

## Guest Checkout Rule

Guest checkout may be allowed only if explicitly supported.

If enabled:

- user_id = null
- customer_name required
- customer_email required
- customer_phone required

Guest checkout must still follow all validation and integrity rules.

---

## Security Rules

System MUST:

- validate all input server-side
- never trust frontend data
- protect checkout routes with correct CSRF handling
- prevent manual order injection
- avoid exposing internal order logic through client-controlled payload
- enforce ownership where applicable after order creation

Forbidden:

- trusting client total_amount
- trusting client product price
- trusting client merchant status
- accepting arbitrary order_items payload without backend verification

---

## UX Rules

Checkout UX must be:

- clear
- predictable
- explicit on failure
- explicit on success

Examples:

- "Produsul nu mai este disponibil"
- "Comerciantul nu mai este activ"
- "Coșul tău este gol"
- "Comanda a fost plasată cu succes"

No silent failures.
No confusing redirects without explanation.

---

## Edge Cases

### Product Becomes Inactive During Checkout

→ checkout must block OR item must be removed before order creation

---

### Merchant Becomes Suspended

→ all products become unavailable immediately

---

### Empty Cart Access

→ redirect to cart page

---

### Duplicate Submission

→ must be prevented via idempotency or equivalent protection

---

### Price Changed After Cart Add

→ checkout recalculates with backend price
→ backend price is final

---

### Product Deleted After Add to Cart

→ item must be removed or checkout blocked

---

### Guest Session Lost

→ cart recovery behavior must be explicit
→ no fake success flow

---

## Synchronization Rule

Checkout rules must be consistent across:

- database
- services
- controllers
- UI
- cart behavior
- order persistence

Any mismatch is a critical bug.

---

## Final Rule

Checkout must be:

- safe
- predictable
- strictly validated
- atomic
- backend-authoritative

If checkout allows invalid data:

→ entire system integrity is compromised.