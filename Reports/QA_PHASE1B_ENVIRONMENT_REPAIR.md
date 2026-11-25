# MYDENT 2.0 – Phase 1B: Safe Local QA Environment Repair

**Date:** 2026-11-22  
**Mode:** Local SQLite repair only — **no production MySQL access**  
**Local app:** http://127.0.0.1:8000  
**Status:** **COMPLETE — ready for Phase 2 Admin E2E**

---

## 1. Database backup location

| Item | Value |
|------|--------|
| Method | `sqlite3 … ".backup"` (online-safe) |
| Path | `database/database.sqlite.backup-phase1b-20261122-091135` |
| Size | 421888 bytes |
| Verification | users **4=4**, tables **66=66** → `BACKUP_VERIFIED_OK` |
| Note | `php artisan serve` held an open FD; backup API still succeeded |

Earlier backup also present: `database/database.sqlite.backup-qa-20261122`

---

## 2. Database repair results

### Root cause (Phase 1 empty DB)
Local sqlite had been left in a partial state (migrations present, seed incomplete). `DefaultUserSeeder` always `User::create` (non-idempotent) — **not re-run**.

### Actions taken (safe / incremental)
1. Inspected seeders (`firstOrCreate` for permissions; role seeder; assign permissions).
2. Ran only necessary seeders (no `migrate:fresh`, no `db:wipe`, no file delete).
3. Added/ran idempotent `LocalQaAccountsRepairSeeder` (local/testing only).

### Post-repair inventory

| Metric | Count |
|--------|------:|
| users | 4 |
| doctors | 1 |
| patients | 2 |
| roles | 3 (`clinic_admin`, `doctor`, `patient`) |
| permissions | 20 |
| admin role → permissions | 20 |
| duplicate emails/roles/permissions | 0 |

| Account | Role | Password | Profile |
|---------|------|----------|---------|
| admin@mydent.in | clinic_admin (20 perms) | 123456 ✓ | — |
| doctor@mydent.in | doctor (4 perms) | 123456 ✓ | doctor_id=1 |
| patient@mydent.in | patient (3 perms) | 123456 ✓ | patient_id=1 |

---

## 3–5. Login verification

| Account | Redirect | Dashboard HTTP | Title |
|---------|----------|----------------|-------|
| Admin | `/admin/dashboard` | **200** | Dashboard \| MyDent Clinic |
| Doctor | `/doctors/dashboard` | **200** | Dashboard \| MyDent Clinic |
| Patient | `/patients/dashboard` | **200** | Dashboard \| MyDent Clinic |

---

## 6. PHPUnit isolation verification

### Changes
- `phpunit.xml`: force `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, dummy MySQL creds, `APP_ENV=testing`.
- `config/database.php`: **do not** treat `:memory:` as a missing file (previously fell back to mysql).
- New `tests/Unit/PhpunitDatabaseIsolationTest.php`.

### Evidence
```
PASS  Tests\Unit\PhpunitDatabaseIsolationTest
✓ phpunit uses in memory sqlite only
✓ refresh style schema does not change dev sqlite file

DEV_USERS_BEFORE=4 AFTER=4
DEV_MTIME unchanged
DEV_SQLITE_UNTOUCHED_OK

RefreshDatabase (AuthenticationTest run): file sqlite users/mtime unchanged
REFRESHDATABASE_SAFE_OK
```

Note: stock Breeze `AuthenticationTest` still fails on factory `users.name` vs app schema — failures occur on **:memory:** only and do not wipe local DB.

---

## 7. Automatic migration verification

| Check | Result |
|-------|--------|
| `AppServiceProvider` contains `Artisan::call(…migrate…)` | **NO_BOOT_MIGRATE** |
| `migrate:status` before vs after `GET /` | **Unchanged** |

Boot no longer runs migrations. Morph-type repair is opt-in via `FIX_SPATIE_MORPH_TYPES` in local/testing only.

---

## 8. Updated bug tracker

See `QA_BUG_TRACKER.md`:

| ID | Status |
|----|--------|
| QA-2026-001 Local DB | **FIXED** |
| QA-2026-002 Auto migrations | **FIXED** |
| QA-2026-003 Payment sandbox | **OPEN / BLOCKED** |
| QA-2026-004 PHPUnit isolation | **FIXED** |

---

## 9. Remaining blockers

| ID | Blocker | Impact on Phase 2 |
|----|---------|-------------------|
| QA-2026-003 | No Razorpay sandbox keys | Admin payment modules: COD/list OK; online payment E2E deferred |
| — | Stock auth PHPUnit factory mismatch | Does not block Admin E2E (Playwright + manual) |
| — | `config/database.php` MySQL defaults still present as fallbacks | Mitigated for tests; avoid pointing local `.env` at Hostinger |

---

## Files modified (Phase 1B)

- `app/Providers/AppServiceProvider.php` (boot migrate already removed; verified)
- `config/database.php` (`:memory:` safe default connection)
- `phpunit.xml` (forced in-memory sqlite)
- `tests/Unit/PhpunitDatabaseIsolationTest.php` (new)
- `database/seeders/LocalQaAccountsRepairSeeder.php` (new)
- `QA_BUG_TRACKER.md`
- This report

## Commands executed (summary)

```bash
sqlite3 database/database.sqlite ".backup 'database/database.sqlite.backup-phase1b-20261122-091135'"
php artisan db:seed --class=DefaultPermissionSeeder --force
php artisan db:seed --class=DefaultRoleSeeder --force
php artisan db:seed --class=DefaultMedicinePermissionSeeder --force
php artisan db:seed --class=DefaultHolidayPermissionSeeder --force
php artisan db:seed --class=DefaultAssignPermissionSeeder --force
php artisan db:seed --class=LocalQaAccountsRepairSeeder --force
php artisan test --filter=PhpunitDatabaseIsolationTest
# + HTTP login curls for three roles
```

---

## Phase 1B verdict

**Local QA environment is verified.**  
Safe to begin **Phase 2: Admin Panel end-to-end testing**.
