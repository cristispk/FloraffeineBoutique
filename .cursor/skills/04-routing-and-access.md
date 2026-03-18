# Routing & Access — Floraffeine Boutique

## Purpose

Define strict routing, middleware, and access control rules.

All routes MUST respect separation between:
- public
- merchant
- admin

---

## 1. Route Files Structure

Routes are split by area:

- routes/public.php
- routes/merchant.php
- routes/admin.php

---

## 2. Route Service Provider

Each route file must be loaded with proper middleware:

- public → web
- merchant → web + auth + role:merchant
- admin → web + auth + role:admin

---

## 3. Public Routes

Accessible without authentication.

Examples:

- homepage
- product catalog
- product details
- login / register

Rules:

- NO merchant data exposure
- NO admin actions
- read-only access only

---

## 4. Merchant Routes

Accessible ONLY by authenticated merchants.

Middleware:

- auth
- role:merchant

---

### Additional Middleware

- merchant.onboarding
- merchant.active

---

### Rules

- merchants can only access their own data
- no cross-merchant access
- onboarding must be enforced step-by-step

---

## 5. Admin Routes

Accessible ONLY by admins.

Middleware:

- auth
- role:admin

---

### Rules

- full access to system data
- actions must be protected
- sensitive actions must be logged

---

## 6. Middleware Responsibilities

Middleware must handle:

- authentication
- role validation
- merchant status validation
- ownership validation

---

## 7. Merchant Status Middleware

Examples:

- merchant.draft → block access
- merchant.onboarding → restrict to onboarding
- merchant.pending_review → lock system
- merchant.active → full access

---

## 8. Access Control Rules

- users cannot access merchant routes
- merchants cannot access admin routes
- admin cannot use merchant routes as merchant

---

## 9. Ownership Rules

- merchants can only access their own:
  - products
  - orders
  - data

---

## 10. Route Naming Convention

Use prefixes:

- public.*
- merchant.*
- admin.*

Example:

- merchant.products.index
- admin.merchants.review

---

## 11. Forbidden Practices

- mixing public and merchant routes
- missing middleware
- exposing sensitive endpoints
- direct access to protected URLs

---

## Final Principle

Routing defines system security.

If routing is wrong:

→ the system is vulnerable
