# MyDent Playwright E2E

## Setup

```bash
cp e2e/.env.example e2e/.env
npx playwright install
php artisan serve --port=8000
```

## Run

```bash
npm run test:e2e:smoke -- --project=chromium
npm run test:e2e -- --project=chromium
npm run test:e2e:report
```

Credentials and `BASE_URL` come from `e2e/.env` only. Never commit secrets.
