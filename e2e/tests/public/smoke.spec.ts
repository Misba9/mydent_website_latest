import { test, expect } from '../../fixtures/test';

const publicPages = [
  { path: '/', name: 'Homepage', marker: /mydent|book|smile/i },
  { path: '/medical-about-us', name: 'About', marker: /why|aligner|mydent/i },
  { path: '/medical-services', name: 'Services', marker: /service|brace|smile|aligner/i },
  { path: '/medical-doctors', name: 'Doctors/Aligners', marker: /aligner|doctor|price|mydent/i },
  { path: '/medical-contact', name: 'Contact', marker: /contact|centre|center|visit/i },
  { path: '/faqs', name: 'FAQs', marker: /faq|question/i },
  { path: '/products', name: 'Products', marker: /product|shop|mydent/i },
  { path: '/medical-appointment', name: 'Appointment', marker: /scan|appointment|book|information/i },
  { path: '/cart', name: 'Cart', marker: /cart|empty|product/i },
];

test.describe('Public website smoke @smoke', () => {
  for (const pageDef of publicPages) {
    test(`${pageDef.name} loads (${pageDef.path})`, async ({ page }) => {
      const failed: string[] = [];
      page.on('response', (res) => {
        if (res.status() >= 500 && res.url().startsWith(page.url().split('/').slice(0, 3).join('/') || '')) {
          failed.push(`${res.status()} ${res.url()}`);
        }
      });

      const response = await page.goto(pageDef.path, { waitUntil: 'domcontentloaded' });
      expect(response, `${pageDef.name} should respond`).toBeTruthy();
      expect(response!.status(), `${pageDef.name} status`).toBeLessThan(500);

      await expect(page.locator('body')).toBeVisible();
      await expect(page.locator('header').first()).toBeVisible();
      await expect(page.locator('footer').first()).toBeVisible();
      await expect(page.locator('body')).toContainText(pageDef.marker);

      const overflow = await page.evaluate(
        () => document.documentElement.scrollWidth > document.documentElement.clientWidth + 4,
      );
      expect(overflow, `${pageDef.name} horizontal overflow`).toBeFalsy();
      expect(failed, `server errors on ${pageDef.name}`).toEqual([]);
    });
  }

  test('header navigation reaches key destinations', async ({ page, homePage }) => {
    await homePage.open();
    await homePage.expectNavLinks();

    await page.locator('header').getByRole('link', { name: /products/i }).first().click();
    await expect(page).toHaveURL(/products|ecom/);

    await page.locator('header').getByRole('link', { name: /book/i }).first().click();
    await expect(page).toHaveURL(/medical-appointment/);
  });

  test('footer contains contact and legal links', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' });
    const footer = page.locator('footer');
    await expect(footer.locator('a[href*="faqs"]').first()).toBeVisible();
    await expect(footer.locator('a[href*="privacy"]').first()).toBeVisible();
    await expect(footer.locator('a[href*="terms"]').first()).toBeVisible();
  });
});
