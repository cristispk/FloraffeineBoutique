# Routing & Access — Floraffeine Boutique

## Purpose

Define strict routing, middleware, and access control rules.

All routes MUST respect separation between:

- public
- merchant
- admin

Routing is a critical security layer.

---

# 1. Route Files Structure

Routes are split by area:

- routes/public.php
- routes/merchant.php
- routes/admin.php

---

# 2. Route Service Provider

Each route file must be loaded with proper middleware:

- public → web
- merchant → web + auth + role:merchant
- admin → web + auth + role:admin

---

# 3. Route Definition Rules

- routes must ONLY define endpoints
- NO business logic in routes
- NO inline closures with logic (except trivial)

All logic must flow:

Route → Controller → Service

---

# 4. Route Groups (Mandatory)

All routes MUST be grouped using:

- prefix
- name
- middleware

---

## Example

~~~php
Route::prefix('merchant')
    ->name('merchant.')
    ->middleware(['web', 'auth', 'role:merchant'])
    ->group(function () {
        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');
    });
~~~

---

# 5. Public Routes

Accessible without authentication.

Examples:

- homepage
- product catalog
- product details
- login / register

---

## Rules

- NO merchant data exposure
- NO admin actions
- read-only access only
- apply rate limiting where needed

---

# 6. Merchant Routes

Accessible ONLY by authenticated merchants.

---

## Middleware

- auth
- role:merchant
- merchant.onboarding
- merchant.active

---

## Rules

- merchants can only access their own data
- no cross-merchant access
- onboarding must be enforced step-by-step

---

# 7. Admin Routes

Accessible ONLY by admins.

---

## Middleware

- auth
- role:admin

---

## Rules

- full access to system data
- sensitive actions must be logged
- destructive actions must be protected

---

# 8. Middleware Responsibilities

Middleware must handle:

- authentication
- role validation
- merchant status validation
- ownership validation

---

# 9. Merchant Status Middleware

Examples:

- merchant.draft → block access
- merchant.onboarding → restrict to onboarding
- merchant.pending_review → lock system
- merchant.active → full access

---

# 10. Ownership Rules

- merchants can only access their own:
  - products
  - orders
  - data

---

## Route Model Binding Rule

- always use route model binding
- always validate ownership after binding
- never trust ID alone

---

# 11. Access Control Rules

- users cannot access merchant routes
- merchants cannot access admin routes
- admin cannot use merchant routes as merchant

---

# 12. Route Naming Convention

Use prefixes:

- public.*
- merchant.*
- admin.*

---

## Examples

- merchant.products.index
- admin.merchants.review

---

# 13. Security Rules

- apply rate limiting where needed
- protect sensitive routes
- never expose internal endpoints
- validate all access via middleware

---

# 14. Forbidden Practices

- mixing public and merchant routes
- missing middleware
- exposing sensitive endpoints
- direct access to protected URLs
- business logic in routes

---

# Final Principle

Routing defines system security.

If routing is wrong:

→ the system is vulnerable