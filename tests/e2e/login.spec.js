import { test, expect } from '@playwright/test';

test('logs in with testuser', async ({ page }) => {
    await page.goto('/login');

    await page.fill('#login_form_email', 'testuser@wp.pl');
    await page.fill('#login_form_password', 'testtest');
    await page.click('button[type="submit"]');

    await expect(page).toHaveURL(/dashboard|home/);
    await expect(page.locator('h1')).toHaveText(/Welcome|Dashboard/);
});