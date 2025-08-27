const { test, expect } = require('@playwright/test');

test('BOS Synchronized Widget Loading', async ({ page }) => {
    console.log('⚡ Testing synchronized widget loading...');
    
    // Navigate to page
    await page.goto(`http://localhost:8082/bos?sync_test=${Date.now()}`);
    
    // Check initial state - should have loading dots
    console.log('1️⃣ Checking initial loading state...');
    await page.waitForTimeout(1000);
    
    const initialState = await page.evaluate(() => {
        const mainWidgets = Array.from(document.querySelectorAll('.stats-grid .stat-value')).map(el => ({
            id: el.id,
            hasLoadingDots: el.querySelector('.loading-dots') !== null,
            content: el.textContent.trim()
        }));
        
        const statusWidgets = Array.from(document.querySelectorAll('.status-item .status-count')).map(el => ({
            hasLoadingDots: el.querySelector('.loading-dots') !== null,
            content: el.textContent.trim()
        }));
        
        return {
            mainWidgets,
            statusWidgets,
            statusItemCount: document.querySelectorAll('.status-item').length
        };
    });
    
    console.log('📊 Initial State:');
    console.log('Main Widgets Loading:', initialState.mainWidgets.map(w => ({ id: w.id, loading: w.hasLoadingDots })));
    console.log('Status Widgets Loading:', initialState.statusWidgets.map((w, i) => ({ index: i, loading: w.hasLoadingDots })));
    console.log('Status Item Count:', initialState.statusItemCount);
    
    // Wait for data to load (same timing as main widgets)
    console.log('2️⃣ Waiting for synchronized loading...');
    await page.waitForTimeout(3000); // Wait for 1s delay + data processing
    
    // Check final state - should have real numbers
    const finalState = await page.evaluate(() => {
        const mainWidgets = Array.from(document.querySelectorAll('.stats-grid .stat-value')).map(el => ({
            id: el.id,
            hasLoadingDots: el.querySelector('.loading-dots') !== null,
            content: el.textContent.trim(),
            hasNumber: !isNaN(parseInt(el.textContent))
        }));
        
        const statusWidgets = Array.from(document.querySelectorAll('.status-item')).map((item, i) => ({
            index: i,
            name: item.querySelector('.status-name')?.textContent || 'unknown',
            hasIcon: item.querySelector('.status-icon i') !== null,
            iconClass: item.querySelector('.status-icon i')?.className || 'none',
            hasLoadingDots: item.querySelector('.status-count .loading-dots') !== null,
            count: item.querySelector('.status-count')?.textContent?.trim() || 'none',
            hasNumber: !isNaN(parseInt(item.querySelector('.status-count')?.textContent))
        }));
        
        return {
            mainWidgets,
            statusWidgets
        };
    });
    
    console.log('📊 Final State:');
    console.log('Main Widgets:', finalState.mainWidgets.map(w => ({ 
        id: w.id, 
        hasNumber: w.hasNumber, 
        loading: w.hasLoadingDots,
        content: w.content 
    })));
    console.log('Status Widgets:', finalState.statusWidgets.map(w => ({ 
        name: w.name, 
        hasIcon: w.hasIcon,
        iconClass: w.iconClass,
        hasNumber: w.hasNumber, 
        loading: w.hasLoadingDots,
        count: w.count 
    })));
    
    // Test tooltip functionality
    console.log('3️⃣ Testing tooltip on first status widget...');
    const statusItems = page.locator('.status-item');
    const itemCount = await statusItems.count();
    
    if (itemCount > 0) {
        const firstItem = statusItems.first();
        await firstItem.hover();
        await page.waitForTimeout(500);
        
        const tooltipVisible = await page.locator('.vehicle-tooltip.show').count() > 0;
        console.log('💬 Tooltip appears on hover:', tooltipVisible);
    }
    
    // Summary
    console.log('\\n📋 SYNCHRONIZED LOADING SUMMARY:');
    console.log('=================================');
    
    const mainWidgetsLoaded = finalState.mainWidgets.filter(w => w.hasNumber).length;
    const statusWidgetsLoaded = finalState.statusWidgets.filter(w => w.hasNumber).length;
    const allIconsVisible = finalState.statusWidgets.filter(w => w.hasIcon).length;
    
    console.log(`✅ Main Widgets Loaded: ${mainWidgetsLoaded}/5`);
    console.log(`✅ Status Widgets Loaded: ${statusWidgetsLoaded}/${itemCount}`);
    console.log(`✅ Status Icons Visible: ${allIconsVisible}/${itemCount}`);
    
    expect(mainWidgetsLoaded).toBe(5);
    expect(itemCount).toBe(5);
    expect(allIconsVisible).toBe(5);
    
    console.log('🎉 Synchronized loading test PASSED!');
});