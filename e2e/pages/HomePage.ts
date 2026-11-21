import { type Page, expect } from '@playwright/test';
import { BasePage } from './BasePage';

export class HomePage extends BasePage {
  constructor(page: Page) {
    super(page);
  }

  async open() {
    await this.goto('/');
  }

  async expectHero() {
    await expect(this.page.locator('.md-hero, .hero-section, h1').first()).toBeVisible();
    await expect(this.page.getByRole('link', { name: /book/i }).first()).toBeVisible();
  }

  async expectNavLinks() {
    const nav = this.page.locator('header nav, header .navbar');
    await expect(nav.getByRole('link', { name: /home/i }).first()).toBeVisible();
    await expect(nav.getByRole('link', { name: /aligners/i }).first()).toBeVisible();
    await expect(nav.getByRole('link', { name: /products/i }).first()).toBeVisible();
  }
}
