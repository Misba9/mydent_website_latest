import { test, expect } from '../../fixtures/test';

const viewports = [
  { name: 'mobile-375', width: 375, height: 812 },
  { name: 'mobile-390', width: 390, height: 844 },
  { name: 'tablet-768', width: 768, height: 1024 },
  { name: 'desktop-1440', width: 1440, height: 900 },
];

test.describe('Responsive public home @smoke', () => {
  for (const vp of viewports) {
    test(`home usable at ${vp.name}`, async ({ page }) => {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      await page.goto('/', { waitUntil: 'domcontentloaded', timeout: 60_000 });
      await expect(page.locator('header').first()).toBeVisible();
      await expect(page.locator('footer').first()).toBeVisible();

      const overflow = await page.evaluate(
        () => document.documentElement.scrollWidth > document.documentElement.clientWidth + 4,
      );
      expect(overflow, `overflow at ${vp.name}`).toBeFalsy();

      // Book CTA should exist somewhere (header or hero)
      await expect(page.getByRole('link', { name: /book/i }).first()).toBeVisible();
    });
  }
});
