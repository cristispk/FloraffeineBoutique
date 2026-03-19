# Release — 003 Visual Correction (Strict Design Fidelity)

## Proposed clean commit message
Fix runtime auth UI by removing fragile Vite loading

## Short release summary
This release corrects the runtime UI for the auth flows (public/merchant/admin) to match the Floraffeine design direction with strict DOM/class mapping and reliable styling at runtime. It addresses the previously broken state caused by conditional Vite asset loading when build artifacts were missing, fixes auth layout branding/charset issues, and applies the remaining visual must-fixes (remember-me checkbox visibility and auth link styling).

## What was produced (workflow artifacts / documents)
1. Screenshots:
   - `tasks/artifacts/screenshots/003-visual-correction-design-fidelity/`
     - `home.png`
     - `public-login.png`
     - `public-register.png`
     - `merchant-login.png`
     - `merchant-register.png`
     - `admin-login.png`
     - `login-mobile.png`
2. Screenshot verification note:
   - `tasks/artifacts/screenshots/003-visual-correction-design-fidelity/verification.md`
3. Reviews:
   - `tasks/reviews/003-visual-correction-design-fidelity.md` (structural/architectural + selector mapping review; identified `.brand img` must-fix)
   - `tasks/reviews/003-visual-correction-design-fidelity-runtime-fix.md` (runtime UI fix review: asset loading + auth branding/runtime)
4. Visual audits:
   - `tasks/visual-reviews/003-visual-correction-design-fidelity.md` (initial must-fix report)
   - `tasks/visual-reviews/003-visual-correction-design-fidelity-final.md` (final audit after must-fix resolutions)
5. Test reports:
   - `tasks/tests/003-visual-correction-design-fidelity.md`
   - `tasks/tests/003-visual-correction-design-fidelity-runtime-fix.md`
6. Implementation closure:
   - `tasks/done/003-visual-correction-design-fidelity.md`

## Non-blocking follow-up (track later)
- The runtime CSS fallback strategy (`public/css/app.css`) was added to keep UI styled when Vite build artifacts are absent in the current environment. This is acceptable for now, but ideally the normal Vite build/deploy pipeline should be restored so the system can return to a single source of stylesheet loading.
- reCAPTCHA should be visually re-checked in an environment where `RECAPTCHA_ENABLED=true` so the widget placement/styling can be confirmed visually.

