# Laravel Architecture — Floraffeine Boutique

## Purpose

This document defines the mandatory Laravel architecture for the project.

All implementation MUST follow this structure.

This is NOT optional.

---

## Core Principle

Laravel must be used as a structured, service-oriented framework.

NOT as a controller-heavy or model-heavy system.

Architecture must enforce clarity, separation of concerns, and business flow integrity.

---

# 1. Layered Architecture

The application is divided into:

- Controllers (entry point)
- Form Requests (validation layer)
- Services (business logic orchestration)
- Actions (optional, reusable logic units)
- Models (data layer)
- Views (presentation)

---

## Flow

Request → FormRequest → Controller → Service → Model → Response

---

## Architecture Rules

- Each layer has a single responsibility
- No layer may take responsibilities from another
- No shortcuts between layers

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
- NO validation logic

---

## Example

BAD:

~~~php
public function store(Request $request)
{
    $user = Auth::user();

    if ($user->role === 'merchant') {
        // logic here
    }

    DB::table('products')->insert([...]);
}
~~~

GOOD:

~~~php
public function store(StoreProductRequest $request, ProductService $service)
{
    $service->createProduct($request->validated());

    return redirect()->route('merchant.products.index');
}
~~~

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
- NO shortcuts or bypassing lifecycle rules
- do NOT use static methods for business logic
- all dependencies must be injected (no manual instantiation)

---

## Responsibilities

- workflows (orchestration layer)
- calculations
- lifecycle transitions
- orchestration between models and actions

---

## Business Flow Rule

Services MUST enforce:

- merchant lifecycle
- product lifecycle
- order lifecycle
- approval flows

No service may allow bypassing these rules.

---

## Example

~~~php
class ProductService
{
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
}
~~~

---

# 5. Actions (Optional but Recommended)

Actions are small, reusable units of logic.

---

## When to use Actions

- repeated logic across services
- isolated operations (e.g. calculations, transformations)
- improving readability of services

---

## Rules

- actions must be small and focused
- no orchestration logic (belongs to services)
- reusable and testable

---

# 6. Models (Data Layer Only)

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
- hidden side-effects

---

# 7. Query Logic

Query logic must NOT be placed in controllers.

---

## Allowed locations

- Services (simple queries)
- Model scopes (reusable filters)
- Query classes (for complex queries)

---

## Rules

- avoid duplicated queries
- keep queries reusable and readable
- no raw queries in controllers
- heavy queries must be extracted to dedicated query classes

---

# 8. Error Handling & Return Types

Services must be predictable.

---

## Rules

- return clear types (Model, DTO, bool)
- throw exceptions for invalid states
- do NOT return mixed or inconsistent structures
- do NOT silently ignore errors

---

# 9. Side Effects Rule

Business side-effects must be explicit.

---

## Rules

- no hidden logic in models
- no silent state changes
- no implicit lifecycle transitions

All important logic must be visible in Services.

---

# 10. Task-Driven Architecture

All implementation must follow tasks.

---

## Rules

- do NOT implement without a task file
- do NOT extend scope without validation
- do NOT anticipate future logic

---

# Final Principle

Clean architecture is NOT optional.

Structure > Convenience  
Clarity > Speed  
Predictability > Flexibility