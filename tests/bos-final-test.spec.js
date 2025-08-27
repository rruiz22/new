const { test, expect } = require('@playwright/test');

test('BOS Final Design Check', async ({ page }) => {
    console.log('🎨 Testing BOS Notion Enterprise design...');
    
    const logs = [];
    const errors = [];
    
    page.on('console', msg => {
        logs.push(msg.text());
        if (msg.type() === 'error') {
            errors.push(msg.text());
            console.log('❌', msg.text());
        } else {
            console.log('📝', msg.text());
        }
    });
    
    page.on('pageerror', error => {
        errors.push(error.message);
        console.log('💥', error.message);
    });
    
    // Add cache-busting parameter
    const timestamp = Date.now();
    await page.goto(`http://localhost:8082/bos?v=${timestamp}&debug=true`);
    await page.waitForTimeout(3000);
    
    // Check for Notion design elements
    const designCheck = await page.evaluate(() => {
        return {
            hasNotionContainer: !!document.querySelector('.notion-container'),
            hasStatsContainer: !!document.querySelector('.stats-container'),
            hasStatsGrid: !!document.querySelector('.stats-grid'),
            hasStatusWidget: !!document.querySelector('.status-overview-widget'),
            hasSearchBar: !!document.querySelector('#inventorySearch'),
            hasTableContainer: !!document.querySelector('.table-wrapper-container'),
            widgetCount: document.querySelectorAll('.stat-widget').length,
            pageTitle: document.title,
            bodyClasses: document.body.className,
            hasNotionCSS: Array.from(document.styleSheets).some(sheet => {
                try {
                    return sheet.href && sheet.href.includes('notion-enterprise');
                } catch(e) {
                    return false;
                }
            })
        };
    });
    
    console.log('🎨 Design Check Results:');
    console.log(JSON.stringify(designCheck, null, 2));
    
    // Test widget interactions
    console.log('🖱️ Testing interactions...');
    
    // Test first widget click
    const firstWidget = page.locator('.stat-widget').first();
    if (await firstWidget.isVisible()) {
        await firstWidget.click();
        const isActive = await firstWidget.evaluate(el => el.classList.contains('active'));
        console.log('📊 Widget click works:', isActive);
    }
    
    // Test search
    const searchInput = page.locator('#inventorySearch');
    if (await searchInput.isVisible()) {
        await searchInput.fill('test');
        const value = await searchInput.inputValue();
        console.log('🔍 Search works:', value === 'test');
    }
    
    // Summary
    console.log('\n📊 SUMMARY:');
    console.log('============');
    console.log('✅ Widgets:', designCheck.widgetCount === 5);
    console.log('✅ Stats Container:', designCheck.hasStatsContainer);
    console.log('✅ Status Widget:', designCheck.hasStatusWidget);
    console.log('✅ Search Bar:', designCheck.hasSearchBar);
    console.log('✅ Notion CSS:', designCheck.hasNotionCSS);
    console.log('❌ Errors:', errors.length);
    
    // Take screenshot
    await page.screenshot({ path: 'bos-final-design.png', fullPage: true });
    
    expect(designCheck.hasStatsContainer).toBe(true);
    expect(designCheck.widgetCount).toBe(5);
});