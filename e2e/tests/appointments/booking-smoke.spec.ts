import { test, expect } from '../../fixtures/test';

test.describe('Appointment booking @smoke', () => {
  test('appointment page shows booking form', async ({ page }) => {
    await page.goto('/medical-appointment', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('form#frontAppointmentBook, form.book-appointment-form, form').first()).toBeVisible();
    await expect(page.locator('body')).toContainText(/scan|appointment|information|book/i);
  });

  test('homepage quick-book posts to appointment flow', async ({ page }) => {
    await page.goto('/', { waitUntil: 'domcontentloaded' });
    const form = page.locator('form[action*="appointment"], .appointmnet-section form').first();
    if ((await form.count()) === 0) {
      test.skip(true, 'Homepage appointment form not present');
      return;
    }
    await form.locator('input[name="first_name"]').fill('QA');
    await form.locator('input[name="last_name"]').fill('Tester');
    await form.locator('input[name="email"]').fill(`qa.tester+${Date.now()}@example.com`);
    // Doctor select may be required
    const doctor = form.locator('select[name="doctor_id"]');
    if (await doctor.count()) {
      const options = doctor.locator('option');
      const n = await options.count();
      if (n > 1) {
        await doctor.selectOption({ index: 1 });
      }
    }
    const date = form.locator('input[name="date"]');
    if (await date.count()) {
      // May be datepicker readonly — set via JS if needed
      await date.evaluate((el: HTMLInputElement) => {
        el.removeAttribute('readonly');
        const d = new Date();
        d.setDate(d.getDate() + 3);
        el.value = d.toISOString().slice(0, 10);
      });
    }
    await form.locator('button[type="submit"]').click();
    await page.waitForLoadState('domcontentloaded');
    // Expect either appointment page, success message, or validation errors — not 500
    expect(page.url()).not.toMatch(/server-error|500/);
  });
});
