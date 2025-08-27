// @ts-check
const { test, expect } = require('@playwright/test');

test.describe('BOS Page Debug', () => {
    test('should check BOS page for errors', async ({ page }) => {
        console.log('🔍 Starting BOS debug check...');
        
        const errors = [];
        const logs = [];
        
        // Capture console messages
        page.on('console', msg => {
            const text = `[${msg.type()}] ${msg.text()}`;
            logs.push(text);
            if (msg.type() === 'error') {
                errors.push(text);
                console.log('❌ ERROR:', msg.text());
            } else {
                console.log('📝 LOG:', msg.text());
            }
        });
        
        // Capture page errors
        page.on('pageerror', error => {
            errors.push(`PAGE ERROR: ${error.message}`);
            console.log('💥 PAGE ERROR:', error.message);
        });
        
        // Navigate to BOS page
        await page.goto('http://localhost:8082/public/bos/');
        await page.waitForLoadState('domcontentloaded');
        await page.waitForTimeout(5000);
        
        // Check basic elements
        const elementsCheck = await page.evaluate(() => {
            return {
                hasTitle: document.title.includes('BOS'),
                hasNotionCSS: document.querySelector('link[href*="notion-enterprise"]') !== null,
                hasWidgets: document.querySelectorAll('.stat-widget').length,
                hasStatusWidget: document.querySelector('.status-overview-widget') !== null,
                hasTable: document.querySelector('#inventoryTable') !== null,
                hasSearch: document.querySelector('#inventorySearch') !== null,
                scriptsLoaded: {
                    jquery: typeof window.jQuery !== 'undefined',
                    dataTables: window.jQuery && typeof window.jQuery.fn.DataTable !== 'undefined',
                    inventoryTable: typeof window.inventoryTable !== 'undefined'
                }
            };
        });
        
        console.log('📊 Element Check:', JSON.stringify(elementsCheck, null, 2));
        console.log('📝 Total Logs:', logs.length);
        console.log('❌ Total Errors:', errors.length);
        
        if (errors.length > 0) {
            console.log('🔴 ERRORS FOUND:');
            errors.forEach((error, i) => console.log(`${i+1}. ${error}`));
        }
        
        // Test basic functionality
        if (elementsCheck.hasSearch) {
            console.log('🔍 Testing search...');
            await page.fill('#inventorySearch', 'BMW');
            await page.waitForTimeout(1000);
        }
        
        if (elementsCheck.hasWidgets >= 5) {
            console.log('📊 Testing widget click...');
            await page.click('.stat-widget:first-child');
            await page.waitForTimeout(500);
        }
        
        console.log('✅ BOS debug check completed');
        
        // Basic assertions
        expect(elementsCheck.hasTitle).toBe(true);
        expect(elementsCheck.hasWidgets).toBeGreaterThanOrEqual(5);
        expect(elementsCheck.hasTable).toBe(true);
    });
});