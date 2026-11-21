# MyDent QA – Phase 1 Architecture & Functionality Inventory

**Date:** 2026-11-20  
**Mode:** Read-only inspection (no production destructive tests)  
**Local base URL (dev):** `http://127.0.0.1:8000` (from `readme.md` / local `.env`)

---

## 1. Technology stack (verified)

| Layer | Version / tool |
|-------|----------------|
| PHP | 8.2.x (runtime); composer requires `^8.1` |
| Laravel | `^10.21` (`composer.json`) |
| Livewire | `^2.11` + Livewire Tables |
| Auth / RBAC | Laravel Breeze patterns + Spatie Permission `^5.8` |
| Frontend | Blade, Bootstrap 5 (CDN + Mix), jQuery, Hotwire Turbo, Laravel Mix 6, Sass |
| Node | v25.8.0 (local); Mix needs `NODE_OPTIONS=--openssl-legacy-provider` |
| DB (local `.env`) | SQLite → `database/database.sqlite` |
| DB (`.env.example`) | MySQL (Hostinger-oriented) |
| Tests (existing) | PHPUnit 9 Feature/Unit (~13 classes) |
| Playwright | **Not present** prior to this QA effort |

Composer package label still says `"version": "8.0.0"` — legacy InfyCare/clinic template branding; framework is Laravel 10.

---

## 2. Application architecture

```
Public Blade (fronts/*) ──► FrontController / Cart / Checkout / Appointment
Admin Livewire tables   ──► Controllers under App\Http\Controllers (+ Front\*)
Doctor portal           ──► routes/doctor.php
Patient portal          ──► routes/patient.php
Auth                    ──► routes/auth.php (Breeze controllers)
API                     ──► routes/api.php (essentially unused)
```

**Counts (approx.):**

| Asset | Count |
|-------|------:|
| Migrations | 92 |
| Eloquent models | 58 |
| Controllers (root) | ~50 |
| Front controllers | 7 |
| Web routes (expanded) | ~330 |
| API routes | 1 (`GET /api/user`) |

Route files: `web.php`, `auth.php`, `doctor.php`, `patient.php`, `upgrade.php`, `api.php`, `console.php`, `channels.php`.

---

## 3. Roles & authentication (verified)

**Spatie roles:** `clinic_admin`, `doctor`, `patient` (+ seeded `staff`).

**Guards:** `web` (session), `api` (legacy token; Sanctum/Passport **not** installed).

**Middleware of note:** `auth`, `role`, `permission`, `checkUserStatus`, `xss`, `setLanguage`, `checkImpersonateUser`, `signed`.

**CSRF exemptions:** Razorpay success/failed/webhook, PayTM callback.

**Local demo credentials** (from `readme.md` — for local QA only; inject via env, never commit secrets):

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@mydent.in` | `123456` |
| Doctor | `doctor@mydent.in` | `123456` |
| Patient | `patient@mydent.in` | `123456` |

Registration creates `User` + `Patient`, assigns `patient`, redirects to login (no auto-login).

---

## 4. Functionality inventory (existence ≠ verified working)

Legend: **Present** = code/routes/UI exist. **Verified** awaits E2E/API execution.

### A. Public website — Present

| Feature | Evidence | Notes |
|---------|----------|-------|
| Homepage | `medical` → `fronts.medicals.index` | Phase 3 UI redesign applied |
| About / Services / Doctors / Contact / FAQs | Named `medical*` + `front.faqs` | Banners via `MainBanner` |
| Appointment page | `medicalAppointment` + book form | Front book often free (`payable_amount = 0`) |
| Blog / Articles / Issues | CRUD + public show | |
| Products / Cart / Checkout | E-com routes | COD + Razorpay only on storefront |
| SEO / JSON-LD | Front layout | |

### B. Auth — Present

Login, register, forgot/reset password, email verification routes, logout, role dashboards.

### C. Appointments — Present

Public book, admin/doctor/patient appointment CRUD, slots (`doctor-session-time`), cancel via signed/encrypted link, multi-gateway payment for paid flows.

### D. Patients / Doctors / Staff — Present

Admin CRUD, doctor sessions/schedules, visits/encounters, prescriptions (patient PDF read-only after Phase 2).

### E. Admin CMS — Present

CMS, banners, homepage videos, FAQs, testimonials (admin kept; public testimonials removed), settings, roles, medicines inventory modules.

### F. E-commerce — Present

Products, cart session, checkout COD + Razorpay order/verify, orders admin status, stock decrement with lock (Phase 2).

### G. Payments — Partially wired

| Gateway | Appointment | E-com | Credentials in local `.env` |
|---------|-------------|-------|-------------------------------|
| Razorpay | Yes | Yes | Typically blank → online blocked |
| Stripe | Yes | No | Blank |
| PayTM / PayPal / Paystack / Authorize.Net | Yes | No | Blank / sandbox hardcodes |
| COD / Manual | Yes | Yes (COD) | N/A |

### H. Notifications — Partial

Mail via `MAIL_MAILER` (local: `log`). No dedicated SMS package inventoried. Do **not** send real email/SMS in automated tests.

### I. Mobile app — **Not in this repository**

No Flutter / RN / Android / iOS source. API surface is effectively empty beyond `GET /api/user`. Google Play link exists on homepage (external binary).

### J. Existing automated tests — Present (PHPUnit)

Phase 2 security/payment tests: cancel-link signing, checkout amount freeze, Razorpay webhook HMAC, upgrade route authz, `canAccessRecord`, etc.  
Stock Breeze tests (`AuthenticationTest`, `RegistrationTest`, …) are **likely mismatched** with customized registration fields and roles.

---

## 5. Third-party integrations (code present)

- Razorpay, Stripe, PayTM, PayPal, Paystack, Authorize.Net  
- Google OAuth / Calendar, Zoom  
- AWS S3 (Flysystem), DomPDF, Excel, Media Library  
- reCAPTCHA (keys often blank)  
- Ziggy (JS routes)

---

## 6. Prior audit artifacts (context)

Repo already contains Phase 1 read-only audits:

- `MYDENT_TECHNICAL_AUDIT.md`, `MYDENT_SECURITY_AUDIT.md`, `MYDENT_BUG_REPORT.md`  
- `MYDENT_UI_UX_AUDIT.md`, `MYDENT_MISSING_FEATURES.md`, `MYDENT_IMPLEMENTATION_ROADMAP.md`  

Phase 2 security/payment hardening and Phase 3 UI (Sprint 3.1–3.2) were implemented in subsequent work. **This QA pass must re-verify with automation — do not assume fixed without tests.**

---

## 7. Blockers & risks for Playwright / E2E

| ID | Blocker | Impact | Mitigation |
|----|---------|--------|------------|
| B1 | Razorpay/Stripe/etc. keys blank locally | Online payment E2E blocked | Test COD; mark Razorpay flows **blocked** until sandbox keys |
| B2 | `RAZORPAY_KEY` vs `RAZOR_KEY` config naming mismatch risk | Intermittent 503 | Align env ↔ `config/services.php` / `payments.php` before payment tests |
| B3 | Almost no REST API | API Phase 4 limited to `/api/user` + web endpoints | Treat web forms as primary; document API gap |
| B4 | No mobile source in repo | Cannot audit app binary APIs | Out of scope unless separate repo provided |
| B5 | PHPUnit uses file SQLite; some tests use `RefreshDatabase` | Risk of wiping local DB | Playwright uses HTTP only; isolate PHPUnit DB later |
| B6 | `AppServiceProvider` may force migrate on boot | Unexpected schema changes | Prefer dedicated test DB / careful local runs |
| B7 | reCAPTCHA may block forms if enabled without test keys | Registration/enquiry flakes | Disable captcha in local test env or mock |
| B8 | Node 25 + Mix OpenSSL | Asset rebuild friction | `NODE_OPTIONS=--openssl-legacy-provider` |
| B9 | Production `.env.example` has `FORCE_HTTPS` / live-like URL | Wrong base URL if copied | Playwright `BASE_URL` must point at local HTTP |

---

## 8. Testing checklist (Phase 3+ backlog)

Priority order for automation:

1. Public smoke (home, about, services, doctors, contact, faqs, products) — links/images/console  
2. Auth (login success/fail, logout, role dashboard redirect, unauthorized admin)  
3. Appointment booking happy path (free front book) + validation  
4. Cart → COD checkout  
5. Admin login + key CRUD smoke (patients, appointments, products)  
6. Doctor / patient portal smoke  
7. Security: CSRF on state-changing POSTs, guest blocked from admin, IDOR spot-checks  
8. Responsive snapshots (375 / 768 / 1440)  
9. Payment sandbox (blocked until keys)  
10. API (minimal)

---

## 9. Phase 1 verdict

| Question | Answer |
|----------|--------|
| Stack understood? | **Yes** |
| Mobile in-repo? | **No** |
| API surface for mobile? | **Negligible** |
| Safe to automate against local? | **Yes**, with env credentials + COD |
| Production Ready (overall app)? | **Deferred** — requires executed test evidence (Phases 2–10) |

**Blockers to call out before Phase 2 execution:** payment sandbox keys (B1), captcha (B7), dedicated test DB recommended (B5/B6).

---

## 10. Next step

Proceed to **Phase 2**: Playwright + TypeScript + POM under `e2e/`, env-based config, multi-browser projects, then implement and run Phase 3 smoke suite against local `php artisan serve`.
