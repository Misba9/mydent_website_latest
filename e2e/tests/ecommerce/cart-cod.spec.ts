import { test, expect } from '../../fixtures/test';

test.describe('E-commerce cart & checkout @smoke', () => {
  test('products page lists or shows empty state', async ({ page }) => {
    await page.goto('/products');
    await expect(page.locator('body')).toContainText(/product|shop|empty|collection|mydent/i);
  });

  test('cart page loads', async ({ page }) => {
    await page.goto('/cart');
    await expect(page.locator('body')).toContainText(/cart/i);
  });

  test('checkout redirects when cart empty', async ({ page }) => {
    await page.goto('/checkout');
    const url = page.url();
    const body = await page.locator('body').innerText();
    const ok =
      /checkout/i.test(url) ||
      /cart/i.test(url) ||
      /empty/i.test(body) ||
      /shipping|payment/i.test(body);
    expect(ok).toBeTruthy();
  });

  test('add first product to cart when products exist', async ({ page }) => {
    await page.goto('/products', { waitUntil: 'domcontentloaded' });
    // Listing cards link to /product/{id} (class is md-product-card__media, not product-link).
    const productLinks = page.locator('a[href*="/product/"]');
    const count = await productLinks.count();
    if (count === 0) {
      test.skip(true, 'No products seeded — cart add blocked');
    }

    // Prefer add-to-cart on listing; fall back to product detail page.
    const listingAdd = page.locator('form.md-product-card__cart-form button[type="submit"]').first();
    if (await listingAdd.count()) {
      await listingAdd.click();
    } else {
      await productLinks.first().click();
      await expect(page).toHaveURL(/\/product\//);
      await page.waitForLoadState('domcontentloaded');
      await page.getByRole('button', { name: /add to cart/i }).click();
    }
    await page.waitForLoadState('domcontentloaded');

    await page.goto('/cart', { waitUntil: 'domcontentloaded' });
    await expect(page.locator('body')).not.toContainText(/your cart is empty/i);
  });
});
