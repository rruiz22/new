// Console logging helper for Playwright tests
class ConsoleHelper {
  constructor() {
    this.consoleLogs = [];
    this.consoleErrors = [];
    this.consoleWarnings = [];
    this.pageErrors = [];
    this.httpErrors = [];
    this.requestFailures = [];
  }

  // Setup all console listeners for a page
  setupConsoleListeners(page) {
    // Listen to all console events
    page.on('console', msg => {
      const type = msg.type();
      const text = msg.text();
      const location = msg.location();
      
      const logEntry = { type, text, location, timestamp: new Date().toISOString() };
      
      console.log(`🖥️ [CONSOLE ${type.toUpperCase()}] ${text}`);
      if (location.url) {
        console.log(`   📍 at ${location.url}:${location.lineNumber}:${location.columnNumber}`);
      }
      
      // Store logs by type
      if (type === 'error') {
        this.consoleErrors.push(logEntry);
      } else if (type === 'warning') {
        this.consoleWarnings.push(logEntry);
      } else {
        this.consoleLogs.push(logEntry);
      }
    });
    
    // Listen to page errors (uncaught exceptions)
    page.on('pageerror', error => {
      const errorEntry = {
        message: error.message,
        stack: error.stack,
        timestamp: new Date().toISOString()
      };
      
      console.log(`❌ [PAGE ERROR] ${error.message}`);
      console.log(`   📍 ${error.stack}`);
      
      this.pageErrors.push(errorEntry);
    });
    
    // Listen to response errors
    page.on('response', response => {
      if (response.status() >= 400) {
        const errorEntry = {
          status: response.status(),
          statusText: response.statusText(),
          url: response.url(),
          timestamp: new Date().toISOString()
        };
        
        console.log(`🔴 [HTTP ERROR] ${response.status()} ${response.statusText()} - ${response.url()}`);
        
        this.httpErrors.push(errorEntry);
      }
    });
    
    // Listen to request failures
    page.on('requestfailed', request => {
      const failureEntry = {
        method: request.method(),
        url: request.url(),
        failure: request.failure()?.errorText,
        timestamp: new Date().toISOString()
      };
      
      console.log(`🚫 [REQUEST FAILED] ${request.method()} ${request.url()}`);
      console.log(`   📍 Failure: ${request.failure()?.errorText}`);
      
      this.requestFailures.push(failureEntry);
    });
  }

  // Print summary of all captured logs
  printSummary() {
    console.log(`\n📊 Console Summary:`);
    console.log(`   📝 Total logs: ${this.consoleLogs.length}`);
    console.log(`   ⚠️ Warnings: ${this.consoleWarnings.length}`);
    console.log(`   ❌ Console Errors: ${this.consoleErrors.length}`);
    console.log(`   🚨 Page Errors: ${this.pageErrors.length}`);
    console.log(`   🔴 HTTP Errors: ${this.httpErrors.length}`);
    console.log(`   🚫 Request Failures: ${this.requestFailures.length}`);
  }

  // Get all errors combined
  getAllErrors() {
    return {
      consoleErrors: this.consoleErrors,
      pageErrors: this.pageErrors,
      httpErrors: this.httpErrors,
      requestFailures: this.requestFailures
    };
  }

  // Check if there are any errors
  hasErrors() {
    return this.consoleErrors.length > 0 || 
           this.pageErrors.length > 0 || 
           this.httpErrors.length > 0 || 
           this.requestFailures.length > 0;
  }

  // Reset all logs (useful for multiple tests)
  reset() {
    this.consoleLogs = [];
    this.consoleErrors = [];
    this.consoleWarnings = [];
    this.pageErrors = [];
    this.httpErrors = [];
    this.requestFailures = [];
  }

  // Export logs to JSON for detailed analysis
  exportLogs() {
    return {
      consoleLogs: this.consoleLogs,
      consoleErrors: this.consoleErrors,
      consoleWarnings: this.consoleWarnings,
      pageErrors: this.pageErrors,
      httpErrors: this.httpErrors,
      requestFailures: this.requestFailures,
      summary: {
        totalLogs: this.consoleLogs.length,
        warnings: this.consoleWarnings.length,
        consoleErrors: this.consoleErrors.length,
        pageErrors: this.pageErrors.length,
        httpErrors: this.httpErrors.length,
        requestFailures: this.requestFailures.length,
        hasErrors: this.hasErrors()
      }
    };
  }
}

module.exports = ConsoleHelper;