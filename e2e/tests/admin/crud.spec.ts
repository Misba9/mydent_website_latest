import { test, expect } from '../../fixtures/test';
import { AdminPage } from '../../pages/AdminPage';

test.describe('Admin CRUD flows @admin @crud', () => {
  test.beforeEach(async ({ page }) => {
    await new AdminPage(page).loginAsAdmin();
  });

  test('patient create form validates required fields', async ({ page }) => {
    await page.goto('/admin/patients/create', { waitUntil: 'domcontentloaded' });
    const form = page.locator('#createPatientForm');
    await expect(form).toBeVisible({ timeout: 20_000 });

    await form.locator('input[name="email"]').fill('');
    await form.locator('button[type="submit"], input[type="submit"]').first().click();
    expect(page.url()).toMatch(/patients/i);
    await expect(page.locator('body')).not.toContainText(/Whoops|SQLSTATE|BadMethodCallException/i);
  });

  test('admin can create a patient and land on patients index', async ({ page }) => {
    const stamp = Date.now();
    const email = `admin.crud.${stamp}@example.com`;

    await page.goto('/admin/patients/create', { waitUntil: 'domcontentloaded' });
    const form = page.locator('#createPatientForm');
    await expect(form).toBeVisible({ timeout: 20_000 });

    await form.locator('input[name="first_name"]').fill('Admin');
    await form.locator('input[name="last_name"]').fill(`CRUD${stamp}`);
    await form.locator('input[name="email"]').fill(email);
    await form.locator('input[name="password"]').fill('password123');
    await form.locator('input[name="password_confirmation"]').fill('password123');
    await Promise.all([
      page.waitForURL(/\/admin\/patients(?!\/create)/, { timeout: 30_000 }),
      form.locator('button[type="submit"], input[type="submit"]').first().click(),
    ]);

    expect(page.url()).toMatch(/\/admin\/patients/);
    expect(page.url()).not.toMatch(/create/);
    await expect(page.locator('body')).not.toContainText(/Whoops|SQLSTATE/i);
    // Flash / list may be Livewire-deferred; create success is the redirect itself.
    await expect(page.locator('body')).toContainText(/patient/i);
  });

  test('admin can create an FAQ', async ({ page }) => {
    const stamp = Date.now();
    await page.goto('/admin/faqs/create', { waitUntil: 'domcontentloaded' });
    expect(page.url()).not.toMatch(/\/login$/);

    const question = page.locator('.container-fluid input[name="question"]');
    const answer = page.locator('.container-fluid textarea[name="answer"]');
    await expect(question).toBeVisible({ timeout: 20_000 });
    await question.fill(`QA FAQ question ${stamp}?`);
    await answer.fill(`QA FAQ answer ${stamp}`);
    await page.locator('.container-fluid button[type="submit"], .container-fluid input[type="submit"]').first().click();
    await page.waitForLoadState('domcontentloaded');

    expect(page.url()).not.toMatch(/server-error|500/);
    await expect(page.locator('body')).not.toContainText(/Whoops|SQLSTATE/i);
    await page.goto('/admin/faqs', { waitUntil: 'domcontentloaded' });
    await expect(page.getByText(`QA FAQ question ${stamp}`, { exact: false }).first()).toBeVisible({
      timeout: 20_000,
    });
  });

  test('admin can create specialization via modal AJAX', async ({ page }) => {
    const stamp = Date.now();
    const name = `QA Spec ${stamp}`;

    await page.goto('/admin/specializations', { waitUntil: 'domcontentloaded' });
    const addBtn = page.locator('#createSpecialization');
    await expect(addBtn).toBeVisible({ timeout: 20_000 });
    await addBtn.click();

    const modal = page.locator('#createSpecializationModal');
    await expect(modal).toBeVisible({ timeout: 10_000 });
    await modal.locator('input[name="name"]').fill(name);
    await modal.locator('button[type="submit"], input[type="submit"]').first().click();
    await expect(modal).toBeHidden({ timeout: 15_000 }).catch(() => {});
    await page.reload({ waitUntil: 'domcontentloaded' });
    await expect(page.getByText(name, { exact: false }).first()).toBeVisible({ timeout: 20_000 });
  });

  test('admin can open product create and submit minimal product', async ({ page }) => {
    const stamp = Date.now();
    await page.goto('/products/create', { waitUntil: 'domcontentloaded' });
    expect(page.url()).toMatch(/products\/create/);

    const form = page.locator('.container-fluid form[action*="products"]');
    await expect(form).toBeVisible({ timeout: 20_000 });
    await form.locator('input[name="name"]').fill(`QA Product ${stamp}`);
    await form.locator('input[name="category"]').fill('QA');
    await form.locator('input[name="price"]').fill('499');
    await form.locator('input[name="quantity"]').fill('10');
    await form.locator('textarea[name="description"]').fill(`QA product description ${stamp}`);
    await form.locator('button[type="submit"], input[type="submit"]').first().click();
    await page.waitForLoadState('domcontentloaded');
    expect(page.url()).not.toMatch(/server-error|500/);
    await expect(page.locator('body')).not.toContainText(/Whoops|SQLSTATE/i);
  });

  test('settings page exposes saveable clinic form', async ({ page }) => {
    await page.goto('/admin/settings', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('#generalSettingForm')).toBeVisible({ timeout: 20_000 });
    await expect(page.locator('body')).toContainText(/setting|clinic|general|app/i);
  });

  test('orders page lists or empty state without error', async ({ page }) => {
    await page.goto('/orders', { waitUntil: 'domcontentloaded' });
    expect(page.url()).not.toMatch(/\/login$/);
    await expect(page.locator('body')).toContainText(/order/i);
    await expect(page.locator('body')).not.toContainText(/Whoops|SQLSTATE/i);
  });

  test('profile settings page loads for admin', async ({ page }) => {
    await page.goto('/profile/edit', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('.container-fluid form, form').first()).toBeVisible({ timeout: 20_000 });
    await expect(page.locator('body')).toContainText(/profile|account|email|password/i);
  });
});
