# MyDent — Scrollbar / Blank Sections / Login Mix Fix

**Date:** 2026-11-22  
**Scope:** Public front pages + auth login

## Root causes

1. **Duplicate vertical scrollbars**  
   `overflow-x: hidden` was set on both `html` and `body` (in `_tokens.scss` and `public/assets/front/responsive.css`). In CSS, setting `overflow-x` to a non-`visible` value forces `overflow-y` to compute as `auto` on that element — so both document roots scrolled.

2. **Blank white sections**  
   `.md-reveal` started at `opacity: 0` and only became visible via `IntersectionObserver`. If JS timing/loader/`overflow:hidden` delayed observation, sections stayed blank until a hard refresh/scroll. Failsafe was missing.

3. **Login Mix exception**  
   `auth.blade.php` / admin layout called `mix('assets/css/pages.css')`, but `public/mix-manifest.json` had no entry for that file (even though `public/assets/css/pages.css` existed on disk). Laravel Mix threw: `Unable to locate Mix file: /assets/css/pages.css`.

4. **Contact page JS error**  
   Google reCAPTCHA loaded with an empty `data-sitekey` (`.env` keys blank) → `Missing required parameters: sitekey`.

## Fixes implemented

| Area | Change |
|------|--------|
| Scroll | `html`: `overflow-x: clip; overflow-y: scroll`. `body`: `overflow-x: clip; overflow-y: visible`. Updated `responsive.css` so it no longer reintroduces dual scroll. |
| Reveal | Progressive enhancement: content visible by default; hide only under `html.md-js` until `.is-visible`. Immediate viewport reveal + 1.2s failsafe. |
| Loader | Loading lock moved to `html.md-page-loading` (not body) to avoid a second scrollbar. |
| Mix | Added `/assets/css/pages.css` (and related) to `mix-manifest.json`; try/catch fallbacks in auth + admin layouts; rebuilt `pages.css`. |
| reCAPTCHA | Script + widget only render when `config('app.google_recaptcha_site_key')` is set. |
| Login logo | Uses transparent brand mark. |

## Files changed

- `resources/assets/front/scss/_tokens.scss`
- `resources/assets/front/scss/_premium.scss`
- `public/assets/front/responsive.css`
- `resources/views/fronts/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/fronts/medical_contact.blade.php`
- `resources/views/auth/login.blade.php`
- `public/mix-manifest.json`
- `public/css/front-pages.css` (recompiled)
- `public/assets/css/pages.css` (recompiled)
- `e2e/tests/public/loading-scroll-login.spec.ts` (new)

## Playwright results

```
npx playwright test e2e/tests/public/loading-scroll-login.spec.ts --project=chromium
11 passed (18.6s)
```

Covered: login Mix error absent; `/`, services, about, products, appointment, contact, doctors — single scrollbar, no blank reveals, no horizontal overflow; home at mobile/tablet/desktop.

## Note

Set `GOOGLE_RECAPTCHA_SITE_KEY` / `GOOGLE_RECAPTCHA_SECRET` in `.env` when you want captcha enabled on the contact form. Until then the form submits without a widget (no console error).
