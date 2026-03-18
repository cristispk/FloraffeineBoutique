# Task 001 — Authentication System

## Goal

Build the authentication system for the Floraffeine Boutique platform.

The platform must support 3 access roles:
- user
- merchant
- admin

---

## Scope

### Public
- User registration
- User login
- Forgot password
- Logout

### Merchant
- Merchant registration
- Merchant login
- Forgot password
- Logout

### Admin
- Admin login
- Logout

---

## Functional Requirements

### User
- Can register with name, email, password
- Can log in
- Can log out
- Can reset password

### Merchant
- Can register with base account details
- Will later complete onboarding
- Can log in
- Can log out
- Can reset password

### Admin
- Can log in through dedicated admin area
- Can log out
- No public admin registration

---

## Technical Requirements

- Laravel standard authentication
- Blade views only
- No React / SPA
- Role-based access control
- Middleware protection by role
- Separate route groups for:
  - public auth
  - merchant auth
  - admin auth

---

## Database Notes

Authentication will start from the `users` table.

The `users` table must support role differentiation:
- user
- merchant
- admin

Possible implementation:
- `role` column in `users` table

---

## Deliverables

- authentication routes
- auth controllers
- role middleware
- login/register views
- password reset flow
- protected dashboard redirects by role

---

## Acceptance Criteria

- A user can register and log in
- A merchant can register and log in
- An admin can log in
- Users cannot access merchant/admin pages
- Merchants cannot access admin pages
- Admin access is protected

---

## Notes

This task defines only the authentication foundation.
Merchant onboarding and admin management will be handled in later tasks.