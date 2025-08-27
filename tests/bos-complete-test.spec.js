const { test, expect } = require('@playwright/test');

test('BOS Complete Functionality Test', async ({ page }) => {
    console.log('🎯 Testing complete BOS functionality...');
    
    let errorCount = 0;
    const errors = [];
    
    page.on('console', msg => {
        if (msg.type() === 'error') {
            errorCount++;
            errors.push(msg.text());
            console.log('❌', msg.text());
        }
    });
    
    page.on('pageerror', error => {
        errorCount++;
        errors.push(error.message);
        console.log('💥', error.message);
    });
    
    // Navigate to BOS page
    await page.goto(`http://localhost:8082/bos?cache=${Date.now()}`);
    await page.waitForTimeout(8000); // Wait for all data to load
    
    // Check all elements
    const elements = await page.evaluate(() => {
        return {
            // Main containers
            notionContainer: !!document.querySelector('.notion-container'),
            statsContainer: !!document.querySelector('.stats-container'),
            statusOverview: !!document.querySelector('.status-overview-widget'),
            tableContainer: !!document.querySelector('.table-wrapper-container'),
            
            // Widgets
            statWidgets: document.querySelectorAll('.stat-widget').length,
            statusItems: document.querySelectorAll('.status-item').length,
            
            // Interactive elements
            searchBar: !!document.querySelector('#inventorySearch'),
            refreshBtn: !!document.querySelector('#refreshInventoryBtn'),
            clearBtn: !!document.querySelector('#clearAllFilters'),
            
            // Table
            table: !!document.querySelector('#inventoryTable'),
            tableRows: document.querySelectorAll('#inventoryTable tbody tr').length,
            
            // JavaScript objects
            scripts: {
                jquery: typeof window.jQuery !== 'undefined',
                inventoryTable: typeof window.inventoryTable !== 'undefined',
                notionUI: typeof window.NotionEnterpriseUI !== 'undefined',
                statusWidget: typeof window.StatusOverviewWidget !== 'undefined'
            }
        };
    });
    
    console.log('📊 Element Check:');
    console.log(JSON.stringify(elements, null, 2));
    
    // Test status widget hover functionality
    console.log('🖱️ Testing status widget hover...');
    const statusItems = page.locator('.status-item');
    const statusCount = await statusItems.count();
    
    if (statusCount > 0) {
        console.log(`📊 Found ${statusCount} status items`);
        
        // Test hover on first status item
        const firstStatus = statusItems.first();
        
        // Check if icon is visible
        const hasIcon = await firstStatus.locator('.status-icon i').isVisible();
        console.log('🎨 Status icon visible:', hasIcon);
        
        // Test hover
        await firstStatus.hover();
        await page.waitForTimeout(500);
        
        // Check if tooltip appears
        const tooltip = page.locator('.vehicle-tooltip.show');
        const tooltipVisible = await tooltip.count() > 0;
        console.log('💬 Tooltip appears on hover:', tooltipVisible);
        
        if (tooltipVisible) {
            const vehicleItems = await tooltip.locator('.vehicle-item').count();
            console.log('🚗 Vehicles in tooltip:', vehicleItems);
        }
    }
    
    // Test search functionality
    console.log('🔍 Testing search...');
    const searchInput = page.locator('#inventorySearch');
    if (await searchInput.isVisible()) {
        await searchInput.fill('BMW');
        await page.waitForTimeout(1000);
        
        const searchValue = await searchInput.inputValue();
        console.log('🔍 Search value:', searchValue);
    }
    
    // Test widget clicks
    console.log('📊 Testing widget interactions...');
    const firstWidget = page.locator('.stat-widget').first();
    if (await firstWidget.isVisible()) {
        await firstWidget.click();
        await page.waitForTimeout(500);
        
        const isActive = await firstWidget.evaluate(el => el.classList.contains('active'));
        console.log('🎯 Widget becomes active:', isActive);
    }
    
    // Final summary
    console.log('\\n📋 FINAL SUMMARY:');
    console.log('==================');
    console.log('✅ Stat Widgets:', elements.statWidgets === 5);
    console.log('✅ Status Widget:', elements.statusOverview);
    console.log('✅ Status Items:', elements.statusItems >= 0);
    console.log('✅ Search Bar:', elements.searchBar);
    console.log('✅ Table:', elements.table);
    console.log('✅ Table Rows:', elements.tableRows >= 0);
    console.log('❌ JS Errors:', errorCount);
    
    if (errors.length > 0) {
        console.log('\\n🔴 ERRORS:');
        errors.slice(0, 3).forEach(error => console.log(`  - ${error}`));
    }
    
    // Take final screenshot
    await page.screenshot({ 
        path: 'bos-final-complete.png', 
        fullPage: true 
    });
    
    // Core assertions
    expect(elements.statWidgets).toBe(5);
    expect(elements.statusOverview).toBe(true);
    expect(elements.searchBar).toBe(true);
    expect(elements.table).toBe(true);
    
    console.log('🎉 BOS Complete Test PASSED!');
});