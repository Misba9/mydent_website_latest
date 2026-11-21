import { expect, type Locator, type Page } from '@playwright/test';

export class BasePage {
  constructor(protected readonly page: Page) {}

  async goto(path: string) {
    await this.page.goto(path, { waitUntil: 'domcontentloaded' });
  }

  async expectNoCriticalConsoleErrors() {
    const errors: string[] = [];
    this.page.on('console', (msg) => {
      if (msg.type() === 'error') {
        const text = msg.text();
        // Ignore noisy third-party / missing optional assets
        if (/favicon|recaptcha|stripe|razorpay|chrome-extension/i.test(text)) return;
        errors.push(text);
      }
    });
    return () => errors;
  }

  async expectVisible(locator: Locator) {
    await expect(locator).toBeVisible();
  }

  headerLogo(): Locator {
    return this.page.locator('header img.front-app-logo, header .header-logo img').first();
  }

  footer(): Locator {
    return this.page.locator('footer').first();
  }

  async assertNoHorizontalOverflow() {
    const overflow = await this.page.evaluate(() => {
      const doc = document.documentElement;
      return doc.scrollWidth > doc.clientWidth + 2;
    });
    expect(overflow, 'page should not horizontally overflow').toBeFalsy();
  }
}
