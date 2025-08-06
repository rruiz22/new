/**
 * jQuery Easing Fix for ApexCharts Compatibility
 * Adds missing jQuery easing functions to prevent "ce.easing[this.easing] is not a function" errors
 * 
 * PROBLEM:
 * ApexCharts has its own internal easing system that can conflict with jQuery's easing functions.
 * When ApexCharts tries to access jQuery easing functions that don't exist, it throws errors like:
 * "ce.easing[this.easing] is not a function"
 * 
 * SOLUTION:
 * This script extends jQuery's easing object with commonly used easing functions and provides
 * a Proxy fallback for any missing functions. It should be loaded after jQuery but before ApexCharts.
 * 
 * SUPPORTED EASING FUNCTIONS:
 * - linear, swing (jQuery core)
 * - ease, ease-in, ease-out, ease-in-out (CSS-style)
 * - easeInQuad, easeOutQuad, easeInOutQuad (Quadratic)
 * - easeInCubic, easeOutCubic, easeInOutCubic (Cubic)
 * - And more via Proxy fallback
 */

(function($) {
    'use strict';
    
    // Ensure jQuery easing object exists
    $.easing = $.easing || {};
    
    // Add missing easing functions that ApexCharts might try to access
    $.extend($.easing, {
        // Linear easing (already exists in jQuery core, but ensuring it's available)
        linear: function(t) {
            return t;
        },
        
        // Swing easing (already exists in jQuery core, but ensuring it's available)
        swing: function(t) {
            return 0.5 - Math.cos(t * Math.PI) / 2;
        },
        
        // Additional easing functions that might be referenced
        ease: function(t) {
            return 0.5 - Math.cos(t * Math.PI) / 2;
        },
        
        'ease-in': function(t) {
            return t * t;
        },
        
        'ease-out': function(t) {
            return 1 - Math.pow(1 - t, 2);
        },
        
        'ease-in-out': function(t) {
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        },
        
        // Ensure easing functions that ApexCharts might reference internally
        easeInQuad: function(t) {
            return t * t;
        },
        
        easeOutQuad: function(t) {
            return 1 - Math.pow(1 - t, 2);
        },
        
        easeInOutQuad: function(t) {
            return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
        },
        
        easeInCubic: function(t) {
            return t * t * t;
        },
        
        easeOutCubic: function(t) {
            return 1 - Math.pow(1 - t, 3);
        },
        
        easeInOutCubic: function(t) {
            return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
        }
    });
    
    // Prevent errors by providing a fallback for any missing easing function
    var originalEasing = $.easing;
    $.easing = new Proxy(originalEasing, {
        get: function(target, property) {
            if (property in target) {
                return target[property];
            }
            
            // Fallback to linear easing for any missing function
            console.warn('jQuery easing function "' + property + '" not found. Using linear fallback.');
            return target.linear || function(t) { return t; };
        }
    });
    
})(jQuery);
