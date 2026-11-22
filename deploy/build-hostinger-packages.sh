#!/usr/bin/env bash
# Build Hostinger Option A upload zips from this repo.
# Does NOT include .env, node_modules, tests, e2e, or git history.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/deploy/packages"
STAMP="$(date +%Y%m%d)"
APP_ZIP="$OUT/mydent_app_new_${STAMP}.zip"
PUB_ZIP="$OUT/public_html_${STAMP}.zip"
STAGE="$OUT/.stage_${STAMP}"

echo "==> Cleaning stage"
rm -rf "$STAGE" "$OUT"/*.zip 2>/dev/null || true
mkdir -p "$STAGE/mydent_app_new" "$STAGE/public_html" "$OUT"

echo "==> Staging Laravel app (mydent_app_new)"
rsync -a \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='.cursor' \
  --exclude='e2e' \
  --exclude='tests' \
  --exclude='deploy' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/data/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='bootstrap/cache/*.php' \
  --exclude='database/*.sqlite' \
  --exclude='database/*.sqlite.*' \
  --exclude='database/*before-hostinger*' \
  --exclude='*.sql' \
  --exclude='*.md' \
  --exclude='.DS_Store' \
  "$ROOT/app" \
  "$ROOT/bootstrap" \
  "$ROOT/config" \
  "$ROOT/database" \
  "$ROOT/lang" \
  "$ROOT/resources" \
  "$ROOT/routes" \
  "$ROOT/storage" \
  "$ROOT/vendor" \
  "$ROOT/artisan" \
  "$ROOT/composer.json" \
  "$ROOT/composer.lock" \
  "$STAGE/mydent_app_new/"

# Keep empty writable dirs
mkdir -p \
  "$STAGE/mydent_app_new/storage/logs" \
  "$STAGE/mydent_app_new/storage/framework/cache/data" \
  "$STAGE/mydent_app_new/storage/framework/sessions" \
  "$STAGE/mydent_app_new/storage/framework/views" \
  "$STAGE/mydent_app_new/storage/app/public" \
  "$STAGE/mydent_app_new/bootstrap/cache"
touch \
  "$STAGE/mydent_app_new/storage/logs/.gitignore" \
  "$STAGE/mydent_app_new/bootstrap/cache/.gitignore"

# Optional helpers root file if present
if [[ -f "$ROOT/app/helpers.php" ]]; then
  :
fi

echo "==> Staging public_html (web root)"
rsync -a \
  --exclude='.DS_Store' \
  --exclude='index.php' \
  --exclude='.htaccess' \
  "$ROOT/public/" "$STAGE/public_html/"

# Option A bootstrap (points to ../mydent_app_new)
cp "$ROOT/deploy/hostinger-option-a/index.php" "$STAGE/public_html/index.php"
cp "$ROOT/deploy/hostinger-option-a/.htaccess" "$STAGE/public_html/.htaccess"

# Safety: never ship env into web root
rm -f "$STAGE/public_html/.env" "$STAGE/public_html/.env.example" 2>/dev/null || true

echo "==> Zipping"
(
  cd "$STAGE"
  zip -rq "$APP_ZIP" mydent_app_new
  zip -rq "$PUB_ZIP" public_html
)

# Manifest
{
  echo "Built: $(date -u +%Y-%m-%dT%H:%M:%SZ)"
  echo "App zip: $(basename "$APP_ZIP") ($(du -h "$APP_ZIP" | awk '{print $1}'))"
  echo "Public zip: $(basename "$PUB_ZIP") ($(du -h "$PUB_ZIP" | awk '{print $1}'))"
  echo "index.php appBase: ../mydent_app_new"
  echo "Excluded: .env, node_modules, tests, e2e, .git, *.sql, local caches"
} > "$OUT/MANIFEST_${STAMP}.txt"

rm -rf "$STAGE"
echo "==> Done"
cat "$OUT/MANIFEST_${STAMP}.txt"
ls -lh "$OUT"/*.zip
