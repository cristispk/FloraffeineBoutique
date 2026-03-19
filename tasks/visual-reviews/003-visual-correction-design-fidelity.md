# Visual Audit — Task 003 (Strict Design Fidelity)

## Must-fix issues
1. “Ține-mă minte” checkbox control is not visually present.
   - On `public-login.png`, `merchant-login.png`, `admin-login.png`, and `login-mobile.png`, the label text “Ține-mă minte” is visible, but the checkbox itself is not.
   - This breaks visual hierarchy and makes the remember-me option appear missing.

2. Auth secondary links are rendered with default browser styling (blue + underline).
   - On all auth screenshots, links like “Ți-ai uitat parola?”, “Creează cont”, and “Autentificare...” appear in the browser’s default blue/underline treatment.
   - They visually stand out from the otherwise themed UI (inputs, cards, and pill buttons), creating a clear mismatch with the Floraffeine design direction.

## Optional improvements (non-blocking)
1. Minor spacing/centering consistency between desktop and mobile
   - On desktop the submit button sits centered in the card area, while on mobile it appears centered as well but the surrounding vertical rhythm feels slightly different.
   - This is subtle and not clearly a functional break.

2. reCAPTCHA visibility
   - reCAPTCHA is not visible in the captured screenshots; this may be expected depending on environment settings, but it cannot be confirmed from the artifacts alone.

