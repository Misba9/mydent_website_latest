# MyDent Option A – Hostinger Cutover Runbook

**Goal:** Fix 403 by restoring the correct Laravel layout without touching MySQL data.

**Do not delete:** `mydent_app/`, `public_/`  
**Do not overwrite:** production `.env` with local `.env`  
**Do not run:** `php artisan key:generate`  
**Do not import:** SQL dumps unless explicitly approved later

---

## Target layout

```text
/home/USERNAME/
├── mydent_app/                 # old app – KEEP
├── public_/                    # old public – KEEP
├── public_html_broken_YYYYMMDD # renamed broken upload – KEEP until validated
├── mydent_app_new/             # Laravel app (outside web root)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── lang/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env                    # production env ONLY
│   ├── artisan
│   ├── composer.json
│   └── composer.lock
└── public_html/                # web root ONLY
    ├── index.php               # from deploy/hostinger-option-a/index.php
    ├── .htaccess               # from deploy/hostinger-option-a/.htaccess
    ├── assets/
    ├── backend/
    ├── css/
    ├── fonts/
    ├── images/
    ├── js/
    ├── uploads/
    ├── storage/                # public storage link/files
    ├── vendor/                 # public front vendor assets only
    ├── web/
    ├── favicon.ico
    ├── messages.js
    ├── mix-manifest.json
    └── robots.txt
```

---

## Phase 0 – Backup (required)

In Hostinger **File Manager**, open the home directory that contains `public_html`.

1. Select `public_html` → **Compress** → save as `public_html_broken_backup.zip` in the home folder.
2. Rename folder `public_html` → `public_html_broken_20261122`  
   (use today’s date if different).
3. Confirm `mydent_app` and `public_` still exist and were not renamed.

Site will stay offline / 403 until Phase 3 finishes. That is expected.

---

## Phase 1 – Create `mydent_app_new` from the broken upload

Still in File Manager (home directory):

1. Create folder: `mydent_app_new`
2. From `public_html_broken_20261122`, **Move** these into `mydent_app_new/`:

   - `app`
   - `bootstrap`
   - `config`
   - `database`
   - `lang`
   - `resources`
   - `routes`
   - `storage`
   - `vendor`
   - `.env`          ← production env – must move here
   - `artisan`
   - `composer.json`
   - `composer.lock`
   - `package.json` / `package-lock.json` (optional)
   - `webpack.mix.js` / `tailwind.config.js` (optional)
   - `deploy.sh` (optional)

3. **Do not move into `mydent_app_new`:**

   - `public` (stays for next phase)
   - `node_modules`
   - `e2e`
   - `tests`
   - `*.sql`
   - `*.zip`
   - audit / QA `*.md` files
   - local `.env` from your Mac

4. Open `mydent_app_new/.env` and verify (do not paste secrets into chat):

   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://mydent.in`
   - `APP_KEY=` is present and unchanged
   - `DB_CONNECTION=mysql`
   - DB name/user match Hostinger MySQL

---

## Phase 2 – Create clean `public_html`

1. Create a new empty folder: `public_html`
2. From `public_html_broken_20261122/public/`, **Move/Copy** all contents into `public_html/`:

   - `assets`, `backend`, `css`, `fonts`, `images`, `js`
   - `uploads`, `storage`, `vendor`, `web`
   - `messages.js`, `mix-manifest.json`, `robots.txt`, `favicon.ico` (if present)
   - existing `index.php` and `.htaccess` from that `public/` folder (will be replaced next)

3. Upload these local files over the ones in `public_html/`:

   - `deploy/hostinger-option-a/index.php` → `public_html/index.php`
   - `deploy/hostinger-option-a/.htaccess` → `public_html/.htaccess`

4. Confirm `public_html/index.php` contains:

   ```php
   $appBase = __DIR__.'/../mydent_app_new';
   ```

5. Confirm there is **no** `public_html/.env`, `public_html/app`, `public_html/vendor` (PHP Composer vendor), or `public_html/database`.

---

## Phase 3 – Permissions / storage / caches

### If SSH is available

```bash
cd ~/mydent_app_new

# writable paths only (not recursive chmod 777 on whole tree)
chmod -R ug+rwx storage bootstrap/cache

php artisan storage:link || true
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up || true
```

If `storage:link` fails on shared hosting, manually ensure `public_html/storage` points at (or contains) media used by the site. You can copy/symlink:

```bash
# only if symlink is missing and SSH allows it
ln -s ~/mydent_app_new/storage/app/public ~/public_html/storage
```

### If File Manager only

1. Ensure `mydent_app_new/storage` and `mydent_app_new/bootstrap/cache` are writable (permissions `775` or Hostinger “writable”).
2. Delete these cache files if present (they may contain Mac/local paths):

   - `mydent_app_new/bootstrap/cache/config.php`
   - `mydent_app_new/bootstrap/cache/routes-v7.php`
   - `mydent_app_new/bootstrap/cache/services.php` (optional; regenerates)

3. Keep uploaded media under `public_html/uploads` and `public_html/storage` as already moved.

---

## Phase 4 – Immediate validation

Open in a private browser window:

1. `https://mydent.in/` → must be **200**, not 403
2. `https://mydent.in/login` → login page
3. `https://mydent.in/public/` → should ideally 404 or redirect; should **not** be required
4. These must **not** be publicly readable:

   - `https://mydent.in/.env`
   - `https://mydent.in/composer.json`
   - `https://mydent.in/artisan`
   - `https://mydent.in/storage/logs/laravel.log`
   - `https://mydent.in/database/database.sqlite`
   - `https://mydent.in/MYDENT_SECURITY_AUDIT.md`

5. Functional smoke:

   - Homepage CSS/JS/images
   - Admin login
   - One patient record visible
   - One appointment visible
   - Product page
   - Cart / checkout page loads (do not place a real paid order unless intended)

---

## Phase 5 – Cleanup (only after site works 24h)

Still do **not** delete old folders yet unless you are sure.

Safe cleanup candidates inside leftovers (not in active `mydent_app_new` / `public_html`):

- `node_modules`, `e2e`, `tests`
- SQL dumps, large zips
- audit markdown reports

Keep until confirmed:

- `mydent_app/`
- `public_/`
- `public_html_broken_20261122/`
- `public_html_broken_backup.zip`

---

## Rollback (if homepage still broken)

1. Rename current `public_html` → `public_html_failed_attempt`
2. Rename `public_html_broken_20261122` back to `public_html`  
   **or** restore from `public_html_broken_backup.zip`
3. Leave `mydent_app_new` in place for inspection; do not delete
4. Database remains unchanged

Optional temporary access while planning retry: open `https://mydent.in/public/` if that layout is restored.

---

## Local files prepared for upload

Preferred (full redesign package):

```bash
bash deploy/build-hostinger-packages.sh
```

| Local path | Upload to |
|------------|-----------|
| `deploy/packages/mydent_app_new_YYYYMMDD.zip` | extract in `/home/USERNAME/` → `mydent_app_new/` |
| `deploy/packages/public_html_YYYYMMDD.zip` | extract in `/home/USERNAME/` → `public_html/` |
| `deploy/HOSTINGER_DEPLOY.md` | reference (Path A / Path B) |

Manual Option A bootstrap only:

| Local path | Upload to |
|------------|-----------|
| `deploy/hostinger-option-a/index.php` | `public_html/index.php` |
| `deploy/hostinger-option-a/.htaccess` | `public_html/.htaccess` |
| `deploy/hostinger-option-a/OPTION_A_RUNBOOK.md` | reference only |

**Reminder:** packages never include `.env`. Keep production `.env` on the server only.
