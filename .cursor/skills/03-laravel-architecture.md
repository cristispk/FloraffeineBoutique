# Laravel Architecture — Floraffeine Boutique

## Purpose

This document defines the mandatory Laravel architecture for the project.

All implementation MUST follow this structure.

This is NOT optional.

---

## Core Principle

Laravel must be used as a structured, service-oriented framework.

NOT as a controller-heavy or model-heavy system.

---

# 1. Layered Architecture

The application is divided into:

- Controllers (entry point)
- Form Requests (validation)
- Services (business logic)
- Models (data)
- Views (presentation)

---

## Flow

Request → FormRequest → Controller → Service → Model → Response

---

# 2. Controllers (Thin Only)

Controllers must:

- receive request
- call service
- return response

---

## Rules

- NO business logic
- NO complex conditionals
- NO data processing
- NO direct DB queries (except trivial reads if justified)

---

## Example

BAD:

```php
public function store(Request $request)
{
    $user = Auth::user();

    if ($user->role === 'merchant') {
        // logic here
    }

    DB::table('products')->insert([...]);
}
```

GOOD:

```php
public function store(StoreProductRequest $request, ProductService $service)
{
    $service->createProduct($request->validated());

    return redirect()->route('merchant.products.index');
}
```

---

# 3. Form Requests (Validation Layer)

All validation must be handled via Form Requests.

---

## Rules

- NEVER validate in controller
- NEVER trust frontend validation
- ALL input must be validated server-side

---

## Responsibilities

- validation rules
- authorization logic (if needed)
- error messages

---

# 4. Services (Business Logic)

All business logic goes into Services.

---

## Rules

- NO business logic in controllers
- NO business logic in models
- NO duplicated logic across services

---

## Responsibilities

- workflows
- calculations
- lifecycle transitions
- orchestration

---

## Example

```php
class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
}
```

---

# 5. Models (Data Layer Only)

Models represent database structure.

---

## Rules

- NO business logic
- relationships allowed
- scopes allowed (light logic only)
- accessors/mutators allowed

---

## Allowed

- relationships
- query scopes
- attribute casting

---

## NOT allowed

- workflows
- lifecycle logic
- complex calculations

---

# Final Principle

Clean architecture is NOT optional.
