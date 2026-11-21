import { type Page, expect } from '@playwright/test';
import { BasePage } from './BasePage';
import { LoginPage } from './LoginPage';
import { env } from '../utils/env';

export class AdminPage extends BasePage {
  constructor(page: Page) {
    super(page);
  }

  async loginAsAdmin() {
    const login = new LoginPage(this.page);
    await login.open();
    await login.login(env.admin.email, env.admin.password);
    await this.page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 30_000 });
  }

  async open(path: string) {
    await this.goto(path);
  }

  async expectDashboard() {
    await expect(this.page).toHaveURL(/admin/i);
    await expect(this.page.locator('body')).toContainText(/dashboard|appointment|patient|doctor/i);
  }
}
