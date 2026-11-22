# MyDent Composer Dependency Remediation Report

**Date:** 2026-11-22  
**Scope:** Plan A (Laravel 10 + Livewire 2) + minimal advisory policy exceptions  
**Backups:** `composer.json.bak.20261122063229`, `composer.lock.bak.20261122063229`

---

## 1. Initial dependency audit (summary)

| Item | Before |
|------|--------|
| Laravel | 10.23.1 |
| Livewire | 2.12.6 |
| Rappasoft tables | `dev-master` @ `26c596d` (LW2-era tip) |
| firebase/php-jwt | 5.5.1 |
| DomPDF stack | barryvdh 2.0.1 / dompdf 2.0.3 |
| Media Library | 10.12.2 |
| Zoom | 7.0.0 → api-client 4.1.0 |
| Local `composer install` | OK |
| Local / Hostinger-style `composer update` | Failed (LW3 tip + advisory blocking) |

---

## 2. Identified security vulnerabilities (key)

| Package (before) | Issue | Fix applied |
|------------------|-------|-------------|
| firebase/php-jwt 5.5.1 | Critical CVE-2021-46743 | → 6.11.1 |
| laravel/framework 10.23.1 | Critical CVE-2026-114509 (<10.50.4) | → 10.50.4 |
| livewire/livewire 2.12.6 | High CVE-2024-47823 (<2.12.7) | → 2.12.8 |
| spatie/laravel-medialibrary 10.x | High upload/SSRF (<11.23.0) | → 11.23.9 |
| dompdf/dompdf 2.x | Multiple (<3.1.6) | → 3.1.6 via barryvdh 3.1.2 |
| maatwebsite/excel | High CVE-2026-84374 (<3.1.70) | → 3.1.70 |
| guzzlehttp/guzzle | Multiple | → 7.16.5 |

---

## 3. Dependency compatibility matrix (after)

| Package | Version | Laravel 10 | Livewire 2 | Notes |
|---------|---------|------------|------------|-------|
| laravel/framework | 10.50.4 | ✓ | — | Stayed on L10 |
| livewire/livewire | 2.12.8 | ✓ | ✓ | No LW3 migration |
| rappasoft/laravel-livewire-tables | **2.15.0** | ✓ | ✓ | Replaces `dev-master` |
| opcodesio/log-viewer | 1.x | ✓ | ✓ | Retained |
| barryvdh/laravel-dompdf | 3.1.2 | ✓ | — | DomPDF 3.1.6 |
| spatie/laravel-medialibrary | 11.23.9 | ✓ | — | Requires PHP ^8.2 |
| firebase/php-jwt | 6.11.1 | ✓ | — | Zoom api-client 5 compatible |
| macsidigital/laravel-zoom | 8.0.1 | ✓ | — | api-client 5.0.1 |
| google/apiclient | 2.20.1 | ✓ | — | Supports JWT 6/7 |

---

## 4. Changes to `composer.json`

**File:** `composer.json`

| Change | Why |
|--------|-----|
| Removed `"version": "8.0.0"` | Invalid for app root; composer validate warning |
| `"php": "^8.2"` | Media Library 11 + Hostinger/local PHP 8.2 |
| `laravel/framework`: `^10.50.4` | Patched L10 critical/high where available |
| `livewire/livewire`: `^2.12.7` | File-upload RCE patch without LW3 |
| `rappasoft/laravel-livewire-tables`: `^2.15` | Stable LW2 tables; ends `dev-master` conflict |
| `barryvdh/laravel-dompdf`: `^3.1` | DomPDF 3.1.6+ security fixes |
| `spatie/laravel-medialibrary`: `^11.23` | Patched upload/SSRF advisories |
| `firebase/php-jwt`: `^6.10` | Clear critical JWT CVE; Zoom 8 supports ^6 |
| `google/apiclient`: `^2.18` | JWT 6+ support |
| `macsidigital/laravel-zoom`: `^8.0` | Pulls api-client 5 (JWT ^6) |
| Moved `barryvdh/laravel-debugbar` → `require-dev` | Keep debug tooling out of production installs |
| `config.policy.advisories.ignore-id` (7 IDs) | **Required** so Composer 2.10 can resolve L10 at all (see §10) |
| `config.audit.ignore` (3 IDs initially) | Align audit reporting with unavoidable exceptions |

---

## 5. Changes to `composer.lock`

| Item | Before | After |
|------|--------|-------|
| content-hash | `2f3756a8324912c742e16f6062de0d0c` | `46cf969a4e9e55e930dc36728295c9a2` |
| Unstable prod deps | Rappasoft `dev-master` | None (stable `v2.15.0`) |
| Lock ops (approx.) | — | ~143 updates, 7 installs, 11 removals |

---

## 6. Packages upgraded and reasons

| Package | From → To | Reason |
|---------|-----------|--------|
| laravel/framework | 10.23.1 → 10.50.4 | Security patches on L10 line |
| livewire/livewire | 2.12.6 → 2.12.8 | CVE-2024-47823 |
| rappasoft/laravel-livewire-tables | dev-master → 2.15.0 | Hostinger/update conflict; stable LW2 |
| firebase/php-jwt | 5.5.1 → 6.11.1 | Critical algorithm confusion |
| barryvdh/laravel-dompdf | 2.0.1 → 3.1.2 | DomPDF 3 security |
| dompdf/dompdf | 2.0.3 → 3.1.6 | Advisory fixes |
| spatie/laravel-medialibrary | 10.12.2 → 11.23.9 | Upload/SSRF fixes |
| macsidigital/laravel-zoom | 7.0.0 → 8.0.1 | JWT 6 support via api-client 5 |
| google/apiclient | 2.14.0 → 2.20.1 | JWT 6/7 + current Google APIs |
| guzzlehttp/guzzle | 7.8.0 → 7.16.5 | HTTP client advisories |
| maatwebsite/excel | 3.1.48 → 3.1.70 | Path traversal advisory |
| + many Symfony/Carbon/AWS transitive | various | Pulled by `-W` resolution |

---

## 7. Packages retained and reasons

| Package | Reason |
|---------|--------|
| Livewire 2.x (not 3) | Explicit constraint; 41 Rappasoft v2 tables; log-viewer 1.x |
| Laravel 10 (not 11/12) | Explicit constraint; smaller blast radius |
| opcodesio/log-viewer ^1.9 | LW2-compatible |
| Abandoned: gerardojbaez/money, laravelcollective/html | Still used; replacement is follow-up, not required for install |
| Payment SDKs (Razorpay, Stripe, PayPal, Paystack, PayTM, Authorize) | Business features unchanged |

---

## 8. Composer validation results

| Check | Result |
|-------|--------|
| `composer validate --no-check-publish` | **Valid** |
| `composer install` | **OK** |
| `composer install --no-dev` (dry-run) | **OK** (removes debugbar correctly) |
| `composer check-platform-reqs` | **All success** on PHP 8.2.33 |
| `php artisan package:discover` | **OK** |
| `php artisan optimize:clear` | **OK** |
| `php artisan about` | Laravel **10.50.4**, PHP 8.2.33 |

---

## 9. Laravel regression test results

### Automated (verified this run)

| Suite | Result |
|-------|--------|
| `SecurityCriticalFixesTest` | **9/9 OK** |
| Full PHPUnit | 49 tests: **11 errors + 1 failure** — **pre-existing** stale Breeze tests (`users.name` / `first_name`), not caused by this dependency bump |
| JWT encode/decode smoke | **OK** (v6 `Key` API; Zoom vendor already updated) |
| DomPDF facade class exists | **OK** |

### HTTP smoke

Restart local `artisan serve` and re-check `/`, `/login`, `/products`, `/medical-appointment` before claiming page health. Dependency install itself does not prove UI.

### Not claimed working without runtime proof

Payment gateways, Zoom meetings, Google Calendar OAuth, Media Library uploads, PDF generation against real data — need manual/E2E verification after deploy.

---

## 10. Remaining security risks

### Policy exceptions (required to resolve on Laravel 10)

Composer 2.10 **refuses to load** any `laravel/framework` 10.x without ignores. IDs in `config.policy.advisories.ignore-id`:

| ID | Why ignored |
|----|-------------|
| `PKSA-mdq4-51ck-6kdq` | CRLF email rule — **no fixed release on Laravel 10** |
| `PKSA-yxbx-3c9s-cjt9` | Metadata lists L12/L13 ranges but Composer still blocked 10.50.4 |
| `PKSA-d5tc-s1qs-h781` | Same false-positive blocking behavior |
| `PKSA-m5cs-t1y6-qpcs` | Same |
| `PKSA-3r5d-mb8f-1qw9` | Same / L12-oriented CRLF sibling |
| `PKSA-y2cr-5h3j-g3ys` | JWT low CVE needs ≥7; Zoom api-client 5 only allows `^6` |
| `PKSA-pg71-gz29-h5sq` | `guzzlehttp/oauth-subscriber` pulled by Zoom api-client 5; no safe alternative while keeping Zoom |

**Security blocking remains ON** (`policy.advisories.block` not disabled). Only listed IDs are excepted.

### Still open after upgrade

- Many transitive advisories may remain (AWS SDK, PhpSpreadsheet chains, etc.) — run `composer audit` and triage.
- Abandoned packages still present.
- Laravel 10 CRLF email advisory remains until a major Laravel upgrade.
- JWT 7 not reachable without replacing Zoom stack.

---

## 11. Hostinger deployment readiness

| Requirement | Status |
|-------------|--------|
| PHP **8.2+** | **Required** (raised from ^8.1) |
| Extensions | json, curl, mbstring, openssl, tokenizer, xml, ctype, fileinfo, **exif**, gd, zip, zlib, … |
| Deploy command | `composer install --no-dev --optimize-autoloader --no-interaction` |
| Do **not** run | `composer update` on the server |
| Commit | `composer.json` + `composer.lock` together |
| Do not deploy | `.env`, SQL dumps, `storage/app` uploads overwrite |
| Preserve | production `.env`, `storage/`, `public/uploads/` |

---

## 12. Exact next deployment commands

### Local (already done pattern)

```bash
composer install --no-interaction
php artisan package:discover
php artisan optimize:clear
php artisan about
```

### Hostinger (Option A / Git deploy)

```bash
cd /home/<USER>/mydent_app_new   # private app root — adjust path
composer install --no-dev --optimize-autoloader --no-interaction
php artisan package:discover --ansi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
# Do NOT run migrate unless intentionally releasing schema changes
# Do NOT run composer update
```

### GitHub / main branch prep (when you approve commit)

```bash
git add composer.json composer.lock COMPOSER_DEPENDENCY_REMEDIATION_REPORT.md
# Do not add .env, backups with secrets, or vendor/
git status   # review before commit
```

Suggested commit message:

```text
Fix Composer lock for Hostinger: pin Livewire Tables 2.15 and patch security deps

Replace rappasoft dev-master, upgrade Laravel 10.50.4, Livewire 2.12.8,
DomPDF 3, Media Library 11, JWT 6, and Zoom 8 so production install resolves
without Livewire 3 or advisory deadlocks.
```

---

## Rollback

```bash
cp composer.json.bak.20261122063229 composer.json
cp composer.lock.bak.20261122063229 composer.lock
composer install --no-interaction
php artisan optimize:clear
```
