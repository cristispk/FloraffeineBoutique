# Test Report — Task 004 Shared User + Merchant Login Role Routing

## Test scope
Runtime behavior validation only (no code-structure review), executed via simple sequential HTTP requests against `http://localhost:8080` in Docker environment.

Test accounts used:
- `test_user_004@example.com`
- `test_merchant_004@example.com`
- `test_admin_004@example.com`

---

## Results by required scenario

### 1) Shared login behavior (`/login`)
- User login (`test_user_004`) -> redirected to `http://localhost:8080/dashboard` ✅
- Merchant login (`test_merchant_004`) -> redirected to `http://localhost:8080/merchant/dashboard` ✅
- Admin login via `/login` (`test_admin_004`) -> stayed on `http://localhost:8080/login` with invalid-credentials message ✅

### 2) Admin login isolation (`/admin/login`)
- Admin login (`test_admin_004`) -> redirected to `http://localhost:8080/admin/dashboard` ✅
- User login via `/admin/login` -> invalid credentials, remained on `http://localhost:8080/admin/login` ✅
- Merchant login via `/admin/login` -> invalid credentials, remained on `http://localhost:8080/admin/login` ✅

### 3) Merchant compatibility route
- `GET /merchant/login` -> `302` redirect to `http://localhost:8080/login` ✅
- `POST /merchant/login` -> `302` redirect to `http://localhost:8080/login` ✅
- POST to `/merchant/login` did **not** authenticate user (subsequent `/dashboard` access resolved to login) ✅

### 4) Invalid credentials behavior
- Wrong credentials on `/login` returned the same invalid-credentials message:  
  `These credentials do not match our records.` ✅
- No explicit role-specific hinting observed in failure response (no “wrong role / admin credentials / merchant credentials” style messaging) ✅

### 5) Registration flows
- User registration (`/register`) succeeded -> redirected to `http://localhost:8080/dashboard` ✅
- Merchant registration (`/merchant/register`) succeeded -> redirected to `http://localhost:8080/merchant/dashboard` ✅

### 6) Password reset flows
- User forgot-password submit (`/password/forgot` -> `/password/email`) returned success status on forgot page ✅
- Merchant forgot-password submit (`/merchant/password/forgot` -> `/merchant/password/email`) returned success status on forgot page ✅
- User reset-password flow (token submit) succeeded -> redirected to `http://localhost:8080/dashboard` ✅
- Merchant reset-password flow (token submit) succeeded -> redirected to `http://localhost:8080/merchant/dashboard` ✅
- Merchant forgot/reset pages include back-to-login links resolving to shared `/login` ✅

### 7) Role-based access protection
- Logged in as user, opened `/merchant/dashboard` -> redirected to `http://localhost:8080/dashboard` ✅
- Logged in as merchant, opened `/dashboard` -> redirected to `http://localhost:8080/merchant/dashboard` ✅

### 8) reCAPTCHA behavior
- With `RECAPTCHA_ENABLED=false` -> login works without recaptcha token ✅
- With `RECAPTCHA_ENABLED=true` -> login without token blocked and returned to `/login` with recaptcha-related validation feedback ✅
- Environment restored to `RECAPTCHA_ENABLED=false` after test and caches cleared ✅

---

## Critical bugs
- None found.

## Edge cases
- None found in requested scope.

## Minor UX inconsistencies
- None observed in requested scope.

---

## Overall verdict
Task 004 runtime behavior matches the requested functional expectations across shared login, admin isolation, merchant compatibility redirect behavior, registration/reset continuity, role-protection redirects, and recaptcha toggle behavior.

