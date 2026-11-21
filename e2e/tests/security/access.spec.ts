import { test, expect } from '../../fixtures/test';

test.describe('Security access checks @smoke', () => {
  test('CSRF token present on login form', async ({ page }) => {
    await page.goto('/login');
    const token = page.locator('input[name="_token"]');
    await expect(token).toHaveCount(1);
    const value = await token.inputValue();
    expect(value.length).toBeGreaterThan(10);
  });

  test('API /api/user requires auth', async ({ request }) => {
    const res = await request.get('/api/user', { maxRedirects: 0 });
    // Laravel may 302 to login or return 401 depending on Accept headers
    expect([401, 302, 403]).toContain(res.status());
  });

  test('upgrade routes blocked for guests', async ({ page }) => {
    const res = await page.goto('/upgrade/database', { waitUntil: 'domcontentloaded' });
    expect(res).toBeTruthy();
    const blocked =
      /login/i.test(page.url()) ||
      [401, 403, 404].includes(res!.status()) ||
      res!.status() === 302;
    expect(blocked).toBeTruthy();
  });

  test('patient cannot open admin appointments URL after login', async ({ page, loginPage, env }) => {
    await loginPage.open();
    await loginPage.login(env.patient.email, env.patient.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });

    const res = await page.goto('/admin/appointments', { waitUntil: 'domcontentloaded' });
    expect(res).toBeTruthy();
    const denied =
      res!.status() === 403 ||
      /patient|login|dashboard/i.test(page.url()) && !/admin\/appointments/.test(page.url()) ||
      (await page.locator('body').innerText()).match(/forbidden|unauthorized|403/i) != null;
    // If still on admin appointments with 200, that is a High finding
    if (/admin\/appointments/.test(page.url()) && res!.status() === 200) {
      expect(denied, 'Patient must not access admin appointments').toBeTruthy();
    } else {
      expect(true).toBeTruthy();
    }
  });
});
