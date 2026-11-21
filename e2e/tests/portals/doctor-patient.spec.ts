import { test, expect } from '../../fixtures/test';
import { LoginPage } from '../../pages/LoginPage';

test.describe('Doctor portal @smoke @portal', () => {
  test.beforeEach(async ({ page, env }) => {
    const login = new LoginPage(page);
    await login.open();
    await login.login(env.doctor.email, env.doctor.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
  });

  test('doctor dashboard loads', async ({ page }) => {
    await expect(page).toHaveURL(/doctor/i);
    await expect(page.locator('body')).toContainText(/dashboard|appointment|patient|schedule/i);
  });

  const doctorPages = [
    { path: '/doctors/appointments', marker: /appointment/i },
    { path: '/doctors/appointments-calendar', marker: /calendar|appointment|schedule/i },
    { path: '/doctors/doctor-sessions', marker: /session|schedule|availability/i },
    { path: '/doctors/visits', marker: /visit|encounter|patient/i },
    { path: '/doctors/holidays', marker: /holiday|leave/i },
  ];

  for (const p of doctorPages) {
    test(`doctor page ${p.path} loads`, async ({ page }) => {
      const res = await page.goto(p.path, { waitUntil: 'domcontentloaded' });
      expect(res).toBeTruthy();
      expect(res!.status()).toBeLessThan(500);
      expect(page.url()).not.toMatch(/\/login$/);
      await expect(page.locator('body')).toContainText(p.marker);
    });
  }

  test('doctor cannot access admin patients', async ({ page }) => {
    const res = await page.goto('/admin/patients', { waitUntil: 'domcontentloaded' });
    expect(res).toBeTruthy();
    const denied =
      res!.status() === 403 ||
      /login/i.test(page.url()) ||
      !/admin\/patients/.test(page.url()) ||
      (await page.locator('body').innerText()).match(/forbidden|unauthorized|403/i) != null;
    expect(denied, 'Doctor must not manage admin patients').toBeTruthy();
  });
});

test.describe('Patient portal @smoke @portal', () => {
  test.beforeEach(async ({ page, env }) => {
    const login = new LoginPage(page);
    await login.open();
    await login.login(env.patient.email, env.patient.password);
    await page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
  });

  test('patient dashboard loads', async ({ page }) => {
    await expect(page).toHaveURL(/patient/i);
    await expect(page.locator('body')).toContainText(/dashboard|appointment|visit|profile/i);
  });

  const patientPages = [
    { path: '/patients/appointments', marker: /appointment/i },
    { path: '/patients/appointments/create', marker: /appointment|doctor|book|schedule/i },
    { path: '/patients/patient-visits', marker: /visit|history|record/i },
    { path: '/patients/transactions', marker: /transaction|payment|history/i },
  ];

  for (const p of patientPages) {
    test(`patient page ${p.path} loads`, async ({ page }) => {
      const res = await page.goto(p.path, { waitUntil: 'domcontentloaded' });
      expect(res).toBeTruthy();
      expect(res!.status()).toBeLessThan(500);
      expect(page.url()).not.toMatch(/\/login$/);
      await expect(page.locator('body')).toContainText(p.marker);
    });
  }
});
