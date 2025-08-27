const { test, expect } = require('@playwright/test');

test('BOS Quick Validation', async ({ page }) => {
    console.log('🎯 Final BOS validation...');
    
    let errorCount = 0;
    
    page.on('console', msg => {
        if (msg.type() === 'error') {
            errorCount++;
            console.log('❌', msg.text());
        }
    });
    
    // Navigate with cache bust
    await page.goto(`http://localhost:8082/bos?t=${Date.now()}`);
    await page.waitForTimeout(5000);
    
    // Quick checks
    const check = await page.evaluate(() => ({
        widgets: document.querySelectorAll('.stat-widget').length,
        statusWidget: !!document.querySelector('.status-overview-widget'),
        search: !!document.querySelector('#inventorySearch'),
        table: !!document.querySelector('#inventoryTable'),
        notionCSS: !!document.querySelector('link[href*="notion-enterprise"]')
    }));
    
    console.log('📊 Results:', JSON.stringify(check, null, 2));
    console.log('❌ JS Errors:', errorCount);
    
    expect(check.widgets).toBe(5);
    expect(check.statusWidget).toBe(true);
    expect(check.search).toBe(true);
    
    console.log('✅ BOS Notion Enterprise design working!');
});