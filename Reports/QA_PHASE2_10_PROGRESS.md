# MYDENT QA Progress Report (Phases 1–10)

**Date:** 2026-11-22  
**Environment:** Local `http://127.0.0.1:8000` (SQLite)  
**Production:** https://mydent.in — not mutated; no deploy  

---

## Executive summary

Phase 1 audit completed. Local DB was empty (blocker) and was reseeded for QA only. Admin/doctor/patient smoke paths verified via Playwright Chromium (**70 passed** after DB repair; ecommerce selectors fixed → **6/6**). Several route/bootstrap bugs fixed at root cause. Full deep CRUD write-matrix and production readiness still **in progress**.

---

## Phase status

| Phase | Status | Notes |
|-------|--------|-------|
| 1 Audit | **COMPLETE** | See `QA_COMPLETE_PHASE1_AUDIT.md` |
| 2 Admin | **PASS** | See `QA_PHASE2_ADMIN_E2E_REPORT.md` — Playwright admin 69/69 + HTTP CRUD PASS |
| 3 Doctor | **PARTIAL PASS** | Dashboard, appointments, calendar, sessions, visits, holidays; admin patients blocked |
| 4 Patient | **PARTIAL PASS** | Dashboard, appointments, create, visits, transactions; admin blocked |
| 5 Auth/Security | **PARTIAL PASS** | Login roles, CSRF, guest/upgrade blocks, PHPUnit security suite green |
| 6 Playwright | **COMPLETE (framework + smoke)** | Structure exists; Chromium smoke green; selectors fixed |
| 7 UI/UX | **PARTIAL** | GET `/logout` no longer Ignition 405 dump; deeper visual audit pending |
| 8 Bug fixing | **IN PROGRESS** | See bugs fixed below |
| 9 Regression | **PARTIAL** | Smoke + security PHPUnit re-run after fixes |
| 10 Final report | **THIS DOC (interim)** | Final PASS/FAIL only where executed |

---

## Test evidence (executed this pass)

### Playwright Chromium `@smoke`
- **70 passed**, **4 skipped** (then ecommerce unskipped after selector fix)
- After selector fix: ecommerce suite **6 passed**

### PHPUnit (selected security/payment)
- `SecurityCriticalFixesTest` — 9 passed  
- `CanAccessRecordTest` — 2 passed  
- `UpgradeRouteAuthorizationTest` — 12 passed  
- `AppointmentSecurityFixesTest` — 5 passed  
- `CheckoutAmountFreezeTest` — 1 passed  
- `RazorpayWebhookTest` — 3 passed  

### Admin HTTP matrix (authenticated)
- Most admin index/create pages **200**
- Fixed: blogs/products create route shadowing; modal resource create 500s

---

## Bugs identified & fixed this engagement

| ID | Severity | Finding | Fix |
|----|----------|---------|-----|
| QA-2026-001 | Blocker | Local SQLite 0 users / incomplete roles | Seeded local demo users/roles/permissions (backup: `database.sqlite.backup-qa-20261122`) |
| QA-2026-002 | High | Boot-time `migrate --force` | Removed; morph repair opt-in via `FIX_SPATIE_MORPH_TYPES` |
| QA-2026-005 | High | Modal resources registered `create` without controller method → **500** | `->except(['create','show'])` on specializations, countries, states, cities, currencies, service-categories, categories, clinic-schedules |
| QA-2026-006 | High | `/blogs/create` & `/products/create` shadowed by `{id}` show → **404** | Reordered routes: create/edit before show |
| QA-2026-007 | Medium | GET `/logout` showed MethodNotAllowed Ignition page | GET `/logout` → redirect login |
| QA-2026-008 | Medium | Playwright ecommerce skipped — stale `a.product-link` selector | Updated to `a[href*="/product/"]` + listing cart form |
| QA-2026-009 | Low | Playwright browsers missing in sandbox cache | Install to `~/Library/Caches/ms-playwright`; config default path |

---

## Remaining issues

| ID | Severity | Status |
|----|----------|--------|
| QA-2026-003 | Medium | Online Razorpay E2E blocked (no sandbox keys) |
| QA-2026-004 | High | **FIXED in 1B** — PHPUnit forced `:memory:` isolation |
| QA-2026-010 | Medium | Partially addressed (patient/FAQ/spec/product); delete/search matrix still thin |
| QA-2026-011 | Medium | Patient self-service reschedule still a product gap (not a crash) |
| QA-2026-012 | Low | Homepage appointment form smoke skipped (form absent after redesign) |
| QA-2026-013 | Info | Firefox Playwright historically env-blocked on this host |

---

## Phase 2 admin evidence (2026-11-22)

- Playwright Chromium `e2e/tests/admin/`: **69 passed**
- `php scripts/qa_admin_crud_http.php`: login + specialization + FAQ + patient + module matrix **PASS**
- Fixes: FAQ `$faqs`, Livewire toolbar add buttons, specialization button id, restored broken `pages.js`, missing holiday/FAQ add-button views

## Panel status (evidence-based)

| Area | Status |
|------|--------|
| Admin Panel | **PASS** (smoke / list / create forms) — deep CRUD TBD |
| Doctor Panel | **PASS** (smoke) — clinical write paths TBD |
| Patient Panel | **PASS** (smoke) — booking write + cancel TBD |
| Authentication | **PASS** (login roles + invalid + CSRF) |
| Security | **PASS** (executed PHPUnit + access smoke) — full IDOR matrix TBD |
| Responsive UI | **PASS** (smoke viewports) |

**Overall Production Ready:** **Not yet** — pending deep CRUD, payment sandbox, PHPUnit DB isolation, and production non-destructive smoke.

---

## Files modified

- `routes/web.php` — blog/product order; modal resource excepts  
- `routes/auth.php` — GET logout redirect  
- `app/Providers/AppServiceProvider.php` — remove boot migrate  
- `e2e/tests/ecommerce/cart-cod.spec.ts` — selectors  
- `e2e/tests/ecommerce/cod-checkout.spec.ts` — selectors  
- `playwright.config.ts` — browsers path default  
- Docs: `QA_COMPLETE_PHASE1_AUDIT.md`, `QA_BUG_TRACKER.md`, this file  

## Database changes

- Local SQLite only: seed demo admin/doctor/patient + permissions; QA patient row via tinker  
- **No production DB changes**

## Deployment recommendations

1. Do **not** enable boot-time migrate in any environment  
2. Ensure Hostinger deploy uses `deploy.sh` / artisan CLI for migrate+seed  
3. Confirm production has `patient` role + full permission set  
4. Rotate any accounts that ever had migration-forced passwords historically  
5. Provide Razorpay sandbox keys for online payment E2E before go-live sign-off  
