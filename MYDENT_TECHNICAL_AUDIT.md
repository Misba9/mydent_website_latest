# MYDENT 2.0 – Technical Audit

**Project:** MyDent (https://mydent.in)  
**Framework:** Laravel 10 · Blade · Livewire 2 · Spatie Permission  
**Audit mode:** Phase 1 — read-only (no source changes)  
**Date:** 2026-11-19  
**Scope:** Full repository inspection (routes, middleware, controllers, models, repositories, Livewire, Blade, migrations, seeders, JS/CSS, composer/npm, payments, auth, tests)

---

## 1. Executive summary

MyDent is a capable multi-role clinic platform (public site + admin + doctor + patient) built on a commercial InfyOm-style codebase and customized for dental / clear-aligner retail. The **core appointment/admin modules are mature**, but **bolt-on ecommerce, payment hardening, authorization helpers, and migration hygiene introduce production risk**.

| Area | Assessment |
|------|------------|
| Architecture | Adequate role-split routes + repositories for clinical core; inconsistent for Product/Cart/Order/Blog |
| Stability | Booking UI works; double-booking is only client-side filtered; several payment paths lack idempotency |
| Security | Multiple **confirmed Critical/High** issues (see `MYDENT_SECURITY_AUDIT.md`) |
| Data layer | Weak indexes on appointments; JSON orders; dangerous data-import migrations |
| Frontend | Large marketing Blade (~1386 LOC homepage); Mix + Livewire 2 + jQuery; no design system |
| Tests | Near-zero domain coverage (Breeze auth + one upgrade-route test) |
| Ops | Hostinger `deploy.sh` present; queues are `sync`; upgrade routes expose Artisan over HTTP |

**Verdict:** Not production-premium-ready until Critical/High authz, payment, and migration issues are fixed. Do **not** start UI redesign before backend stabilization (Phase 2).

---

## 2. Architecture overview

```
Public (FrontController, Blog, Product/Cart/Checkout)
        │
Auth (Breeze) + Spatie roles: clinic_admin | doctor | patient | staff
        │
┌───────┴────────┬────────────────┬─────────────────┐
│ Admin routes   │ Doctor routes  │ Patient routes  │
│ /admin/*       │ /doctors/*     │ /patients/*     │
│ permission:*   │ role:doctor    │ role:patient    │
└───────┬────────┴────────┬───────┴────────┬────────┘
        │                 │                │
   Controllers + Repositories + Livewire tables
        │
   MySQL (prod) / SQLite (local) · Media Library · Gateways
```

**Strengths**
- Clear role route files (`web.php`, `doctor.php`, `patient.php`)
- Repository layer for appointments, visits, medicines, settings
- Multi-gateway payment surface + Zoom + Google Calendar
- Livewire tables with eager-loading on primary grids

**Weaknesses**
- God objects: `User` (~655 LOC), `AppointmentController` (~650), `helpers.php` (~761)
- Ecommerce/CMS skip repositories; fat controllers
- Duplicate named routes across role files
- Authorization helpers that do not authorize (`canAccessRecord`)
- Role-name casing bugs (`Doctor` vs `doctor`)

---

## 3. Stack inventory

| Layer | Versions / notes |
|-------|------------------|
| PHP | ^8.1 |
| Laravel | ^10.21 (composer `version` still says `8.0.0` — cosmetic debt) |
| Livewire | ^2.11 + rappasoft tables |
| Auth | Breeze-style + Spatie Permission ^5.8 |
| Payments | Razorpay, Stripe, PayPal, Paytm, Paystack, Authorize.net |
| Assets | Laravel Mix 6, jQuery, Bootstrap-era CSS, FullCalendar 5, ApexCharts, Quill 1 |
| i18n | 10 languages under `lang/` |
| Queues | `QUEUE_CONNECTION=sync` (local `.env`) |
| Debug | `barryvdh/laravel-debugbar` in **require** (not require-dev) |

---

## 4. Module health matrix

| Module | Status | Notes |
|--------|--------|-------|
| Public marketing site | Functional / UI debt | Hardcoded URLs, stock/CDN images, mega Blade |
| Appointment booking | Functional with gaps | Slots filtered in UI; **no server-side overlap lock** found |
| Doctor sessions / holidays | Functional | Booked slots computed in `DoctorSessionController@getDoctorSession` |
| Admin clinical ops | Mature | Doctors, patients, visits, medicines |
| Prescriptions | Broken authz | `hasRole('Doctor')` never matches; `owner_id` invalid |
| Payments (appointments) | High risk | CSRF exceptions, weak/missing idempotency, webhook stub |
| Ecommerce checkout | Partially hardened | Signature uses `hash_equals`; amount not frozen vs Razorpay |
| Orders / inventory | Incomplete | JSON product names; quantity not decremented on sale |
| Blog / articles | Basic | Missing CMS maturity (draft/slug/SEO consistency) |
| Live consultations | Present | Zoom OAuth; incomplete destroy authz |
| Reporting / analytics | Minimal | Dashboard counts only |
| Automated tests | Insufficient | No booking/payment/ecom coverage |

---

## 5. Confirmed technical issues

Evidence-based only. Full bug catalog with remediation lives in `MYDENT_BUG_REPORT.md`.

### Critical

| ID | Module | Summary | Evidence |
|----|--------|---------|----------|
| TECH-SEC-001 | Front / DB | `/ecom` runs production SQL via `DB::unprepared` when products empty | `FrontController.php:126-141` |
| TECH-SEC-002 | Auth | Password change uses `$request->all()` into fillable including `type`/`status` | `UserController.php:203-214`, `User.php:101-118` |
| TECH-DB-001 | Migrations | Migrations import SQL dumps and reset passwords to `123456` | `2026_08_18_000001_*`, `000002_*`, `000004_*` |
| TECH-PERF-001 | Livewire | SQL injection via interpolated `whereRaw` search in 16 tables | e.g. `DoctorTable.php:63-66` |

### High

| ID | Module | Summary | Evidence |
|----|--------|---------|----------|
| TECH-PAY-001 | Razorpay | Appointment webhook is a stub; no signature verification | `RazorpayController.php:131-141` |
| TECH-PAY-002 | Payments | No duplicate-transaction guards across gateways | Multiple `Transaction::create` sites |
| TECH-PAY-003 | Checkout | Verifies signature but not frozen `amount_paise` vs cart | `CheckoutController.php:91-162` |
| TECH-AUTH-001 | Helpers | `canAccessRecord` returns true if row exists | `helpers.php:718-727` |
| TECH-AUTH-002 | Prescriptions | Role casing `Doctor` + `owner_id` → ownership checks never run | `PrescriptionController.php:137-138` |
| TECH-ROUTE-001 | Routes | Duplicate `cart.add` and many shared names across role files | `web.php:130-141` + doctor/patient |
| TECH-BOOK-001 | Booking | No DB-level unique/lock preventing double booking same slot | `AppointmentRepository` store methods |

### Medium / Low (selected)

| ID | Summary | Evidence |
|----|---------|----------|
| TECH-DB-002 | Appointments: `date` as string; no indexes on date/status/unique id | appointments migration |
| TECH-DB-003 | Orders store product names JSON; no line-item table / FKs | orders migration, `Order` model |
| TECH-MODEL-001 | Medicine / prescription relation inconsistencies | `Medicine.php`, `PrescriptionMedicineModal.php` |
| TECH-MIX-001 | Webpack Mix path typo / Livewire 2 + Mix (not Vite) | `webpack.mix.js` |
| TECH-TEST-001 | Domain tests missing | `tests/Feature/` |
| TECH-OPS-001 | HTTP upgrade routes run `migrate --force` | `routes/upgrade.php` |
| TECH-OPS-002 | Queues sync; no reminder jobs in production pattern | `.env` QUEUE_CONNECTION |

---

## 6. Database & relationships

**Counts:** ~91 migrations · ~58 models · roles in DB: `clinic_admin`, `doctor`, `patient`, `staff`

**Confirmed schema concerns**
1. `appointments.appointment_unique_id` not unique-indexed; generator may discard recursive collision result (`Appointment.php`).
2. `appointments.date` stored as string — hurts range queries and indexing.
3. `transactions.transaction_id` not unique — enables duplicate payment rows.
4. `orders` anemic — no `order_items`, no product FK, status columns added later inconsistently.
5. Product `quantity` never decremented on checkout (confirmed by reading `CheckoutController` — no stock update).
6. Dual staff concept (`Staff` model vs `User` type staff) — Livewire staff table queries users.

**Relationship notes**
- Core clinical relations (User↔Doctor/Patient, Appointment↔Doctor/Patient/Service) are present and used.
- Several alias/dead relations (`Appointment::user()`, duplicate doctor user aliases) add confusion.
- Ecommerce models lack domain relations.

---

## 7. Routing & middleware

**Middleware aliases:** `auth`, `xss`, `checkUserStatus`, `checkImpersonateUser`, `role`, `permission`, `setLanguage`

**Confirmed problems**
| Issue | Detail |
|-------|--------|
| Public privileged routes | `PUT transaction-status`, `GET manually-payment` outside auth groups (`web.php:196-197`) |
| CSRF exceptions | `razorpay-payment-success`, `razorpay-payment-failed`, `paytm-callback` |
| CheckUserStatus order | Runs `$next` **before** verifying disabled/unverified user — side effects can occur |
| XSS middleware | Global `strip_tags`; CMS update bypass; not a substitute for output escaping |
| Upgrade routes | Auth + `manage_admin_dashboard` can force migrate/seed over HTTP |
| ~496 routes | High surface area; duplicate names risk Ziggy/JS hitting wrong endpoint |

---

## 8. Payments & ecommerce

| Flow | Signature | Amount bind | Idempotency | Authz | Notes |
|------|-----------|-------------|-------------|-------|-------|
| Appointment Razorpay | HMAC with `!=` | Uses appointment amount | No | Initiate by appointmentId — weak | CSRF excepted; webhook stub |
| Ecom Razorpay | `hash_equals` | **Not** vs `amount_paise` | No | Public cart | Better than appointment path |
| Stripe | Session retrieve | From Stripe total | No | Success URL | No explicit `paid` assert found |
| PayTM | SDK | Appointment amount | No | `loginUsingId` from order payload | Session re-login risk |
| Manual payment | N/A | Appointment | No | **Public GET** | Anyone can create pending txn |
| COD | N/A | Server cart totals | N/A | Public | OK for COD; no stock decrement |

Cart prices are set from DB at add-time and trusted from session at checkout — acceptable if prices cannot be client-posted; still vulnerable to **stale prices** if admin changes product price after add.

---

## 9. Frontend / assets

- Public layout: SEO meta + JSON-LD dentist schema present (good baseline).
- Admin layout: Poppins, dark mode, Livewire + Turbo/Turbolinks hybrid, Stripe + Razorpay scripts global.
- Homepage is a single large Blade with mixed local/CDN assets and hardcoded production URLs.
- No Playwright/Cypress in `package.json`.
- Asset pipeline: Mix (legacy); Quill 1 / old Stripe PHP SDK versions.

---

## 10. Testing posture

| Suite | Contents |
|-------|----------|
| Feature | Authentication, Registration, Password reset/confirm, Email verification, Example, UpgradeRouteAuthorization |
| Unit | Example only |
| E2E | None |

`UpgradeRouteAuthorizationTest` is a positive signal (guards upgrade URLs). No tests for booking, Razorpay verify, checkout, prescriptions, or role IDOR.

---

## 11. Performance observations

| Finding | Type | Notes |
|---------|------|-------|
| Livewire `whereRaw` injection | Security + perf | Must parameterize |
| Appointment queries filter string dates | Perf | Add proper date column/index |
| `PatientTable` eager-loads full appointments | Perf | Use `withCount` only |
| Homepage mega-template + CDNs | Perf / UX | Split components; self-host critical assets |
| `Setting::all()` cached statically in helpers | OK | Watch for stale settings after admin edits |
| Queue sync | Ops | Emails block request thread |

---

## 12. Configuration & deployment

- Local `.env`: SQLite, `APP_DEBUG=true`, `APP_ENV=local` (expected for local).
- Production URL comments and Hostinger DB names present in `.env` comments — ensure production never ships with debug true.
- `deploy.sh`: down → composer → storage:link → migrate --force → optimize → up. **No** backup step, **no** health check, **no** asset build.
- SQL dumps and large zips (`dist.zip`, production SQL) live in repo root — operational and privacy risk.
- HTTPS force exists for production via `AppServiceProvider`.

---

## 13. Evidence classification key

Used across all Phase 1 documents:

- **CONFIRMED** — Verified in source (file + behavior clear from code).
- **POTENTIAL** — Plausible risk; needs runtime exploit/test to fully prove impact.
- **GAP** — Missing capability vs premium target (not a bug).

---

## 14. Recommended next step

1. Review and approve findings in companion docs.  
2. Proceed to **Phase 2 – Backend Stabilization** in the order defined in `MYDENT_IMPLEMENTATION_ROADMAP.md`.  
3. **Do not** begin Phase 3 UI redesign until Critical/High security and payment items are closed.

---

*End of technical audit.*
