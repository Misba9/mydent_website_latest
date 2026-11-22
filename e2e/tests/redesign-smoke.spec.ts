import { test, expect } from '@playwright/test';

const viewports = [
  { name: 'mobile-375', width: 375, height: 812 },
  { name: 'tablet-768', width: 768, height: 1024 },
  { name: 'desktop-1280', width: 1280, height: 800 },
];

test.describe('MyDent redesign smoke @smoke', () => {
  for (const vp of viewports) {
    test(`homepage renders at ${vp.name}`, async ({ page }) => {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      await page.goto('/');
      await expect(page.locator('.md-hero__brand')).toContainText(/mydent/i);
      await expect(page.getByRole('heading', { name: /Your Dream Smile Starts Here/i })).toBeVisible();
      await expect(page.locator('.md-header')).toBeVisible();
      await expect(page.locator('.md-footer')).toBeVisible();

      const overflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > document.documentElement.clientWidth + 1;
      });
      expect(overflow).toBeFalsy();
    });
  }

  test('mobile offcanvas navigation opens', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    await page.getByRole('button', { name: /open menu/i }).click();
    await expect(page.locator('#mdMobileNav')).toBeVisible();
    await expect(page.locator('#mdMobileNav').getByRole('link', { name: 'Aligners' })).toBeVisible();
  });

  test('key public routes load', async ({ page }) => {
    const routes = [
      '/medical-about-us',
      '/medical-doctors',
      '/medical-services',
      '/medical-contact',
      '/products',
      '/medical-appointment',
      '/faqs',
      '/login',
    ];
    for (const route of routes) {
      const response = await page.goto(route);
      expect(response?.ok() || response?.status() === 302).toBeTruthy();
    }
  });
});
