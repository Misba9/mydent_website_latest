# MYDENT QA – Living Bug Tracker

**Updated:** 2026-11-23 (Production DB access incident)

---

## Production incidents

| ID | Severity | Finding | Status | Evidence |
|----|----------|---------|--------|----------|
| **QA-2026-020** | Blocker | Production MySQL user lacks privilege on configured DB; all public pages 500 | **OPEN / NOT FIXED** | Live `https://mydent.in/` → 500; Ignition title shows `SQLSTATE[HY000] [1044]` for user `u412681336_mydent` → DB `u412681336_mydent`; `APP_DEBUG` effectively **true** (full stack/SQL/paths exposed) |

### QA-2026-020 details — Production database access denied (2026-11-23)

- **Symptom:** Homepage / medical / public front routes fail with MySQL `1044 Access denied for user … to database …` at first Eloquent query.
- **Reported locus:** `FrontController::medical()` L38 (`Doctor::with(...)->get()`). Method logic is fine; it is only the first DB touch.
- **Exact root cause:** Production is authenticating as MySQL user **`u412681336_mydent`** against database **`u412681336_mydent`**. Error **1044** means auth succeeded but that user has **no GRANT** on that database. Repo production template / `config/database.php` fallback username is **`u412681336_ahmed`** (privileged user pattern). Local Mac `.env` active `DB_USERNAME` is incorrectly set to the **database name** (`u412681336_mydent`), matching the live error user — strongly consistent with production `.env` contamination or wrong `DB_USERNAME`.
- **Not the cause:** `medical()` query shape; missing tables; migrate needed. Do **not** run `migrate:fresh` / `db:wipe`.
- **Config cache:** Local has no `bootstrap/cache/config.php`. On Hostinger, a stale `config.php` can freeze wrong `DB_*`; after correcting `.env`, delete cache or `optimize:clear` then re-`config:cache`. Auto-unlink in `index.php` only removes Mac-path / missing-sqlite caches — **not** wrong MySQL username.
- **APP_DEBUG / leakage (verified live):** Production returns Ignition HTML (~1.1MB) with SQL text, stack traces, and `/home/...` paths on `/`, `/medical`, `/medical-about-us`, `/medical-services`, `/medical-doctors`, `/medical-contact`, `/faqs`, `/ecom`, `/login`. **`APP_DEBUG` is not false in production.**
- **Local vs production (no secrets printed):**
  | Item | Local (Mac `.env`) | Production (live error) | Expected prod |
  |------|--------------------|-------------------------|---------------|
  | `DB_CONNECTION` | `sqlite` | `mysql` (error Connection: mysql) | `mysql` |
  | Database | sqlite file path | `u412681336_mydent` | Hostinger DB name (same prefix family) |
  | Username | `u412681336_mydent` | `u412681336_mydent` | Privileged user (repo: `u412681336_ahmed`) |
  | Host | n/a (sqlite) | `127.0.0.1` | Hostinger MySQL host (usually `127.0.0.1`) |
  | `APP_DEBUG` | `true` (OK local) | **true (BAD)** | `false` |
- **Local read-only check:** App bootstraps `default=sqlite`; local config cache absent. Production MySQL is not safely reachable from this Mac (`DB_HOST=127.0.0.1` is server-local). **No production data modified.**
- **Runtime verification (2026-11-23):** All probed public URLs **500** — **NOT FIXED**.

#### Required Hostinger hPanel / server actions (authorization required — do not guess passwords)

1. **Databases → MySQL:** Confirm exact database name and which user(s) are assigned/privileged.
2. **Either:** grant user `u412681336_mydent` access to DB `u412681336_mydent`, **or** (preferred if `_ahmed` is the real app user) set production `.env` `DB_USERNAME` to the privileged user shown in hPanel.
3. Edit **`~/mydent_app_new/.env` only** (not Mac `.env` upload):
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://mydent.in`
   - `DB_CONNECTION=mysql`
   - `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` = **exact hPanel values**
4. Delete `~/mydent_app_new/bootstrap/cache/config.php` if present; then `php artisan optimize:clear` and `php artisan config:cache` (SSH) if available.
5. Optional: upload `deploy/hostinger-option-a/diag.php` temporarily, capture redacted output, **delete immediately**.
6. Re-verify pages return **200** without Ignition/SQL in HTML. **Do not mark FIXED until that passes.**

#### Files requiring modification

| Location | File | Change |
|----------|------|--------|
| Hostinger | `mydent_app_new/.env` | Correct `DB_USERNAME` (+ password if needed); force `APP_DEBUG=false` |
| Hostinger | `mydent_app_new/bootstrap/cache/config.php` | Delete if stale after `.env` fix |
| Repo (follow-up, not applied this incident) | `config/database.php` | Remove hardcoded prod username/password fallbacks (secret-in-repo risk) |
| Repo (follow-up) | Local `.env` | Keep sqlite for local; do not reuse DB-name-as-username for MySQL |
| Repo (follow-up) | `app/Exceptions/Handler.php` | JSON path still returns raw `$message` (can include SQLSTATE); harden when `APP_DEBUG=false` |
| Not required for this bug | `FrontController.php` | No functional change needed for 1044 |

#### Safety

- No `migrate:fresh` / `db:wipe` / table drops / credential rotation without owner authorization / auto-deploy performed.

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
| **QA-2026-020** | **Blocker** | **OPEN** — Production MySQL 1044; public site 500; APP_DEBUG leaking |
| QA-2026-010 | Medium | Admin delete/search/deep write matrix still thin |
| QA-2026-011 | Medium | Patient reschedule gap |
| QA-2026-012 | Low | Homepage appointment form smoke skipped |
| QA-2026-013 | Info | Firefox Playwright host issues |

## Panel status

| Area | Status |
|------|--------|
| **Production public site** | **FAIL** — 500 / MySQL 1044 (QA-2026-020); not fixed |
| Admin Panel | **PASS (Phase 2, local)** — Playwright 69/69 + HTTP CRUD |
| Doctor Panel | Smoke PASS (local) — Phase 3 deep next |
| Patient Panel | Smoke PASS (local) — Phase 4 deep next |
| Authentication | PASS (local QA accounts verified) |
| Security | **REGRESSION on prod** — APP_DEBUG/Ignition exposing SQL + paths |
| Responsive UI | PASS (smoke, local) |
| QA env / PHPUnit isolation | **PASS (Phase 1B)** |
