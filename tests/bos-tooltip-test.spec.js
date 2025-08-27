const { test, expect } = require('@playwright/test');

test('BOS Tooltip Functionality Test', async ({ page }) => {
    console.log('💬 Testing tooltip functionality...');
    
    let logCount = 0;
    
    page.on('console', msg => {
        logCount++;
        console.log(`📝 [${logCount}]`, msg.text());
    });
    
    // Navigate and wait for full load
    await page.goto(`http://localhost:8082/bos?debug=tooltip&t=${Date.now()}`);
    await page.waitForTimeout(10000); // Wait longer for data
    
    // Check status widget structure
    const statusCheck = await page.evaluate(() => {
        const statusWidget = document.querySelector('.status-overview-widget');
        const statusGrid = document.querySelector('.status-grid');
        const statusItems = document.querySelectorAll('.status-item');
        
        return {
            hasStatusWidget: !!statusWidget,
            hasStatusGrid: !!statusGrid,
            statusItemCount: statusItems.length,
            statusData: Array.from(statusItems).map(item => ({
                hasIcon: !!item.querySelector('.status-icon i'),
                hasName: !!item.querySelector('.status-name'),
                hasCount: !!item.querySelector('.status-count'),
                count: item.querySelector('.status-count')?.textContent || '0',
                name: item.querySelector('.status-name')?.textContent || 'Unknown'
            }))
        };
    });
    
    console.log('📊 Status Widget Check:');
    console.log(JSON.stringify(statusCheck, null, 2));
    
    // Test tooltip on each status item
    if (statusCheck.statusItemCount > 0) {
        console.log(`🖱️ Testing hover on ${statusCheck.statusItemCount} status items...`);
        
        for (let i = 0; i < statusCheck.statusItemCount; i++) {
            const statusItem = page.locator('.status-item').nth(i);
            
            console.log(`\\n🎯 Testing status item ${i + 1}:`);
            
            // Hover on the status item
            await statusItem.hover();
            await page.waitForTimeout(1000); // Wait for tooltip
            
            // Check if tooltip appears
            const tooltips = page.locator('.vehicle-tooltip');
            const tooltipCount = await tooltips.count();
            
            console.log(`💬 Tooltips found: ${tooltipCount}`);
            
            if (tooltipCount > 0) {
                const tooltip = tooltips.first();
                const isVisible = await tooltip.isVisible();
                const hasShowClass = await tooltip.evaluate(el => el.classList.contains('show'));
                
                console.log(`✅ Tooltip ${i + 1} - Visible: ${isVisible}, HasShow: ${hasShowClass}`);
                
                if (isVisible) {
                    // Check tooltip content
                    const tooltipContent = await tooltip.evaluate(el => ({
                        hasHeader: !!el.querySelector('.tooltip-header'),
                        hasVehicleList: !!el.querySelector('.vehicle-list'),
                        vehicleCount: el.querySelectorAll('.vehicle-item').length
                    }));
                    
                    console.log(`📋 Tooltip Content:`, tooltipContent);
                }
            }
            
            // Move away to hide tooltip
            await page.mouse.move(0, 0);
            await page.waitForTimeout(500);
        }
    }
    
    // Take screenshot of final state
    await page.screenshot({ 
        path: `bos-tooltip-test-${Date.now()}.png`,
        fullPage: true 
    });
    
    console.log('📊 Total Console Messages:', logCount);
    console.log('✅ Tooltip test completed');
    
    expect(statusCheck.hasStatusWidget).toBe(true);
    expect(statusCheck.statusItemCount).toBeGreaterThan(0);
});