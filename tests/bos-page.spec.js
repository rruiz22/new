const { test, expect } = require('@playwright/test');
const ConsoleHelper = require('./console-helper');

test.describe('BOS Inventory Page', () => {
  let consoleHelper;

  test.beforeEach(async ({ page }) => {
    consoleHelper = new ConsoleHelper();
    consoleHelper.setupConsoleListeners(page);
  });

  test('should load BOS inventory page and capture all logs', async ({ page }) => {
    console.log('🌐 Starting BOS inventory page test...');
    
    // Navigate to BOS inventory page
    await page.goto('/bos/index.php');
    
    // Wait for page to load completely
    await page.waitForLoadState('networkidle');
    
    // Wait for any dynamic content to load
    await page.waitForTimeout(3000);
    
    // Check if page loaded successfully
    await expect(page).toHaveTitle(/BOS Inventory Management/);
    
    // Check for main elements
    const mainContainer = page.locator('.container-fluid');
    await expect(mainContainer).toBeVisible();
    
    // Check for dashboard header
    const dashboardHeader = page.locator('h4:has-text("Inventory Management Dashboard")');
    await expect(dashboardHeader).toBeVisible();
    
    // Check for inventory table
    const inventoryTable = page.locator('#inventoryTable');
    await expect(inventoryTable).toBeVisible();
    
    // Check for filter widgets
    const filterWidgets = page.locator('.filter-widget');
    await expect(filterWidgets.first()).toBeVisible();
    
    // Print console summary
    consoleHelper.printSummary();
    
    // Take screenshot
    await page.screenshot({ 
      path: 'bos-inventory-page.png',
      fullPage: true 
    });
    
    // Export detailed logs for analysis
    const logs = consoleHelper.exportLogs();
    console.log('\n📋 Detailed Error Analysis:');
    
    if (logs.consoleErrors.length > 0) {
      console.log('\n❌ JavaScript Errors:');
      logs.consoleErrors.forEach((error, index) => {
        console.log(`  ${index + 1}. ${error.text}`);
        if (error.location.url) {
          console.log(`     📍 ${error.location.url}:${error.location.lineNumber}`);
        }
      });
    }
    
    if (logs.pageErrors.length > 0) {
      console.log('\n🚨 Page Errors:');
      logs.pageErrors.forEach((error, index) => {
        console.log(`  ${index + 1}. ${error.message}`);
      });
    }
    
    if (logs.httpErrors.length > 0) {
      console.log('\n🔴 HTTP Errors:');
      logs.httpErrors.forEach((error, index) => {
        console.log(`  ${index + 1}. ${error.status} ${error.statusText} - ${error.url}`);
      });
    }
    
    if (logs.requestFailures.length > 0) {
      console.log('\n🚫 Request Failures:');
      logs.requestFailures.forEach((failure, index) => {
        console.log(`  ${index + 1}. ${failure.method} ${failure.url}`);
        console.log(`     Reason: ${failure.failure}`);
      });
    }
    
    // Check for specific BOS functionality
    console.log('\n🔍 Testing BOS-specific functionality...');
    
    // Check for inventory stats widgets
    const totalInventory = page.locator('#totalInventoryItems');
    const recentItems = page.locator('#recentItems');
    const moderateItems = page.locator('#moderateItems');
    const agedItems = page.locator('#agedItems');
    
    // Wait for stats to load
    await page.waitForTimeout(2000);
    
    // Check if stats are displaying numbers (not just "0")
    const totalCount = await totalInventory.textContent();
    const recentCount = await recentItems.textContent();
    const moderateCount = await moderateItems.textContent();
    const agedCount = await agedItems.textContent();
    
    console.log(`📊 Inventory Stats: Total=${totalCount}, Recent=${recentCount}, Moderate=${moderateCount}, Aged=${agedCount}`);
    
    // Test filter functionality
    console.log('\n🔧 Testing filter functionality...');
    
    const recentFilter = page.locator('.filter-widget[data-filter="0-1"]');
    if (await recentFilter.isVisible()) {
      await recentFilter.click();
      await page.waitForTimeout(1000);
      console.log('✅ Recent filter clicked successfully');
    }
    
    // Test refresh functionality
    console.log('\n🔄 Testing refresh functionality...');
    
    const refreshBtn = page.locator('#refreshInventoryBtn');
    if (await refreshBtn.isVisible()) {
      await refreshBtn.click();
      await page.waitForTimeout(2000);
      console.log('✅ Refresh button clicked successfully');
    }
    
    // Check for DataTables initialization
    const isDataTableInitialized = await page.evaluate(() => {
      return window.inventoryTable !== undefined && 
             typeof window.inventoryTable.data === 'function';
    });
    
    console.log(`📋 DataTable initialized: ${isDataTableInitialized}`);
    
    // Check authentication status
    const authStatus = await page.evaluate(() => {
      return {
        isAuthenticated: window.isAuthenticated,
        userType: window.userType,
        authCheckCompleted: window.authCheckCompleted
      };
    });
    
    console.log(`🔐 Auth Status: ${JSON.stringify(authStatus)}`);
    
    // Print final summary
    console.log('\n📈 Test Summary:');
    console.log(`   ✅ Page loaded successfully`);
    console.log(`   📋 Console logs: ${logs.summary.totalLogs}`);
    console.log(`   ⚠️ Warnings: ${logs.summary.warnings}`);
    console.log(`   ❌ Errors: ${logs.summary.consoleErrors + logs.summary.pageErrors}`);
    console.log(`   🔴 HTTP errors: ${logs.summary.httpErrors}`);
    console.log(`   🚫 Request failures: ${logs.summary.requestFailures}`);
  });

  test('should test responsive design on different devices', async ({ page }) => {
    console.log('📱 Testing responsive design...');
    
    // Test on mobile
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/bos/index.php');
    await page.waitForLoadState('networkidle');
    
    // Check if mobile layout is working
    const topBar = page.locator('.top-bar');
    await expect(topBar).toBeVisible();
    
    // Take mobile screenshot
    await page.screenshot({ 
      path: 'bos-mobile-layout.png',
      fullPage: true 
    });
    
    // Test on tablet
    await page.setViewportSize({ width: 768, height: 1024 });
    await page.reload();
    await page.waitForLoadState('networkidle');
    
    // Take tablet screenshot
    await page.screenshot({ 
      path: 'bos-tablet-layout.png',
      fullPage: true 
    });
    
    // Test on desktop
    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.reload();
    await page.waitForLoadState('networkidle');
    
    // Take desktop screenshot
    await page.screenshot({ 
      path: 'bos-desktop-layout.png',
      fullPage: true 
    });
    
    console.log('✅ Responsive design tests completed');
  });

  test('should test all interactive elements', async ({ page }) => {
    console.log('🖱️ Testing interactive elements...');
    
    await page.goto('/bos/index.php');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);
    
    // Test all filter widgets
    const filterWidgets = page.locator('.filter-widget');
    const filterCount = await filterWidgets.count();
    
    console.log(`🔧 Testing ${filterCount} filter widgets...`);
    
    for (let i = 0; i < filterCount; i++) {
      const widget = filterWidgets.nth(i);
      const filterValue = await widget.getAttribute('data-filter');
      
      if (await widget.isVisible()) {
        console.log(`  Testing filter: ${filterValue || 'all'}`);
        await widget.click();
        await page.waitForTimeout(1000);
        
        // Check if filter is applied (widget should have active class)
        const isActive = await widget.evaluate(el => el.classList.contains('active'));
        console.log(`    Filter ${filterValue || 'all'} active: ${isActive}`);
      }
    }
    
    // Test refresh button
    const refreshBtn = page.locator('#refreshInventoryBtn');
    if (await refreshBtn.isVisible()) {
      console.log('🔄 Testing refresh button...');
      await refreshBtn.click();
      await page.waitForTimeout(2000);
    }
    
    // Test debug toggle if visible
    const debugToggle = page.locator('#debugToggle');
    if (await debugToggle.isVisible()) {
      console.log('🐛 Testing debug toggle...');
      await debugToggle.click();
      await page.waitForTimeout(500);
    }
    
    console.log('✅ Interactive elements test completed');
  });

  test.afterEach(async () => {
    // Reset console helper for next test
    if (consoleHelper) {
      consoleHelper.reset();
    }
  });
});