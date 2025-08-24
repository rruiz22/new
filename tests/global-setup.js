// Global setup for Playwright tests
async function globalSetup(config) {
  console.log('🚀 Setting up Playwright tests...');
  
  // You can add any global setup here
  // For example: starting a test database, clearing cache, etc.
  
  return async () => {
    console.log('🧹 Cleaning up after tests...');
    // Global teardown code here
  };
}

module.exports = globalSetup;