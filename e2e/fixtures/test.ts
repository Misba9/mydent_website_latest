import { test as base, expect } from '@playwright/test';
import { HomePage } from '../pages/HomePage';
import { LoginPage } from '../pages/LoginPage';
import { env } from '../utils/env';

type Fixtures = {
  homePage: HomePage;
  loginPage: LoginPage;
  env: typeof env;
};

export const test = base.extend<Fixtures>({
  homePage: async ({ page }, use) => {
    await use(new HomePage(page));
  },
  loginPage: async ({ page }, use) => {
    await use(new LoginPage(page));
  },
  env: async ({}, use) => {
    await use(env);
  },
});

export { expect };
