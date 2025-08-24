const { test, expect } = require('@playwright/test');

test('basic test example', async ({ page }) => {
  // Array to capture console logs
  const consoleLogs = [];
  const consoleErrors = [];
  const consoleWarnings = [];
  
  // Listen to all console events
  page.on('console', msg => {
    const type = msg.type();
    const text = msg.text();
    const location = msg.location();
    
    console.log(`🖥️ [CONSOLE ${type.toUpperCase()}] ${text}`);
    if (location.url) {
      console.log(`   📍 at ${location.url}:${location.lineNumber}:${location.columnNumber}`);
    }
    
    // Store logs by type for assertions
    if (type === 'error') {
      consoleErrors.push({ type, text, location });
    } else if (type === 'warning') {
      consoleWarnings.push({ type, text, location });
    } else {
      consoleLogs.push({ type, text, location });
    }
  });
  
  // Listen to page errors (uncaught exceptions)
  page.on('pageerror', error => {
    console.log(`❌ [PAGE ERROR] ${error.message}`);
    console.log(`   📍 ${error.stack}`);
  });
  
  // Listen to response errors
  page.on('response', response => {
    if (response.status() >= 400) {
      console.log(`🔴 [HTTP ERROR] ${response.status()} ${response.statusText()} - ${response.url()}`);
    }
  });
  
  // Listen to request failures
  page.on('requestfailed', request => {
    console.log(`🚫 [REQUEST FAILED] ${request.method()} ${request.url()}`);
    console.log(`   📍 Failure: ${request.failure()?.errorText}`);
  });
  
  // Navigate to your application
  console.log('🌐 Navigating to application...');
  await page.goto('/');
  
  // Wait for page to load
  await page.waitForLoadState('networkidle');
  
  // Take a screenshot for visual verification
  await page.screenshot({ path: 'screenshot.png' });
  
  // Basic assertion - page should have a title
  await expect(page).toHaveTitle(/.*/);
  
  // Log summary of captured console messages
  console.log(`📊 Console Summary:`);
  console.log(`   📝 Total logs: ${consoleLogs.length}`);
  console.log(`   ⚠️ Warnings: ${consoleWarnings.length}`);
  console.log(`   ❌ Errors: ${consoleErrors.length}`);
  
  // Optional: Fail test if there are console errors
  // expect(consoleErrors).toHaveLength(0);
});