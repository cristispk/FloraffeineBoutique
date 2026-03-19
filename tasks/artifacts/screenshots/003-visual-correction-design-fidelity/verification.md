## Verification (003 — Visual Correction, Runtime UI Breakage)

- Confirmed Vite build artifacts were absent (`public/build/manifest.json` and `public/hot` missing). Updated layouts to always load intended styling from `public/css/app.css` (no conditional asset-loading).
- Fixed `meta charset` typo in `resources/views/layouts/auth.blade.php` (`utf-utf-8` -> `utf-8`).
- Updated `resources/views/layouts/auth.blade.php` to include the source-of-truth branding structure in a `.brand` wrapper inside the header (so the logo sizing uses the `.brand img { height: 52px; }` rule).
- Captured screenshots for:
  - `/` (home), desktop 1280x800
  - `/login`, desktop 1280x800 + mobile 375x900
  - `/register`, desktop 1280x800
  - `/merchant/login`, desktop 1280x800
  - `/merchant/register`, desktop 1280x800
  - `/admin/login`, desktop 1280x800

Screenshots are in this folder: `tasks/artifacts/screenshots/003-visual-correction-design-fidelity/`.

