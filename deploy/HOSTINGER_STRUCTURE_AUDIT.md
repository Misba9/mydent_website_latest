# MyDent Hostinger Structure Audit & 500 Fix Plan

**Status:** Read-only audit complete. **No unverified production changes applied.**  
**Date:** 2026-11-22  
**Live probe:** `https://mydent.in/` → **500** (browser/WebFetch). Some CLI clients receive **403** (likely edge/WAF). Site is **not** healthy.

---

## 1. Complete deployment structure audit

### Target (correct Option A)

```text
/home/USERNAME/
├── mydent_app_new/          # private Laravel app + production .env
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── lang/
│   ├── resources/
│   ├── routes/
│   ├── storage/             # writable
│   ├── vendor/
│   ├── .env                 # PRODUCTION only (never from Mac)
│   ├── artisan
│   ├── composer.json
│   └── composer.lock
└── public_html/             # web root only
    ├── index.php            # Option A → ../mydent_app_new
    ├── .htaccess
    ├── assets/ backend/ css/ fonts/ images/ js/
    ├── uploads/ storage/ vendor/ web/
    ├── mix-manifest.json messages.js robots.txt favicon.ico
    └── (NO .env, NO app/, NO composer vendor PHP tree)
```

### Local packages audited

| Package | Source | Hostinger target |
|---------|--------|------------------|
| `deploy/packages/mydent_app_new_20261122.zip` | project `app/`, `bootstrap/`, `config/`, `database/`, `lang/`, `resources/`, `routes/`, `storage/`, `vendor/`, `artisan`, `composer.*` | `~/mydent_app_new/` |
| `deploy/packages/public_html_20261122.zip` | project `public/` + Option A `index.php` / `.htaccess` | `~/public_html/` |

### Laravel app package — completeness

| Item | Status |
|------|--------|
| `vendor/autoload.php` | Present |
| `bootstrap/app.php` | Present |
| `artisan`, `composer.json/lock` | Present |
| `app/helpers.php` | Present |
| Routes / resources / config | Present |
| PHP constraint | `^8.1` (Laravel 10) — Hostinger must run **PHP 8.1+** (prefer 8.2) |
| **`.env` in zip** | **Absent by design** |
| `bootstrap/cache/*.php` | Cleared (good — avoids Mac path cache) |
| Local SQLite DB | Excluded (good) |

### public_html package — completeness

| Item | Status |
|------|--------|
| Option A `index.php` (`$appBase = ../mydent_app_new`) | Correct in package |
| `.htaccess` rewrite + sensitive file block | Present |
| `css/front-pages.css`, `js/front-pages.js`, `mix-manifest.json` | Present |
| `.env` in public zip | Absent (good) |
| Composer `app/` / PHP `vendor/` tree | Absent (good) |

### public_html folder purpose

| Path | Required? | Notes |
|------|-----------|--------|
| `assets/` | Yes | Theme + front assets |
| `backend/` | Yes | Admin front-end assets |
| `css/`, `js/`, `fonts/`, `images/` | Yes | Compiled Mix + marketing images |
| `uploads/` | Yes | User/media uploads — **do not overwrite blindly** |
| `storage/` | Yes | Public media (`imgs`, `vids`, doctors) — keep live files |
| `vendor/` | Yes (Livewire public assets only) | **Not** Composer PHP vendor |
| `web/` | Yes | Metronic/media leftovers used by app |
| `messages.js` | Yes | JS localization |
| `mix-manifest.json` | Yes | Mix URL map |
| `index.php`, `.htaccess` | Yes | Entry + rewrite |
| `.env`, `app/`, `composer.json`, `artisan` | **Must NOT be here** | Private |

**Do not delete** old `mydent_app/`, `public_/`, or renamed `public_html_*` backups until the new site is stable 24h+.

---

## 2. Incorrect / risk items

1. **Missing production `.env` in `mydent_app_new/`** after zip extract (zip never includes it).  
   → Laravel cannot boot → **500**.
2. **Uploading Mac `.env`** (`APP_ENV=local`, `DB_CONNECTION=sqlite`, `APP_URL=http://127.0.0.1:8000`).  
   → Wrong DB / paths → **500**.
3. **Nested extract** (`public_html/public_html/` or `mydent_app_new/mydent_app_new/`).  
   → `index.php` cannot find `../mydent_app_new` → **500** or **403**.
4. **Wrong sibling folder name** (e.g. app lives in `mydent_app` but `index.php` expects `mydent_app_new`).
5. **`storage/` / `bootstrap/cache` not writable** → 500 on log/view/cache write.
6. **Stale `bootstrap/cache/config.php`** with `/Volumes/storage/` or sqlite (partially mitigated in Option A `index.php`).
7. **PHP &lt; 8.1** on Hostinger → fatal → 500.
8. **Google OAuth client JSON files** inside `storage/` in the app zip — not the 500 cause, but should not be world-readable (they are outside `public_html`, OK if app stays private).

---

## 3. Exact root cause of the 500 (most probable)

**Primary root cause (highest confidence without server log access):**

> `mydent_app_new` was extracted **without a valid Hostinger production `.env`**, **or** with a **local Mac `.env`**, so Laravel fails during bootstrap (missing/wrong `APP_KEY`, or sqlite/local DB). That produces HTTP **500**.

**Secondary causes to confirm on-server (same symptoms):**

- App folder path mismatch vs `public_html/index.php` (`../mydent_app_new`)
- Storage / cache permissions
- PHP version &lt; 8.1

**Evidence:**

- Deploy zip intentionally excludes `.env` (verified: 0 `.env` files in both zips).
- Local Mac `.env` is **not** production-safe (`APP_ENV=local`, `DB_CONNECTION=sqlite`).
- Live `https://mydent.in/` returns **500** via WebFetch.
- Option A `index.php` paths in the **package** are correct; failure is almost certainly **runtime env/path/permissions on the server**, not a bad entry-point file in the zip.

**Cannot claim a single confirmed line of `laravel.log` until** `diag.php` is run on Hostinger or the log is downloaded.

---

## 4. Files requiring modification (proposed only)

| File | Action | Safe? |
|------|--------|-------|
| `~/mydent_app_new/.env` | Ensure **production** copy exists (from old Hostinger app). Do **not** upload Mac `.env`. | Yes if copied from old prod |
| `~/public_html/index.php` | Confirm matches Option A (`$appBase = __DIR__.'/../mydent_app_new'`) | Yes |
| `~/public_html/.htaccess` | Confirm Option A rewrite + block rules | Yes |
| `~/mydent_app_new/bootstrap/cache/config.php` (if present) | Delete if contains Mac/sqlite paths | Yes |
| `storage` + `bootstrap/cache` permissions | `775` / writable | Yes |
| `public_html/diag.php` | Upload temporarily, then **delete** | Yes if deleted after |

**Do not modify:** production `APP_KEY`, DB credentials, customer tables, live `uploads/` / media.

---

## 5. Corrected deployment configuration

### `public_html/index.php` (already in package)

```php
$appBase = __DIR__.'/../mydent_app_new';
require $appBase.'/vendor/autoload.php';
$app = require_once $appBase.'/bootstrap/app.php';
```

### Production `.env` (values from **Hostinger**, not Mac)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mydent.in
APP_KEY=base64:...   # EXISTING production key — do not regenerate
DB_CONNECTION=mysql
DB_HOST=127.0.0.1    # or Hostinger’s MySQL host
DB_PORT=3306
DB_DATABASE=...      # existing
DB_USERNAME=...      # existing
DB_PASSWORD=...      # existing
CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=public
```

### Hostinger PHP

- Select **PHP 8.1 or 8.2** for the domain in hPanel.

---

## 6. Step-by-step Hostinger File Manager instructions

### A. Confirm layout (read-only)

1. Open File Manager → home (parent of `public_html`).
2. Confirm folders: `mydent_app_new`, `public_html`.
3. Inside `mydent_app_new`, confirm `vendor`, `bootstrap`, `app`, `artisan`.
4. Check whether `.env` exists in `mydent_app_new` (hidden files ON).
5. Open `public_html/index.php` → must contain `../mydent_app_new`.
6. Confirm you did **not** extract as `public_html/public_html/...`.

### B. Install temporary diagnostic (safe)

1. Upload `deploy/hostinger-option-a/diag.php` → `public_html/diag.php`.
2. Open `https://mydent.in/diag.php`.
3. Copy the full output (it redacts secrets).
4. **Delete `public_html/diag.php` immediately.**

### C. Fix based on diag (typical)

**If `.env` MISSING:**

1. Find production `.env` from old `mydent_app/.env` or broken upload backup.
2. Copy into `mydent_app_new/.env`.
3. Edit only if needed: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://mydent.in`.
4. Do **not** change `APP_KEY` or DB_* unless Hostinger panel proves they are wrong.
5. Do **not** paste Mac sqlite settings.

**If DB_CONNECTION=sqlite or APP_URL local:**

1. Restore production MySQL settings from Hostinger MySQL panel / old `.env`.
2. Keep existing `APP_KEY`.

**If storage not writable:**

1. Set `mydent_app_new/storage` and `mydent_app_new/bootstrap/cache` to writable (775).

**If config cache warns Mac/sqlite:**

1. Delete `mydent_app_new/bootstrap/cache/config.php`.

**If SSH available after env is correct:**

```bash
cd ~/mydent_app_new
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### D. Validate (private window)

1. `https://mydent.in/` → 200, redesign loads  
2. `/login`  
3. Admin dashboard  
4. Doctors / patients still present  
5. Products / cart / checkout page load  
6. Images from `/uploads` and `/storage`  
7. `https://mydent.in/.env` → denied  
8. Delete `diag.php` if still present  

---

## 7. Testing results (current)

| Check | Result |
|-------|--------|
| Live homepage | **FAIL — HTTP 500** |
| Live `.env` exposure via curl | Edge returns 403 (not proof of Laravel deny yet) |
| Local package entry point | PASS |
| Local package Mix assets | PASS |
| Local Mac server (dev) | PASS after Mix manifest fix |
| Production Laravel log | **Not yet readable** — needs File Manager / `diag.php` |
| Playwright / PHPUnit against live | **Not run** — site not healthy |

**Deployment success is NOT claimed.**

---

## 8. Rollback instructions

1. Rename failing `public_html` → `public_html_failed_YYYYMMDD`.
2. Restore previous working `public_html` from backup zip / `public_html_broken_*`.
3. Leave `mydent_app_new` in place for inspection (or rename aside).
4. **Database untouched** — no imports/resets in this plan.
5. Optional: point temporarily at old `/public/` layout only if that was the last known working access path.

---

## Next action required from you

1. Upload `deploy/hostinger-option-a/diag.php` to `public_html/`.
2. Open `https://mydent.in/diag.php` and paste the output here (secrets already redacted by the script).
3. After that, apply only the matching fix from section 6C — still without regenerating `APP_KEY` or touching the DB.
