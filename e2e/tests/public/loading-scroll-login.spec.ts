import { test, expect } from '../../fixtures/test';

const pagesWithSections = [
  '/',
  '/medical-services',
  '/medical-about-us',
  '/products',
  '/medical-appointment',
  '/medical-contact',
  '/medical-doctors',
];

test.describe('Loading, scrollbar, login & section visibility @smoke', () => {
  test('login page loads without Mix pages.css exception', async ({ page }) => {
    const response = await page.goto('/login', { waitUntil: 'domcontentloaded' });
    expect(response?.status()).toBeLessThan(500);
    const body = await page.locator('body').innerText();
    expect(body).not.toMatch(/Unable to locate Mix file/i);
    expect(body).not.toMatch(/Exception/i);
    await expect(page.getByRole('heading', { name: /sign in/i })).toBeVisible();
    await expect(page.getByRole('button', { name: /login/i })).toBeVisible();
    await expect(page.locator('input[type="email"], input[name="email"]').first()).toBeVisible();
  });

  for (const path of pagesWithSections) {
    test(`${path} has single document scrollbar and visible sections`, async ({ page }) => {
      const consoleErrors: string[] = [];
      page.on('pageerror', (err) => consoleErrors.push(err.message));

      const response = await page.goto(path, { waitUntil: 'load', timeout: 60_000 });
      expect(response?.status(), `${path} status`).toBeLessThan(500);

      // Allow reveal failsafe / loader hide
      await page.waitForTimeout(1400);

      const metrics = await page.evaluate(() => {
        const html = getComputedStyle(document.documentElement);
        const body = getComputedStyle(document.body);
        const reveals = Array.from(document.querySelectorAll('.md-reveal'));
        const hiddenReveals = reveals.filter((el) => getComputedStyle(el).opacity === '0').length;
        const horizontalOverflow =
          document.documentElement.scrollWidth > document.documentElement.clientWidth + 4;

        // Nested full-height scrollers (excluding offcanvas/modals)
        const nested = Array.from(document.querySelectorAll('body *')).filter((el) => {
          if (el.closest('.offcanvas, .modal, .md-page-loader')) return false;
          const cs = getComputedStyle(el);
          const oy = cs.overflowY;
          if (oy !== 'auto' && oy !== 'scroll') return false;
          const r = el.getBoundingClientRect();
          return r.height > window.innerHeight * 0.85 && el.scrollHeight > el.clientHeight + 20;
        }).length;

        return {
          htmlOY: html.overflowY,
          bodyOY: body.overflowY,
          dualAuto: html.overflowY === 'auto' && body.overflowY === 'auto',
          hiddenReveals,
          revealCount: reveals.length,
          horizontalOverflow,
          nested,
        };
      });

      expect(metrics.dualAuto, `${path} dual auto overflow`).toBeFalsy();
      expect(metrics.nested, `${path} nested page scrollers`).toBe(0);
      expect(metrics.horizontalOverflow, `${path} horizontal overflow`).toBeFalsy();
      expect(metrics.hiddenReveals, `${path} blank md-reveal sections`).toBe(0);
      expect(consoleErrors, `${path} pageerrors`).toEqual([]);
    });
  }

  for (const vp of [
    { name: 'mobile', width: 390, height: 844 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'desktop', width: 1280, height: 800 },
  ]) {
    test(`home responsive ${vp.name}`, async ({ page }) => {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      await page.goto('/', { waitUntil: 'load', timeout: 60_000 });
      await page.waitForTimeout(1400);
      await expect(page.locator('header').first()).toBeVisible();
      await expect(page.locator('footer').first()).toBeVisible();

      const bad = await page.evaluate(() => {
        const html = getComputedStyle(document.documentElement);
        const body = getComputedStyle(document.body);
        return {
          dualAuto: html.overflowY === 'auto' && body.overflowY === 'auto',
          overflowX: document.documentElement.scrollWidth > document.documentElement.clientWidth + 4,
          blank: Array.from(document.querySelectorAll('.md-reveal')).filter(
            (el) => getComputedStyle(el).opacity === '0',
          ).length,
        };
      });
      expect(bad.dualAuto).toBeFalsy();
      expect(bad.overflowX).toBeFalsy();
      expect(bad.blank).toBe(0);
    });
  }
});
