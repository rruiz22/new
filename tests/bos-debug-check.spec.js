// @ts-check
const { test, expect } = require('@playwright/test');

/**
 * BOS Page Debug and Console Logs Check
 * Review the page and capture all console logs and errors
 */

test.describe('BOS Page Debug Check', () => {
    test('should load BOS page and capture all console logs and errors', async ({ page }) => {
        console.log('🔍 Starting BOS page debug check...');
        
        // Capture all console logs
        const consoleLogs = [];
        const consoleErrors = [];
        const networkErrors = [];
        
        // Listen to console events
        page.on('console', msg => {
            const logEntry = `[${msg.type().toUpperCase()}] ${msg.text()}`;
            consoleLogs.push(logEntry);
            
            if (msg.type() === 'error') {
                consoleErrors.push(logEntry);
                console.log('🔴 [CONSOLE ERROR]', msg.text());
            } else if (msg.type() === 'warning') {
                console.log('🟡 [CONSOLE WARNING]', msg.text());
            } else if (msg.type() === 'log') {
                console.log('🖥️ [CONSOLE LOG]', msg.text());
            }
        });
        
        // Listen to page errors
        page.on('pageerror', error => {
            consoleErrors.push(`[PAGE ERROR] ${error.message}`);
            console.log('❌ [PAGE ERROR]', error.message);
        });
        
        // Listen to failed requests
        page.on('requestfailed', request => {
            const errorInfo = `${request.failure()?.errorText} - ${request.url()}`;
            networkErrors.push(errorInfo);
            console.log('🔴 [HTTP ERROR]', request.failure()?.errorText, '-', request.url());
        });
        
        // Navigate to BOS page
        console.log('🌐 Navigating to BOS page...');
        await page.goto('http://localhost:8082/public/bos/');
        
        // Wait for page to load completely
        await page.waitForLoadState('domcontentloaded');
        
        // Wait for any dynamic content to load
        await page.waitForTimeout(5000);
        
        // Check if basic elements are present
        console.log('🔍 Checking page elements...');
        
        // Check page title
        const title = await page.title();
        console.log('📄 Page Title:', title);
        
        // Check if Notion CSS is loaded
        const notionCSSLoaded = await page.evaluate(() => {
            const links = document.querySelectorAll('link[href*="bos-notion-enterprise.css"]');
            return links.length > 0;
        });
        console.log('🎨 Notion CSS Loaded:', notionCSSLoaded);
        
        // Check if JavaScript files are loaded
        const scriptsLoaded = await page.evaluate(() => {
            return {
                jquery: typeof window.jQuery !== 'undefined',
                dataTables: window.jQuery && typeof window.jQuery.fn.DataTable !== 'undefined',
                notionUI: typeof window.NotionEnterpriseUI !== 'undefined',
                statusWidget: typeof window.StatusOverviewWidget !== 'undefined',
                inventoryTable: typeof window.inventoryTable !== 'undefined'\n            };\n        });\n        console.log('📜 Scripts Loaded:', JSON.stringify(scriptsLoaded, null, 2));\n        \n        // Check authentication status\n        const authStatus = await page.evaluate(() => {\n            return {\n                isAuthenticated: window.isAuthenticated,\n                userType: window.userType,\n                authCheckCompleted: window.authCheckCompleted\n            };\n        });\n        console.log('🔐 Auth Status:', JSON.stringify(authStatus, null, 2));\n        \n        // Check if widgets are visible\n        const widgetsVisible = await page.locator('.stats-grid .stat-widget').count();\n        console.log('📊 Widgets Count:', widgetsVisible);\n        \n        // Check if status overview widget is present\n        const statusWidgetVisible = await page.locator('.status-overview-widget').isVisible();\n        console.log('📈 Status Widget Visible:', statusWidgetVisible);\n        \n        // Check if table is present\n        const tableVisible = await page.locator('#inventoryTable').isVisible();\n        console.log('📋 Table Visible:', tableVisible);\n        \n        // Check table data loading\n        await page.waitForTimeout(3000); // Wait for data to load\n        \n        const tableData = await page.evaluate(() => {\n            if (window.inventoryTable) {\n                try {\n                    const data = window.inventoryTable.data();\n                    return {\n                        hasData: data && data.length > 0,\n                        rowCount: data ? data.length : 0,\n                        isInitialized: window.inventoryTable.settings && window.inventoryTable.settings().length > 0\n                    };\n                } catch (e) {\n                    return { error: e.message };\n                }\n            }\n            return { error: 'Table not initialized' };\n        });\n        console.log('📊 Table Data:', JSON.stringify(tableData, null, 2));\n        \n        // Check order info lookup\n        const orderInfoStatus = await page.evaluate(() => {\n            return {\n                hasOrderInfo: typeof window.orderInfoLookup === 'object',\n                orderCount: window.orderInfoLookup ? Object.keys(window.orderInfoLookup).length : 0\n            };\n        });\n        console.log('📋 Order Info:', JSON.stringify(orderInfoStatus, null, 2));\n        \n        // Test widget interactions\n        console.log('🖱️ Testing widget interactions...');\n        const firstWidget = page.locator('.stat-widget').first();\n        if (await firstWidget.isVisible()) {\n            await firstWidget.click();\n            await page.waitForTimeout(500);\n            \n            const isActive = await firstWidget.evaluate(el => el.classList.contains('active'));\n            console.log('🎯 Widget Active After Click:', isActive);\n        }\n        \n        // Test search functionality\n        console.log('🔍 Testing search functionality...');\n        const searchInput = page.locator('#inventorySearch');\n        if (await searchInput.isVisible()) {\n            await searchInput.fill('BMW');\n            await page.waitForTimeout(1000);\n            \n            const searchValue = await searchInput.inputValue();\n            console.log('🔍 Search Value:', searchValue);\n        }\n        \n        // Summary\n        console.log('\\n📊 DEBUG SUMMARY:');\n        console.log('=================');\n        console.log('Console Logs:', consoleLogs.length);\n        console.log('Console Errors:', consoleErrors.length);\n        console.log('Network Errors:', networkErrors.length);\n        console.log('Page Elements Working:', {\n            widgets: widgetsVisible === 5,\n            statusWidget: statusWidgetVisible,\n            table: tableVisible,\n            search: await searchInput.isVisible()\n        });\n        \n        // Print first few errors if any\n        if (consoleErrors.length > 0) {\n            console.log('\\n🔴 CONSOLE ERRORS:');\n            consoleErrors.slice(0, 5).forEach((error, i) => {\n                console.log(`${i + 1}. ${error}`);\n            });\n        }\n        \n        if (networkErrors.length > 0) {\n            console.log('\\n🌐 NETWORK ERRORS:');\n            networkErrors.slice(0, 5).forEach((error, i) => {\n                console.log(`${i + 1}. ${error}`);\n            });\n        }\n        \n        // Assertions for critical functionality\n        expect(notionCSSLoaded).toBe(true);\n        expect(scriptsLoaded.jquery).toBe(true);\n        expect(widgetsVisible).toBe(5);\n        expect(tableVisible).toBe(true);\n        \n        console.log('✅ Debug check completed!');\n    });\n    \n    test('should test status widget hover functionality', async ({ page }) => {\n        console.log('🖱️ Testing status widget hover...');\n        \n        await page.goto('http://localhost:8082/public/bos/');\n        await page.waitForLoadState('domcontentloaded');\n        await page.waitForTimeout(5000); // Wait for data\n        \n        // Check if status widget exists\n        const statusWidget = page.locator('.status-overview-widget');\n        if (await statusWidget.isVisible()) {\n            console.log('✅ Status widget is visible');\n            \n            // Check status items\n            const statusItems = page.locator('.status-item');\n            const itemCount = await statusItems.count();\n            console.log('📊 Status Items Count:', itemCount);\n            \n            if (itemCount > 0) {\n                // Test hover on first status item\n                const firstStatusItem = statusItems.first();\n                await firstStatusItem.hover();\n                await page.waitForTimeout(500);\n                \n                // Check if tooltip appears\n                const tooltip = page.locator('.vehicle-tooltip.show');\n                const tooltipVisible = await tooltip.isVisible();\n                console.log('💬 Tooltip Visible on Hover:', tooltipVisible);\n                \n                if (tooltipVisible) {\n                    const vehicleItems = tooltip.locator('.vehicle-item');\n                    const vehicleCount = await vehicleItems.count();\n                    console.log('🚗 Vehicles in Tooltip:', vehicleCount);\n                }\n            }\n        } else {\n            console.log('❌ Status widget not visible');\n        }\n    });\n});"