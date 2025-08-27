const { test, expect } = require('@playwright/test');

test('BOS Page Final Check', async ({ page }) => {
    console.log('🔍 Checking BOS page...');
    
    const logs = [];
    const errors = [];
    
    page.on('console', msg => {
        const text = msg.text();
        logs.push(text);
        if (msg.type() === 'error') {
            errors.push(text);
            console.log('❌ ERROR:', text);
        } else {
            console.log('📝 LOG:', text);
        }
    });
    
    page.on('pageerror', error => {
        errors.push(error.message);
        console.log('💥 PAGE ERROR:', error.message);
    });
    
    // Navigate to BOS
    await page.goto('http://localhost:8082/bos/');
    await page.waitForTimeout(3000);
    
    // Check elements
    const elements = await page.evaluate(() => {
        return {
            title: document.title,
            hasStatsContainer: !!document.querySelector('.stats-container'),
            hasStatusWidget: !!document.querySelector('.status-overview-widget'),
            hasSearch: !!document.querySelector('#inventorySearch'),
            widgetCount: document.querySelectorAll('.stat-widget').length,
            hasTable: !!document.querySelector('#inventoryTable'),
            scripts: {
                jquery: typeof window.jQuery !== 'undefined',
                inventoryTable: typeof window.inventoryTable !== 'undefined',
                statusWidget: typeof window.StatusOverviewWidget !== 'undefined'
            }
        };
    });
    
    console.log('📊 Page Elements:', JSON.stringify(elements, null, 2));
    console.log('📝 Logs Count:', logs.length);
    console.log('❌ Errors Count:', errors.length);
    
    if (errors.length > 0) {
        console.log('🔴 ERRORS:');
        errors.forEach(error => console.log('  -', error));
    }
    
    // Basic functionality check
    expect(elements.title).toContain('BOS');
    expect(elements.hasTable).toBe(true);
});