import { type Page, expect } from '@playwright/test';
import { BasePage } from './BasePage';

export class LoginPage extends BasePage {
  constructor(page: Page) {
    super(page);
  }

  async open() {
    await this.goto('/login');
  }

  async login(email: string, password: string) {
    await this.page.locator('input[name="email"], #email').first().fill(email);
    await this.page.locator('input[name="password"], #password').first().fill(password);
    await this.page.locator('button[type="submit"], input[type="submit"]').first().click();
  }

  async expectLoginForm() {
    await expect(this.page.locator('input[name="email"], #email').first()).toBeVisible();
    await expect(this.page.locator('input[name="password"], #password').first()).toBeVisible();
  }

  async expectInvalidCredentialsFeedback() {
    const alert = this.page.locator('.alert-danger, .invalid-feedback, .text-danger, .error').first();
    await expect(alert).toBeVisible({ timeout: 15_000 });
  }
}
