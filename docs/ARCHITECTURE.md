# Floraffeine Boutique — Architecture

## Overview

Floraffeine Boutique is a fully independent Laravel-based web application built using:
- Laravel (PHP)
- Blade templating (no React, no SPA)
- MySQL database
- Docker-based local environment

This project is developed as a standalone product, with its own full business flows, database structure, cart, checkout, and administration area.

---

## Core Principles

- Use Laravel standard architecture (Controllers, Models, Requests, Services)
- Use Blade templates for all views
- Avoid SPA frameworks (no React, no Inertia, no Livewire unless explicitly required)
- Keep business logic out of controllers (use services)
- Maintain clear separation between modules
- Treat this application as fully independent from any other system

---

## Application Areas

The application is divided into three main areas:

### 1. Public Area
Accessible to all users.

Includes:
- Homepage
- Boutique listing page
- Creator (merchant) public profile page
- Product page
- Cart
- Checkout
- Promotions pages
- Events pages

---

### 2. Merchant Area
Accessible to registered creators (merchants).

Includes:
- Register / Login
- Onboarding flow
- Dashboard
- Product management
- Orders management
- Payouts
- Subscriptions (Creator Plan)
- Promotions management
- Settings

---

### 3. Admin Area
Accessible to administrators.

Includes:
- Creator approval (approve/reject merchants)
- Categories management
- Subscription plans
- Promotions moderation
- Events moderation
- Reports & analytics
- System settings

---

## Routing Strategy

Routes are grouped by area:
- public routes → `routes/web.php`
- merchant routes → prefixed with `/merchant`
- admin routes → prefixed with `/admin`

Middleware will control access:
- guest
- auth
- merchant
- admin

---

## Folder Structure (Views)

resources/views/
- public/
- merchant/
- admin/
- layouts/
- components/
- partials/

---

## Database

Database: MySQL

Core entities will include:
- users
- creators (merchants)
- categories
- products
- carts
- cart_items
- orders
- order_items
- payouts
- subscriptions
- promotions
- events

---

## Services Layer

Business logic must be placed in service classes:

`app/Services/`

Examples:
- CartService
- CheckoutService
- OrderService
- PaymentService
- PromotionService
- CreatorService

---

## Future Extensions

- Queue system (Redis)
- Email notifications
- Scheduled jobs
- Payment integrations
- Analytics and reporting

---

## Development Environment

- Docker (PHP, MySQL, Nginx)
- Local URL: http://localhost:8080

---

## Important Rules

- Do NOT introduce new frameworks without approval
- Do NOT mix responsibilities between modules
- Keep code readable and modular
- Always follow the defined architecture
- Do NOT assume any dependency on another project