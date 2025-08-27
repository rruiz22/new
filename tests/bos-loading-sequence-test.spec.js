const { test, expect } = require('@playwright/test');

test('BOS Loading Sequence Test', async ({ page }) => {
    console.log('⚡ Testing BOS loading sequence...');
    
    // Navigate to page
    await page.goto(`http://localhost:8082/bos?sequence_test=${Date.now()}`);
    
    // Check initial loading state
    console.log('1️⃣ Checking initial loading state...');
    
    const initialState = await page.evaluate(() => {
        const statValues = Array.from(document.querySelectorAll('.stat-value')).map(el => ({
            id: el.id,
            hasLoadingDots: el.querySelector('.loading-dots') !== null,
            textContent: el.textContent.trim()
        }));
        
        const statusItems = document.querySelectorAll('.status-item');
        const statusState = Array.from(statusItems).map(item => ({
            hasLoadingDots: item.querySelector('.loading-dots') !== null,
            count: item.querySelector('.status-count')?.textContent?.trim() || 'none'
        }));
        
        return {
            statValues,
            statusState,
            statusItemCount: statusItems.length
        };
    });
    
    console.log('📊 Initial State:');
    console.log('Stats:', initialState.statValues);
    console.log('Status Items:', initialState.statusState);
    
    // Wait for data to load
    console.log('2️⃣ Waiting for data to load...');
    await page.waitForTimeout(8000);
    
    // Check final state
    const finalState = await page.evaluate(() => {
        const statValues = Array.from(document.querySelectorAll('.stat-value')).map(el => ({
            id: el.id,
            hasLoadingDots: el.querySelector('.loading-dots') !== null,
            textContent: el.textContent.trim(),
            hasNumber: !isNaN(parseInt(el.textContent))
        }));
        
        const statusItems = document.querySelectorAll('.status-item');
        const statusState = Array.from(statusItems).map((item, i) => ({
            index: i,
            hasIcon: item.querySelector('.status-icon i') !== null,
            iconClass: item.querySelector('.status-icon i')?.className || 'none',
            name: item.querySelector('.status-name')?.textContent || 'none',
            hasLoadingDots: item.querySelector('.loading-dots') !== null,
            count: item.querySelector('.status-count')?.textContent?.trim() || 'none',
            hasNumber: !isNaN(parseInt(item.querySelector('.status-count')?.textContent))
        }));
        
        return {
            statValues,
            statusState,
            statusItemCount: statusItems.length
        };
    });
    
    console.log('📊 Final State:');
    console.log('Stats:', finalState.statValues);
    console.log('Status Items:', finalState.statusState);
    
    // Test tooltip on first status item with data
    console.log('3️⃣ Testing tooltip functionality...');
    const statusItems = page.locator('.status-item');
    const itemCount = await statusItems.count();
    
    if (itemCount > 0) {
        console.log(`Found ${itemCount} status items, testing first one...`);
        
        const firstItem = statusItems.first();
        
        // Get position before hover
        const itemRect = await firstItem.boundingBox();
        console.log('📍 Status item position:', itemRect);
        
        // Hover on the item
        await firstItem.hover();
        await page.waitForTimeout(1000);
        
        // Check for tooltip
        const tooltips = page.locator('.vehicle-tooltip');
        const tooltipCount = await tooltips.count();
        console.log(`💬 Tooltips found: ${tooltipCount}`);
        
        if (tooltipCount > 0) {
            const tooltip = tooltips.first();
            const isVisible = await tooltip.isVisible();
            const hasShow = await tooltip.evaluate(el => el.classList.contains('show'));
            
            console.log('✅ Tooltip working:', { visible: isVisible, hasShow });
        }
    }
    
    // Final summary
    console.log('\\n📋 LOADING SEQUENCE SUMMARY:');
    console.log('============================');
    
    const hasLoadingDots = initialState.statValues.some(stat => stat.hasLoadingDots);
    const hasNumbers = finalState.statValues.some(stat => stat.hasNumber);
    const statusItemsWork = finalState.statusItemCount > 0;
    const iconsWork = finalState.statusState.some(item => item.hasIcon);
    
    console.log('✅ Initial Loading Dots:', hasLoadingDots);
    console.log('✅ Final Numbers Loaded:', hasNumbers);
    console.log('✅ Status Items Created:', statusItemsWork);
    console.log('✅ Icons Visible:', iconsWork);
    
    expect(finalState.statusItemCount).toBeGreaterThanOrEqual(5);
    console.log('🎉 Loading sequence test completed!');
});