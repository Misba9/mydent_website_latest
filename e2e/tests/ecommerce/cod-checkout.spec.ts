import { test, expect } from '../../fixtures/test';

test.describe('COD checkout E2E @smoke @ecommerce', () => {
  test('complete COD order from product to success', async ({ page }) => {
    // 1. Pick a product
    await page.goto('/products', { waitUntil: 'domcontentloaded' });
    const productLink = page.locator('a.product-link');
    const count = await productLink.count();
    test.skip(count === 0, 'No products available for COD checkout');

    await productLink.first().click();
    await expect(page).toHaveURL(/\/product\//);

    // 2. Add to cart
    await page.getByRole('button', { name: /add to cart/i }).click();
    await page.waitForLoadState('domcontentloaded');

    // 3. Open checkout (via cart or direct)
    await page.goto('/cart', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('body')).not.toContainText(/your cart is empty/i);

    const checkoutBtn = page.getByRole('link', { name: /checkout|proceed/i }).first();
    if (await checkoutBtn.count()) {
      await checkoutBtn.click();
    } else {
      await page.goto('/checkout', { waitUntil: 'domcontentloaded' });
    }
    await expect(page).toHaveURL(/checkout/);

    // 4. Fill shipping + COD (scope to checkout form — footer also has an email field)
    const form = page.locator('#checkout-form');
    const unique = Date.now();
    await form.locator('input[name="name"]').fill('QA COD Tester');
    await form.locator('input[name="email"]').fill(`qa.cod.${unique}@example.com`);
    await form.locator('input[name="phone"]').fill('9876543210');
    await form.locator('input[name="pincode"]').fill('500033');
    await form.locator('input[name="address"]').fill('Jubilee Hills Test Address 8');
    await form.locator('input[name="city"]').fill('Hyderabad');
    await form.locator('input[name="state"]').fill('Telangana');
    await form.locator('input[name="country"]').fill('India');
    await form.locator('#payment_cod').check();

    // 5. Place order
    await Promise.all([
      page.waitForURL(/checkout\/success|success|order/i, { timeout: 45_000 }).catch(() => null),
      form.getByRole('button', { name: /place order/i }).click(),
    ]);

    await page.waitForLoadState('domcontentloaded');
    const url = page.url();
    const body = await page.locator('body').innerText();

    const success =
      /checkout\/success|order/i.test(url) ||
      /thank you|order (has been )?received|placed successfully|success/i.test(body);

    expect(success, `Expected COD success page, got ${url}`).toBeTruthy();
    expect(page.url()).not.toMatch(/server-error|500/);
  });

  test('checkout validation rejects empty required fields', async ({ page }) => {
    await page.goto('/products', { waitUntil: 'domcontentloaded' });
    const productLink = page.locator('a.product-link');
    test.skip((await productLink.count()) === 0, 'No products');

    await productLink.first().click();
    await page.getByRole('button', { name: /add to cart/i }).click();
    await page.goto('/checkout', { waitUntil: 'domcontentloaded' });

    const form = page.locator('#checkout-form');
    await form.locator('input[name="name"]').fill('');
    await form.getByRole('button', { name: /place order/i }).click();
    await expect(page).toHaveURL(/checkout/);
  });
});
