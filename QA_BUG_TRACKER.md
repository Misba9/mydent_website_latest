# MYDENT QA – Living Bug Tracker

**Updated:** 2026-11-22 (Phase 2 Admin E2E)

---

## Phase 1B infrastructure

| ID | Severity | Finding | Status | Evidence |
|----|----------|---------|--------|----------|
| **QA-2026-001** | Blocker | Local SQLite had 0 users / incomplete roles during Phase 1 | **FIXED** | Accounts restored/verified: admin/doctor/patient with roles + profiles; HTTP login → correct dashboards (200) |
| **QA-2026-002** | High | `AppServiceProvider` boot `migrate --force` | **FIXED** | Source has `NO_BOOT_MIGRATE`; `migrate:status` unchanged after HTTP boot |
| **QA-2026-003** | Medium | Razorpay sandbox keys blank | **OPEN / BLOCKED** | Online payment E2E still blocked; COD path works |
| **QA-2026-004** | High | PHPUnit could use file SQLite / RefreshDatabase wipe risk | **FIXED** | `phpunit.xml` forces `sqlite` + `:memory:`; `:memory:` no longer falls through to MySQL; isolation test passed; RefreshDatabase left file sqlite users=4 / mtime unchanged |

### QA-2026-001 details
- **Root cause:** Incomplete/partial local seed after schema work; `DefaultUserSeeder` is not idempotent (cannot safely re-run).
- **Files:** `database/seeders/LocalQaAccountsRepairSeeder.php` (new, local-only idempotent repair); ran existing permission/role seeders with `firstOrCreate`.
- **Commands:** `db:seed --class=DefaultPermissionSeeder|DefaultRoleSeeder|DefaultMedicinePermissionSeeder|DefaultHolidayPermissionSeeder|DefaultAssignPermissionSeeder|LocalQaAccountsRepairSeeder`
- **Verify:** password `123456` hashes OK; roles clinic_admin/doctor/patient; doctor profile id=1; patient profile id=1; logins redirect correctly.
- **Risk:** Do not re-run `DefaultUserSeeder` (creates duplicates).

### QA-2026-002 details
- **Root cause:** Convenience migrate-on-boot left in provider.
- **Files:** `app/Providers/AppServiceProvider.php`
- **Verify:** regex scan = `NO_BOOT_MIGRATE`; migrate status identical before/after `GET /`.
- **Risk:** None for boot path; migrations must remain CLI/deploy only.

### QA-2026-003 details
- **Status:** Still blocked pending sandbox keys in `e2e/.env` / local `.env`.
- **Risk:** Online payment regressions untested until keys provided.

### QA-2026-004 details
- **Root cause:** `DB_CONNECTION`/`DB_DATABASE` commented out in `phpunit.xml`; `config/database.php` treated `:memory:` as missing file and fell back to **mysql**.
- **Files:** `phpunit.xml`, `config/database.php`, `tests/Unit/PhpunitDatabaseIsolationTest.php`
- **Verify:** isolation unit tests 2/2 pass; file sqlite untouched during RefreshDatabase run.
- **Risk:** Stock Breeze `AuthenticationTest` still fails on schema mismatch (`users.name`) — isolated to memory, not a wipe risk.

---

## Other fixes (prior / related)

| ID | Status | Notes |
|----|--------|-------|
| QA-2026-005 | FIXED | Modal resource create 500s |
| QA-2026-006 | FIXED | Blog/product create route shadowing |
| QA-2026-007 | FIXED | GET `/logout` redirect |
| QA-2026-008 | FIXED | E2E product selectors |
| QA-2026-009 | FIXED | Playwright browser path |

## Fixed (console / Turbo)

| ID | Severity | Finding | Status |
|----|----------|---------|--------|
| QA-2026-014 | High | Turbo Drive re-injected `front-pages.js` after cart form → `turbo-frame` redefine, Stripe/FullCalendar double-load | **FIXED** — `data-turbo-eval="false"` on permanent scripts; CustomElementRegistry guard; `data-turbo="false"` on cart/checkout/subscribe forms; Stripe singleton |

## Phase 2 admin fixes

| ID | Severity | Finding | Status |
|----|----------|---------|--------|
| QA-2026-015 | High | FAQ index missing `$faqs` → empty UI | **FIXED** |
| QA-2026-016 | High | Livewire `showButtonOnHeader` never rendered in Rappasoft toolbar | **FIXED** |
| QA-2026-017 | High | Specialization Add btn id ≠ JS `#createSpecialization` | **FIXED** |
| QA-2026-018 | Blocker | Broken Mix `pages.js` ESM throws killed admin JS/`listenClick` | **FIXED** (restored bundle + `require()` sources) |
| QA-2026-019 | High | Missing add-button views → `/admin/holidays` 500 after toolbar wiring | **FIXED** |

## Open (non-1B)

| ID | Severity | Status |
|----|----------|--------|
| QA-2026-010 | Medium | Admin delete/search/deep write matrix still thin |
| QA-2026-011 | Medium | Patient reschedule gap |
| QA-2026-012 | Low | Homepage appointment form smoke skipped |
| QA-2026-013 | Info | Firefox Playwright host issues |

## Panel status

| Area | Status |
|------|--------|
| Admin Panel | **PASS (Phase 2)** — Playwright 69/69 + HTTP CRUD |
| Doctor Panel | Smoke PASS — Phase 3 deep next |
| Patient Panel | Smoke PASS — Phase 4 deep next |
| Authentication | PASS (local QA accounts verified) |
| Security | PASS (prior suite) |
| Responsive UI | PASS (smoke) |
| QA env / PHPUnit isolation | **PASS (Phase 1B)** |
