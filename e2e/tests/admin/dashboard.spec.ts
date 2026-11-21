import { test, expect } from '../../fixtures/test';
import { AdminPage } from '../../pages/AdminPage';

test.describe('Admin dashboard @smoke @admin', () => {
  test.beforeEach(async ({ page }) => {
    const admin = new AdminPage(page);
    await admin.loginAsAdmin();
  });

  test('dashboard loads after admin login', async ({ page }) => {
    await expect(page).toHaveURL(/admin/i);
    await expect(page.locator('body')).toContainText(/dashboard|appointment|patient|doctor/i);
  });

  const adminPages = [
    { path: '/admin/patients', marker: /patient/i },
    { path: '/admin/appointments', marker: /appointment/i },
    { path: '/admin/doctors', marker: /doctor/i },
    { path: '/admin/services', marker: /service/i },
    { path: '/admin/faqs', marker: /faq/i },
    { path: '/admin/products', marker: /product/i },
    { path: '/orders', marker: /order/i },
    { path: '/admin/settings', marker: /setting|clinic|general/i },
    { path: '/admin/main-banners', marker: /banner/i },
    { path: '/admin/homepage-videos', marker: /video|image|upload|characteristic/i },
  ];

  for (const p of adminPages) {
    test(`admin page ${p.path} loads`, async ({ page }) => {
      const res = await page.goto(p.path, { waitUntil: 'domcontentloaded' });
      expect(res).toBeTruthy();
      expect(res!.status()).toBeLessThan(500);
      // Must stay authenticated in admin/orders area (not bounced to login)
      expect(page.url()).not.toMatch(/\/login$/);
      await expect(page.locator('body')).toContainText(p.marker);
    });
  }

  test('admin can open create patient form', async ({ page }) => {
    await page.goto('/admin/patients/create', { waitUntil: 'domcontentloaded' });
    expect(page.url()).not.toMatch(/\/login$/);
    const form = page.locator('form').first();
    await expect(form).toBeVisible();
  });

  test('admin can open create appointment form', async ({ page }) => {
    await page.goto('/admin/appointments/create', { waitUntil: 'domcontentloaded' });
    expect(page.url()).not.toMatch(/\/login$/);
    await expect(page.locator('form').first()).toBeVisible();
  });
});
