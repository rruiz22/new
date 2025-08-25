// @ts-check
const { test, expect } = require('@playwright/test');

// Test configuration
const BOS_URL = 'http://localhost/mda_nuevo/public/bos/index_improved.php';

test.describe('BOS UI Design Validation', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto(BOS_URL);
    await page.waitForLoadState('networkidle');
  });

  test('should have light background colors only', async ({ page }) => {
    // Check body background
    const bodyBg = await page.evaluate(() => {
      return window.getComputedStyle(document.body).backgroundColor;
    });
    
    // Should be light grey (#F8F9FA)
    expect(bodyBg).toMatch(/rgb\(248,\s*249,\s*250\)/);
    
    // Check header is white/light
    const headerBg = await page.locator('.bmw-header').evaluate(el => {
      return window.getComputedStyle(el).backgroundColor;
    });
    
    // Should be white or very light
    expect(headerBg).toMatch(/rgb\(255,\s*255,\s*255\)|rgb\(248,\s*249,\s*250\)/);
  });

  test('should NOT have gradients', async ({ page }) => {
    // Check all elements for gradients
    const elementsWithGradients = await page.evaluate(() => {
      const elements = document.querySelectorAll('*');
      const gradientElements = [];
      
      elements.forEach(el => {
        const styles = window.getComputedStyle(el);
        const bg = styles.backgroundImage;
        
        if (bg && bg.includes('gradient')) {
          gradientElements.push({
            tag: el.tagName,
            class: el.className,
            background: bg
          });
        }
      });
      
      return gradientElements;
    });
    
    // Should have NO gradients
    expect(elementsWithGradients).toHaveLength(0);
  });

  test('should have subtle animations only', async ({ page }) => {
    // Check transition durations
    const transitions = await page.evaluate(() => {
      const elements = document.querySelectorAll('*');
      const longTransitions = [];
      
      elements.forEach(el => {
        const styles = window.getComputedStyle(el);
        const transition = styles.transition;
        
        if (transition && transition !== 'none') {
          // Extract duration
          const match = transition.match(/(\d+)ms/);
          if (match && parseInt(match[1]) > 300) {
            longTransitions.push({
              element: el.tagName + '.' + el.className,
              duration: match[1] + 'ms'
            });
          }
        }
      });
      
      return longTransitions;
    });
    
    // Should have no transitions longer than 300ms
    expect(transitions).toHaveLength(0);
  });

  test('should have clean cards without glass effects', async ({ page }) => {
    // Check for glass/blur effects
    const glassEffects = await page.evaluate(() => {
      const cards = document.querySelectorAll('.clean-card, .stat-card');
      const glassElements = [];
      
      cards.forEach(card => {
        const styles = window.getComputedStyle(card);
        
        if (styles.backdropFilter !== 'none' || 
            styles.webkitBackdropFilter !== 'none' ||
            styles.background.includes('rgba') && styles.background.includes('0.')) {
          glassElements.push({
            class: card.className,
            backdrop: styles.backdropFilter
          });
        }
      });
      
      return glassElements;
    });
    
    // Should have no glass effects
    expect(glassEffects).toHaveLength(0);
  });

  test('should display all statistic cards', async ({ page }) => {
    // Check stat cards are visible
    const statCards = page.locator('.stat-card');
    await expect(statCards).toHaveCount(4);
    
    // Check each card has proper structure
    for (let i = 0; i < 4; i++) {
      const card = statCards.nth(i);
      await expect(card).toBeVisible();
      
      // Check for required elements
      await expect(card.locator('.stat-icon')).toBeVisible();
      await expect(card.locator('.stat-number')).toBeVisible();
      await expect(card.locator('.stat-label')).toBeVisible();
      await expect(card.locator('.stat-progress')).toBeVisible();
    }
  });

  test('should have BMW blue as accent color only', async ({ page }) => {
    // Check BMW blue usage
    const blueElements = await page.evaluate(() => {
      const elements = document.querySelectorAll('*');
      const bmwBlue = 'rgb(0, 102, 204)'; // #0066CC
      const blueUsage = [];
      
      elements.forEach(el => {
        const styles = window.getComputedStyle(el);
        
        // Check if used as background (should be minimal)
        if (styles.backgroundColor === bmwBlue) {
          blueUsage.push({
            element: el.tagName + '.' + el.className,
            usage: 'background'
          });
        }
        
        // Check if used as text color (acceptable)
        if (styles.color === bmwBlue) {
          blueUsage.push({
            element: el.tagName + '.' + el.className,
            usage: 'text'
          });
        }
      });
      
      return blueUsage;
    });
    
    // Blue should be used sparingly
    const bgBlueElements = blueElements.filter(el => el.usage === 'background');
    expect(bgBlueElements.length).toBeLessThan(5); // Minimal background usage
  });

  test('should have proper table styling', async ({ page }) => {
    // Check table exists
    const table = page.locator('#inventoryTable');
    await expect(table).toBeVisible();
    
    // Check table has clean styling
    const tableBg = await table.evaluate(el => {
      return window.getComputedStyle(el).backgroundColor;
    });
    
    // Table should have white/light background
    expect(tableBg).toMatch(/rgb\(255,\s*255,\s*255\)|transparent/);
  });

  test('should have clean header without dark colors', async ({ page }) => {
    const header = page.locator('.bmw-header');
    
    // Check header background
    const headerStyles = await header.evaluate(el => {
      const styles = window.getComputedStyle(el);
      return {
        background: styles.backgroundColor,
        color: styles.color
      };
    });
    
    // Should NOT be dark
    expect(headerStyles.background).not.toMatch(/rgb\(0,\s*0,\s*0\)|rgb\(33,\s*37,\s*41\)/);
    
    // Text should be dark on light background
    expect(headerStyles.color).toMatch(/rgb\(3[0-9],|rgb\(2[0-9],|rgb\(1[0-9],|rgb\([0-9],/);
  });

  test('should have functional search bar', async ({ page }) => {
    const searchBar = page.locator('#globalSearch');
    await expect(searchBar).toBeVisible();
    
    // Test search functionality
    await searchBar.fill('BMW');
    await searchBar.press('Enter');
    
    // Search should not break the page
    await expect(page).toHaveURL(/.*index_improved\.php/);
  });

  test('should have responsive design for mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check cards stack properly
    const cards = await page.locator('.stat-card').boundingBox();
    
    // Header should still be visible
    await expect(page.locator('.bmw-header')).toBeVisible();
    
    // Check for horizontal scroll (should not have)
    const hasHorizontalScroll = await page.evaluate(() => {
      return document.documentElement.scrollWidth > document.documentElement.clientWidth;
    });
    
    expect(hasHorizontalScroll).toBe(false);
  });

  test('should load charts without errors', async ({ page }) => {
    // Wait for charts to initialize
    await page.waitForTimeout(1000);
    
    // Check for chart canvases
    const trendChart = page.locator('#inventoryTrendChart');
    const statusChart = page.locator('#statusChart');
    
    await expect(trendChart).toBeVisible();
    await expect(statusChart).toBeVisible();
    
    // Check no console errors
    const consoleErrors = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });
    
    await page.waitForTimeout(500);
    expect(consoleErrors).toHaveLength(0);
  });

  test('performance: page should load quickly', async ({ page }) => {
    const startTime = Date.now();
    
    await page.goto(BOS_URL);
    await page.waitForLoadState('domcontentloaded');
    
    const loadTime = Date.now() - startTime;
    
    // Page should load in under 3 seconds
    expect(loadTime).toBeLessThan(3000);
  });

  test('accessibility: should have proper contrast ratios', async ({ page }) => {
    // Check text contrast
    const contrastIssues = await page.evaluate(() => {
      const issues = [];
      const elements = document.querySelectorAll('*');
      
      elements.forEach(el => {
        const styles = window.getComputedStyle(el);
        const bg = styles.backgroundColor;
        const color = styles.color;
        
        // Simple check for very low contrast
        if (bg === color) {
          issues.push({
            element: el.tagName,
            issue: 'Same background and text color'
          });
        }
      });
      
      return issues;
    });
    
    expect(contrastIssues).toHaveLength(0);
  });

});

test.describe('Visual Regression Tests', () => {
  
  test('full page screenshot', async ({ page }) => {
    await page.goto(BOS_URL);
    await page.waitForLoadState('networkidle');
    
    // Take screenshot for visual comparison
    await expect(page).toHaveScreenshot('bos-full-page.png', {
      fullPage: true,
      animations: 'disabled'
    });
  });
  
  test('header screenshot', async ({ page }) => {
    await page.goto(BOS_URL);
    await page.waitForLoadState('networkidle');
    
    const header = page.locator('.bmw-header');
    await expect(header).toHaveScreenshot('bos-header.png');
  });
  
  test('statistics cards screenshot', async ({ page }) => {
    await page.goto(BOS_URL);
    await page.waitForLoadState('networkidle');
    
    const statsSection = page.locator('.row').first();
    await expect(statsSection).toHaveScreenshot('bos-stats-cards.png');
  });
});