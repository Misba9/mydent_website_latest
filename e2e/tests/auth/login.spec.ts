import { test, expect } from '../../fixtures/test';

test.describe('Authentication @smoke', () => {
  test('login page renders', async ({ loginPage }) => {
    await loginPage.open();
    await loginPage.expectLoginForm();
  });

  test('invalid login shows error', async ({ page, loginPage }) => {
    await loginPage.open();
    await loginPage.login('nobody@example.com', 'wrong-password-xyz');
    await expect(page).toHaveURL(/login/);
    await loginPage.expectInvalidCredentialsFeedback();
  });

  test('admin can login and reach dashboard', async ({ page, loginPage, env }) => {
    await loginPage.open();
    await loginPage.login(env.admin.email, env.admin.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
    expect(page.url()).toMatch(/admin|dashboard/i);
    await expect(page.locator('body')).toContainText(/dashboard|appointment|patient|doctor/i);
  });

  test('doctor can login and reach doctor area', async ({ page, loginPage, env }) => {
    await loginPage.open();
    await loginPage.login(env.doctor.email, env.doctor.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
    expect(page.url()).toMatch(/doctor/i);
  });

  test('patient can login and reach patient area', async ({ page, loginPage, env }) => {
    await loginPage.open();
    await loginPage.login(env.patient.email, env.patient.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
    expect(page.url()).toMatch(/patient/i);
  });

  test('guest cannot access admin dashboard', async ({ page }) => {
    const response = await page.goto('/admin/dashboard', { waitUntil: 'domcontentloaded' });
    expect(response).toBeTruthy();
    // Redirect to login or 403
    const url = page.url();
    const status = response!.status();
    const blocked = /login/i.test(url) || status === 403 || status === 401;
    expect(blocked).toBeTruthy();
  });
});
