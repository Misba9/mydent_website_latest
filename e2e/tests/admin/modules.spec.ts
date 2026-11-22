import { test, expect } from '../../fixtures/test';
import { AdminPage } from '../../pages/AdminPage';

/**
 * Phase 2 — Admin module inventory smoke (list/index + key create entry points).
 * Tagged @admin so it can run with: npx playwright test --grep @admin --project=chromium
 */
test.describe('Admin modules inventory @admin', () => {
  test.beforeEach(async ({ page }) => {
    await new AdminPage(page).loginAsAdmin();
  });

  const modules: Array<{ path: string; marker: RegExp; minStatus?: number }> = [
    { path: '/admin/dashboard', marker: /dashboard|appointment|patient|doctor/i },
    { path: '/admin/doctors', marker: /doctor/i },
    { path: '/admin/doctors/create', marker: /doctor|first|email|password/i },
    { path: '/admin/patients', marker: /patient/i },
    { path: '/admin/patients/create', marker: /patient|first|email|password/i },
    { path: '/admin/appointments', marker: /appointment/i },
    { path: '/admin/appointments/create', marker: /appointment|patient|doctor|service/i },
    { path: '/admin/admin-appointments-calendar', marker: /calendar|appointment/i },
    { path: '/admin/doctor-sessions', marker: /session|schedule|doctor/i },
    { path: '/admin/services', marker: /service/i },
    { path: '/admin/services/create', marker: /service/i },
    { path: '/admin/service-categories', marker: /categor|service/i },
    { path: '/admin/specializations', marker: /special/i },
    { path: '/admin/staffs', marker: /staff/i },
    { path: '/admin/staffs/create', marker: /staff|first|email|password/i },
    { path: '/admin/roles', marker: /role/i },
    { path: '/admin/roles/create', marker: /role|permission/i },
    { path: '/admin/products', marker: /product/i },
    { path: '/products/create', marker: /product|name|price/i },
    { path: '/orders', marker: /order/i },
    { path: '/admin/transactions', marker: /transaction|payment/i },
    { path: '/admin/faqs', marker: /faq/i },
    { path: '/admin/faqs/create', marker: /faq|question|answer/i },
    { path: '/admin/front-patient-testimonials', marker: /testimonial|patient/i },
    { path: '/admin/enquiries', marker: /enquir/i },
    { path: '/admin/subscribers', marker: /subscri/i },
    { path: '/admin/cms', marker: /cms|about|term|privacy|home/i },
    { path: '/admin/settings', marker: /setting|clinic|general/i },
    { path: '/admin/clinic-schedules', marker: /schedule|clinic/i },
    { path: '/admin/holidays', marker: /holiday/i },
    { path: '/admin/main-banners', marker: /banner/i },
    { path: '/admin/homepage-videos', marker: /video|image|upload|characteristic/i },
    { path: '/admin/articles', marker: /article/i },
    { path: '/admin/articles/create', marker: /article|title|content/i },
    { path: '/blogs/create', marker: /blog|title|content/i },
    { path: '/admin/medicines', marker: /medicine/i },
    { path: '/admin/categories', marker: /categor/i },
    { path: '/admin/brands', marker: /brand/i },
    { path: '/admin/medicine-bills', marker: /bill|medicine/i },
    { path: '/admin/medicine-purchase', marker: /purchase|medicine/i },
    { path: '/admin/visits', marker: /visit|encounter/i },
    { path: '/admin/visits/create', marker: /visit|patient|doctor/i },
    { path: '/admin/currencies', marker: /currenc/i },
    { path: '/admin/countries', marker: /countr/i },
    { path: '/admin/states', marker: /state/i },
    { path: '/admin/cities', marker: /cit/i },
    { path: '/profile/edit', marker: /profile|account|password|email/i },
  ];

  for (const mod of modules) {
    test(`loads ${mod.path}`, async ({ page }) => {
      const res = await page.goto(mod.path, { waitUntil: 'domcontentloaded' });
      expect(res, `no response for ${mod.path}`).toBeTruthy();
      expect(res!.status(), `${mod.path} returned ${res!.status()}`).toBeLessThan(500);
      // Modal-only create routes may 404/405 after except — still must not 500 or bounce to login
      expect(page.url()).not.toMatch(/\/login$/);
      if (res!.status() < 400) {
        await expect(page.locator('body')).toContainText(mod.marker);
      }
    });
  }

  test('modal-only specialization create URL is not a 500', async ({ page }) => {
    const res = await page.goto('/admin/specializations/create', { waitUntil: 'domcontentloaded' });
    expect(res).toBeTruthy();
    expect(res!.status()).not.toBe(500);
    await expect(page.locator('body')).not.toContainText(/BadMethodCallException|does not exist/i);
  });
});
