# MyDent QA Interim Report

**Date:** 2026-11-21  
**Scope completed:** Phase 1 (inspection) + Phase 2 (Playwright framework) + Phase 3 smoke expansion  
**Environment:** Local `http://127.0.0.1:8000` (SQLite)

---

## Production readiness (current)

### **Not Production Ready**

Rationale: Payment sandbox (Razorpay) still blocked; Phase 4–10 (API depth, security IDOR matrix, DB integrity, notifications, final sign-off) not executed. Phase 3 smoke is green on Chromium + WebKit. Firefox blocked by local browser-launch environment (not an app defect).

---

## Phase 1 summary

See [`QA_PHASE1_ARCHITECTURE_INVENTORY.md`](QA_PHASE1_ARCHITECTURE_INVENTORY.md).

| Item | Finding |
|------|---------|
| Stack | Laravel 10, PHP 8.2, Livewire 2, Blade, Mix |
| Routes | ~330 web, **1** API (`GET /api/user`) |
| Mobile source | **Not in repo** |
| Payments | Razorpay/COD e-com; many appointment gateways; keys blank locally |
| Prior PHPUnit | Security/payment tests present; stock Breeze tests likely stale |

### Blockers

| ID | Status |
|----|--------|
| B1 Payment sandbox keys | **Open** — online payment E2E blocked |
| B3 Thin API | **Open** — Phase 4 limited |
| B4 No mobile source | **Open** — out of scope |
| B7 reCAPTCHA | Monitor on registration/enquiry |
| B8 Firefox Playwright launch | **Open (env)** — `browserType.launch` fails with “Could not find profile folder” on this host (darwin 27); Chromium/WebKit OK |

---

## Phase 2 framework (delivered)

| Asset | Path |
|-------|------|
| Config | `playwright.config.ts` (Chromium, Firefox, WebKit, Mobile Chrome, Mobile Safari) |
| Env | `e2e/.env.example` / `e2e/.env` (gitignored) |
| POM | `e2e/pages/*` |
| Fixtures | `e2e/fixtures/test.ts` |
| Artifacts | screenshots / video / trace on failure; HTML + JSON reporters |
| webServer | Auto `php artisan serve` with reuse |

Commands:

```bash
npm run test:e2e:smoke -- --project=chromium
npm run test:e2e:smoke -- --project=webkit
npm run test:e2e:report
```

---

## Phase 3 smoke execution — evidence

### Chromium — **58 passed** (2026-11-21)

| Suite | Result |
|-------|--------|
| Public pages (9) + nav + footer | Passed |
| Auth (6) | Passed — admin/doctor/patient login; guest blocked from admin |
| Cart / checkout smoke (4) | Passed — including add-to-cart |
| COD checkout E2E (2) | Passed — product → cart → COD order success; empty-field validation |
| Appointment smoke (2) | Passed |
| Admin dashboard + CRUD entry points (13) | Passed — patients, appointments, doctors, services, FAQs, products, orders, settings, banners, videos; create patient/appointment forms |
| Doctor portal (7) | Passed — dashboard, appointments, calendar, sessions, visits, holidays; admin patients blocked |
| Patient portal (5) | Passed — dashboard, appointments, create, visits, transactions |
| Security access (4) | Passed — CSRF, `/api/user` 302, upgrade guest block, patient≠admin |
| Responsive home (4 viewports) | Passed |

### WebKit — **58 passed** (2026-11-21)

Same `@smoke` matrix as Chromium — all green.

### Firefox — **blocked (environment)**

All UI tests fail at `browserType.launch` (`Could not find profile folder` / prior `sandbox_extension_issue_file_to_process`). Only request-only API check can pass without launching Firefox. **Not counted as application failures.** Re-run after Firefox/Playwright host fix:

```bash
PLAYWRIGHT_BROWSERS_PATH="$HOME/Library/Caches/ms-playwright" npx playwright install firefox
npx playwright test --project=firefox --grep @smoke
```

**Artifacts:** `e2e/playwright-report/`, `e2e/test-results/`

**Fixes applied during Phase 3:**
- COD checkout locators scoped to `#checkout-form` (footer newsletter also has `input[name="email"]` — strict-mode violation).
- Earlier: cart selector, homepage `domcontentloaded`, `/api/user` `maxRedirects:0`.

---

## Bugs / findings so far

| ID | Severity | Finding | Status |
|----|----------|---------|--------|
| QA-001 | Low | Playwright request follows redirects; `/api/user` appeared as 200 until `maxRedirects:0` (actual 302) | Test fixed; API correctly redirects |
| QA-002 | Low | Homepage `waitUntil: load` times out under video-heavy home | Tests use `domcontentloaded` |
| QA-003 | Info | Online Razorpay checkout not exercised | **Blocked** (B1) |
| QA-004 | Info | Mobile app / rich API absent | Documented Phase 1 |
| QA-005 | Low | Checkout + footer both expose `name="email"` — breaks unscoped Playwright fills | Test scoped to `#checkout-form` |
| QA-006 | Info | Firefox cannot launch under Playwright on this macOS host | Env blocker B8 |

No Critical application defects confirmed in Chromium/WebKit smoke. Deeper CRUD write paths, payment sandbox, and IDOR suites still pending.

---

## Remaining work (ordered)

1. Unblock Firefox launch (B8) when host allows; optional Mobile Chrome/Safari projects  
2. Phase 4 API (minimal) + Phase 5 security suite expansion  
3. Phase 8 DB checks via artisan/tinker or dedicated test DB  
4. Phase 10 final report with production readiness re-evaluation  
5. Provide Razorpay sandbox keys to lift B1 for online payment E2E  

---

## How to reproduce smoke

```bash
cp e2e/.env.example e2e/.env   # if needed
php artisan serve --host=127.0.0.1 --port=8000
npm run test:e2e:smoke -- --project=chromium
npm run test:e2e:smoke -- --project=webkit
```
