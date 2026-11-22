# MYDENT 2.0 – Phase 1 Application Audit (Fresh Pass)

**Date:** 2026-11-22  
**Mode:** Read-only inspection (no production writes; no deploy)  
**Targets:** Codebase + local SQLite (`DB_CONNECTION=sqlite`)  
**Production URL (out of scope for destructive tests):** https://mydent.in  
**Local URL:** http://127.0.0.1:8000  

**Prior artifacts consulted (not assumed fixed without re-test):**  
`QA_PHASE1_ARCHITECTURE_INVENTORY.md`, `QA_INTERIM_REPORT.md`, `MYDENT_*_AUDIT.md`, `MYDENT_BUG_REPORT.md`

---

## 1. Stack (verified this pass)

| Layer | Verified value |
|-------|----------------|
| PHP | 8.2.33 (CLI) |
| Laravel | 10.50.4 |
| Composer require | `php ^8.2`, `laravel/framework ^10.50.4` |
| Auth / RBAC | Session `web` guard + Spatie Permission (`role` / `permission` middleware) |
| UI | Blade + Livewire 2 tables + Bootstrap admin chrome + Mix front assets |
| Local DB | SQLite → `database/database.sqlite` |
| Queue / Mail (local) | `sync` / `log` |
| API | Essentially unused (`GET /api/user` only, `auth:api`) |
| Playwright | Present under `e2e/` (`@playwright/test` ^1.65) |
| PHPUnit | Feature + Unit tests under `tests/` |

---

## 2. Architecture map

```
Public fronts (setLanguage)  → FrontController, Cart, Checkout, Blog, Products
Auth (Breeze-style)          → routes/auth.php
Admin (/admin + permissions) → Controllers + Livewire tables
Doctor (/doctors)            → routes/doctor.php  role:doctor
Patient (/patients)          → routes/patient.php role:patient
Payments                     → Razorpay / Stripe / PayTM / PayPal / Paystack / Authorize.Net
Upgrade HTTP                 → routes/upgrade.php (disabled when APP_ENV=production)
```

**Route inventory (`php artisan route:list --json`):** **491** total  

| Bucket | Approx. count |
|--------|--------------:|
| Admin / clinic_admin / manage_* | 251 |
| Doctor prefix | 73 |
| Patient prefix | 39 |
| Auth-related URIs | 15 |
| Public / unauthenticated | 112 |
| API | 1 |

**Code counts:** 58 Eloquent models · 92 migrations · ~65 controllers · 41 Livewire tables · 2 policies (`Appointment`, `Prescription`) · 34 repositories

---

## 3. Authentication & roles

**Spatie roles (seeded in code):** `clinic_admin`, `doctor`, `patient` (+ staff via staff seeder / permissions)

**Role redirect helper:** `getDashboardURL()` in `app/helpers.php`  
- clinic_admin → admin dashboard  
- doctor → doctors dashboard  
- patient → patients dashboard  

**Auth routes:** register, login, logout, forgot/reset password, email verify (throttled), confirm password  

**Registration:** creates `User` + `Patient`, assigns `patient` role (see `RegisteredUserController`)  

**Middleware aliases:** `auth`, `role`, `permission`, `checkUserStatus`, `xss`, `checkImpersonateUser`, `setLanguage`, `signed`, `throttle`, `verified`

**CSRF exemptions:** Razorpay success/failed/webhook, PayTM callback only

**Demo credentials (local seeders / e2e/.env — local QA only):**

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@mydent.in | 123456 |
| Doctor | doctor@mydent.in | 123456 |
| Patient | patient@mydent.in | 123456 |

---

## 4. Module inventory (existence = code/routes; working = not claimed yet)

### Admin (`/admin`, mostly `role:clinic_admin` + Spatie permissions)

| Module | Evidence | Notes |
|--------|----------|-------|
| Dashboard / analytics | `DashboardController@index`, `permission:manage_admin_dashboard` | Basic counts |
| Doctors | `UserController` resource + status/session | `manage_doctors` |
| Patients | `PatientController` | `manage_patients` |
| Appointments + calendar + PDF | `AppointmentController` | `manage_appointments` |
| Doctor sessions / schedules | `DoctorSessionController` | `manage_doctor_sessions` |
| Services / categories | `ServiceController`, `ServiceCategoryController` | `manage_services` |
| Specializations | `SpecializationController` | `manage_specialities` |
| Staff | `StaffController` | `manage_staff` |
| Roles | `RoleController` | `manage_roles` |
| Settings / clinic schedules / holidays | `SettingController`, `ClinicScheduleController`, `HolidayContoller` | `manage_settings` |
| Countries / states / cities | resources | geo permissions |
| Currencies | `CurrencyController` | `manage_currencies` |
| Visits / encounters | `VisitController` | `manage_patient_visits` |
| Front CMS / FAQs / testimonials / enquiries / subscribers | Front CMS controllers | `manage_front_cms` |
| Banners / homepage videos | Admin controllers | clinic_admin group |
| Articles / blogs / products / orders | Separate admin middleware groups | Mixed naming |
| Medicines / brands / categories / bills / purchase | Second admin group | clinic_admin |
| Prescriptions | `PrescriptionController` | Shared with doctor |
| Transactions | under appointments permission | |
| Impersonation | `impersonate/{id}` | admin only |
| Log viewer | `admin/logs` | sensitive |

### Doctor (`/doctors`, `role:doctor`)

Dashboard, appointments (+ calendar/PDF/status), sessions/schedule, visits (+ clinical sub-records), prescriptions, holidays, live consultations / Zoom, transactions, Google Calendar, patient detail

### Patient (`/patients`, `role:patient`)

Dashboard, appointments (+ book/cancel/calendar/payment), visits (read), transactions, reviews, live consultations start, prescriptions **read/PDF only** (no CUD)

### Public / ecommerce

Home, about, services, doctors, appointment, contact, FAQs, terms/privacy, products/ecom, cart, checkout (COD + Razorpay), blogs/articles/issues, enquire/subscribe, front appointment book

### Gaps (code absent or thin — not bugs)

- No first-class patient self-service **reschedule** flow  
- No rich REST API for mobile  
- No mobile app source in this repo  
- SMS/WhatsApp appointment reminders not first-class  
- Online payment E2E blocked without sandbox keys  

---

## 5. Database & relationships (local SQLite snapshot)

**Tables present:** 65+ including `users`, `doctors`, `patients`, `appointments`, `visits` (+ notes/observations/prescriptions/problems), `prescriptions`, `services`, `products`, `orders`, `transactions`, Spatie ACL tables, CMS/ecommerce/medicine tables.

**Relationships (code):** User 1–1 Doctor/Patient/Staff; Doctor/Patient ↔ Appointments; Visit clinical children; Prescription medicines; Orders products JSON; Spatie roles/permissions morph to User.

### CRITICAL LOCAL FINDING — test DB not usable

| Check | Result |
|-------|--------|
| `users` count | **0** |
| Spatie `roles` | **only** `clinic_admin`, `doctor` — **`patient` missing** |
| `permissions` count | **2** (`manage_doctors_holiday`, `manage_medicines`) — incomplete vs seeders |
| `patients` / `doctors` / `appointments` / `orders` | **0** |
| `products` | 4 |

**Impact:** Admin/doctor/patient panel login and prior Playwright smoke **cannot pass** against current local DB until seed/repair.  
**Cause candidates:** incomplete seed after migrate; `AppServiceProvider` auto-`migrate --force` on boot; recent composer/env work on 2026-11-22 (sqlite mtime 08:39).

**Rule:** Seed/repair **local SQLite only**. Do not reset Hostinger/production MySQL.

---

## 6. Authorization / security code status (static re-check)

| Prior bug ID | Topic | Static status this pass |
|--------------|-------|-------------------------|
| BUG-001 | Public `/ecom` SQL dump import | **Not found** in `FrontController` (likely fixed) — still needs runtime verify |
| BUG-002 | `changePassword` mass-assign | **Fixed in code** — updates password only |
| BUG-004 | Livewire search SQLi | **Bound parameters** observed in sampled tables |
| BUG-005 | `canAccessRecord` existence-only | **Fixed in code** — role-scoped; deny-by-default |
| BUG-006/007 | Public transaction/manual payment | **Routes gated** to auth + admin/doctor |
| Upgrade routes | migrate via HTTP | **Disabled in production** env |
| AppServiceProvider boot migrate | Auto `migrate --force` when `users` table exists | **Still present** — risky for any env; local schema drift risk |

Policies registered: Appointment + Prescription. Many modules still rely on role middleware + `canAccessRecord` / inline checks — IDOR matrix still required in Phase 5.

---

## 7. Frontend components

| Surface | Path pattern |
|---------|--------------|
| Marketing | `resources/views/fronts/**` (+ Blade components under `components/front`) |
| Auth | `resources/views/auth/**`, `layouts/auth.blade.php` |
| Admin shell | `layouts/app.blade.php`, `sidebar`, Livewire tables |
| Doctor/Patient | Shared admin-ish layouts + portal-specific views |
| Assets | Laravel Mix (`webpack.mix.js`), `public/css|js|assets` |

Recent redesign notes: `MYDENT_REDESIGN_REPORT.md`, `QA_LOADING_SCROLL_LOGIN_FIX.md`.

---

## 8. Existing automated tests

### PHPUnit (`tests/`)

| Area | Files |
|------|-------|
| Auth (Breeze stock) | Authentication, Registration, Password*, EmailVerification |
| Security / payments | AppointmentSecurityFixes, SecurityCriticalFixes, CheckoutAmountFreeze, RazorpayWebhook, UpgradeRouteAuthorization |
| Unit | CanAccessRecordTest, ExampleTest |

**Risk:** `phpunit.xml` does **not** force in-memory SQLite (entries commented). Feature tests using `RefreshDatabase` can wipe **local** `database.sqlite`. Isolate before bulk PHPUnit runs.

### Playwright (`e2e/`)

| Asset | Path |
|-------|------|
| Config | `playwright.config.ts` (Chromium, Firefox, WebKit, mobile) |
| POM | `e2e/pages/*` |
| Fixtures | `e2e/fixtures/test.ts` |
| Suites | auth, admin, doctor/patient portals, appointments, ecommerce, security, responsive, public smoke |
| Prior interim | Chromium/WebKit smoke **58 passed** (2026-11-21); Firefox host-blocked |

**Re-run required** after local DB repair — do not reuse prior pass as current evidence.

---

## 9. Integrations (code present; credentials often blank locally)

Razorpay, Stripe, PayTM, PayPal, Paystack, Authorize.Net, Google Calendar OAuth, Zoom, AWS S3, DomPDF, Excel, Media Library, Impersonate, reCAPTCHA (optional).

---

## 10. Blockers before Phase 2+ execution

| ID | Blocker | Severity | Mitigation |
|----|---------|----------|------------|
| P1-B1 | Local SQLite has **0 users** / incomplete roles & permissions | **Blocker** | Backup sqlite → run documented local seed (`db:seed` / targeted seeders). Never touch production. |
| P1-B2 | Boot-time `migrate --force` in `AppServiceProvider` | High | Prefer remove/gate to CLI-only before further QA; document |
| P1-B3 | Payment sandbox keys blank | Medium | COD-only e-com; mark online payment blocked |
| P1-B4 | PHPUnit may wipe local SQLite | High | Dedicated test DB / enable memory sqlite in phpunit |
| P1-B5 | Firefox Playwright launch historically broken on this host | Low | Chromium first |
| P1-B6 | Production testing must be non-destructive | Policy | Prefer local; production = read-only smoke if needed |

---

## 11. Testing plan handed to later phases

1. **Repair local DB** (seed demo users/roles/permissions/services)  
2. **Phase 2** Admin CRUD matrix (create/read/update/delete, search, validation)  
3. **Phase 3** Doctor workflows + cross-doctor isolation  
4. **Phase 4** Patient workflows + cross-patient isolation  
5. **Phase 5** Auth + security (CSRF, IDOR, role URL direct access)  
6. **Phase 6** Expand Playwright (structure already exists)  
7. **Phase 7** UI/UX pass  
8. **Phase 8–10** Fixes, regression, final report  

Bug tracking file: `QA_BUG_TRACKER.md` (living document).

---

## 12. Phase 1 verdict

| Question | Answer |
|----------|--------|
| Stack / routes / roles understood? | **Yes** |
| Safe to claim panels work? | **No** — local auth data missing; runtime not re-executed this pass |
| Production Ready? | **Deferred** until Phases 2–10 evidence |
| Next action | Local DB seed/repair → Phase 2 Admin testing |

**Phase 1 status: COMPLETE (read-only audit).**
