# MyDent → Hostinger Deploy Guide

Use this to publish the redesigned site on Hostinger **without wiping the database** or regenerating `APP_KEY`.

Packages are built by:

```bash
bash deploy/build-hostinger-packages.sh
```

Output lives in `deploy/packages/`:

| File | Upload / extract to |
|------|---------------------|
| `mydent_app_new_YYYYMMDD.zip` | `/home/USERNAME/` → becomes `mydent_app_new/` |
| `public_html_YYYYMMDD.zip` | `/home/USERNAME/` → becomes `public_html/` |
| Option A `index.php` / `.htaccess` | already inside the public zip |

---

## Hard rules

1. **Never upload** your local Mac `.env` over production.
2. **Never run** `php artisan key:generate` on Hostinger.
3. **Never import** a local SQLite/SQL dump unless you intentionally want to replace live data.
4. Keep old folders until the new site works: `mydent_app/`, `public_/`, renamed broken `public_html_*`.

---

## Path A — First-time Option A cutover (site still 403 / needs `/public/`)

Follow `deploy/hostinger-option-a/OPTION_A_RUNBOOK.md` in full.

Short version:

1. Backup + rename current `public_html`.
2. Extract `mydent_app_new_*.zip` in the home directory.
3. Extract `public_html_*.zip` in the home directory (creates clean web root).
4. Copy **production** `.env` into `mydent_app_new/.env` (from the old broken upload or from `mydent_app/.env` — not from your laptop).
5. Confirm `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://mydent.in`, `APP_KEY` unchanged, MySQL credentials match Hostinger.
6. Clear caches / set storage writable (SSH or File Manager — see runbook).
7. Smoke-test homepage, login, products, cart, checkout.

---

## Path B — Site already on Option A (refresh redesign only)

If `mydent_app_new` + clean `public_html` already exist:

1. Backup current `mydent_app_new` and `public_html` (compress in File Manager).
2. Upload/extract **app zip** over `mydent_app_new/` **but skip**:
   - `.env`
   - `storage/app` uploaded media (merge carefully)
3. Upload/extract **public zip** over `public_html/` **but keep**:
   - existing `uploads/` and live `storage/` media if newer on server
4. Do **not** replace production `.env`.
5. Clear Laravel caches:

```bash
cd ~/mydent_app_new
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## After upload checklist

Open in a private window:

- [ ] `https://mydent.in/` → 200 (not 403)
- [ ] Logo + coral/blue redesign visible
- [ ] Services / Products / Cart / Checkout load
- [ ] `https://mydent.in/login` loads (Mix `pages.css` present)
- [ ] Admin can log in; patients/appointments still in DB
- [ ] `https://mydent.in/.env` denied
- [ ] `https://mydent.in/public/` not required

---

## Rollback

1. Rename failing `public_html` away.
2. Restore previous `public_html` zip/folder.
3. Restore previous `mydent_app_new` if needed.
4. Database untouched.
