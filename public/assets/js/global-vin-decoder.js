/**
 * ============================================================================
 * GLOBAL VIN DECODER - Reusable across all modules
 * Advanced VIN decoding with caching, validation, and multiple API support
 * ============================================================================
 */

class GlobalVINDecoder {
    constructor(config = {}) {
        this.config = {
            // API endpoints in priority order
            apiEndpoints: [
                'https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/',
                '/api/vin/decode/' // Local fallback
            ],
            // Cache configuration
            cache: {
                prefix: 'vin_cache_',
                duration: 7 * 24 * 60 * 60 * 1000, // 7 days in milliseconds
                maxSize: 100 // Maximum cached VINs
            },
            // Rate limiting
            rateLimit: {
                requests: 10,
                perMinutes: 1
            },
            // Timeouts
            timeout: 10000, // 10 seconds
            ...config
        };

        this.cache = new Map();
        this.requestHistory = [];
        this.eventListeners = new Map();
        
        this.init();
    }

    init() {
        this.loadCacheFromLocalStorage();
        this.cleanupOldCache();
        console.log('GlobalVINDecoder: Initialized with cache size:', this.cache.size);
    }

    // ========================================
    // PUBLIC API METHODS
    // ========================================

    /**
     * Decode a VIN number
     * @param {string} vin - The VIN to decode
     * @returns {Promise<Object>} Decoded vehicle information
     */
    async decode(vin) {
        // Validate VIN format
        const validation = this.validateVIN(vin);
        if (!validation.isValid) {
            throw new Error(validation.error);
        }

        // Check cache first
        const cached = this.getFromCache(vin);
        if (cached) {
            this.emit('cached', { vin, data: cached });
            return cached;
        }

        // Check rate limiting
        if (!this.checkRateLimit()) {
            throw new Error('Rate limit exceeded. Please wait before making more requests.');
        }

        // Decode VIN
        this.emit('decoding', { vin });
        
        try {
            const data = await this.fetchVINData(vin);
            const processed = this.processVINData(data);
            
            // Cache the result
            this.saveToCache(vin, processed);
            
            this.emit('decoded', { vin, data: processed });
            return processed;
            
        } catch (error) {
            console.error('VIN decode error:', error);
            this.emit('error', { vin, error: error.message });
            
            // Try local fallback
            const localData = this.decodeVINLocal(vin);
            if (localData.year || localData.make) {
                this.emit('fallback', { vin, data: localData });
                return localData;
            }
            
            throw error;
        }
    }

    /**
     * Validate a VIN number
     * @param {string} vin - The VIN to validate
     * @returns {Object} Validation result
     */
    validateVIN(vin) {
        if (!vin || typeof vin !== 'string') {
            return { isValid: false, error: 'VIN must be a string' };
        }

        // Remove whitespace and convert to uppercase
        vin = vin.replace(/\s/g, '').toUpperCase();

        // Check length
        if (vin.length !== 17) {
            return { isValid: false, error: 'VIN must be exactly 17 characters' };
        }

        // Check for invalid characters
        if (/[IOQ]/.test(vin)) {
            return { isValid: false, error: 'VIN cannot contain letters I, O, or Q' };
        }

        // Check for valid characters only
        if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
            return { isValid: false, error: 'VIN contains invalid characters' };
        }

        // Validate check digit (position 9)
        if (!this.validateCheckDigit(vin)) {
            return { isValid: false, error: 'VIN has invalid check digit' };
        }

        // Check for suspicious patterns
        const suspiciousPatterns = [
            /(.)\1{4,}/, // 5 or more consecutive identical characters
            /^00000/, // Starts with multiple zeros
            /11111|22222|33333|44444|55555|66666|77777|88888|99999/, // Repeated digits
        ];

        for (const pattern of suspiciousPatterns) {
            if (pattern.test(vin)) {
                return { isValid: false, error: 'VIN contains suspicious patterns' };
            }
        }

        return { isValid: true, vin: vin };
    }

    /**
     * Get cached VIN data
     * @param {string} vin - The VIN to look up
     * @returns {Object|null} Cached data or null
     */
    getCached(vin) {
        return this.getFromCache(vin);
    }

    /**
     * Clear all cached data
     */
    clearCache() {
        this.cache.clear();
        this.clearLocalStorageCache();
        this.emit('cache-cleared');
    }

    /**
     * Get cache statistics
     * @returns {Object} Cache statistics
     */
    getCacheStats() {
        return {
            size: this.cache.size,
            maxSize: this.config.cache.maxSize,
            hitRate: this.calculateHitRate()
        };
    }

    // ========================================
    // EVENT SYSTEM
    // ========================================

    /**
     * Add event listener
     * @param {string} event - Event name
     * @param {Function} callback - Event callback
     */
    on(event, callback) {
        if (!this.eventListeners.has(event)) {
            this.eventListeners.set(event, []);
        }
        this.eventListeners.get(event).push(callback);
    }

    /**
     * Remove event listener
     * @param {string} event - Event name
     * @param {Function} callback - Event callback
     */
    off(event, callback) {
        if (this.eventListeners.has(event)) {
            const callbacks = this.eventListeners.get(event);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    }

    /**
     * Emit event
     * @param {string} event - Event name
     * @param {Object} data - Event data
     */
    emit(event, data) {
        if (this.eventListeners.has(event)) {
            this.eventListeners.get(event).forEach(callback => {
                try {
                    callback(data);
                } catch (error) {
                    console.error(`Error in VIN decoder event listener for ${event}:`, error);
                }
            });
        }
    }

    // ========================================
    // PRIVATE METHODS
    // ========================================

    async fetchVINData(vin) {
        const errors = [];
        
        for (const endpoint of this.config.apiEndpoints) {
            try {
                const url = endpoint.includes('http') ? 
                    `${endpoint}${vin}?format=json` : 
                    `${window.base_url || '/'}${endpoint}${vin}`;

                const response = await this.fetchWithTimeout(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                // Validate response
                if (this.isValidAPIResponse(data)) {
                    return data;
                } else {
                    throw new Error('Invalid API response format');
                }

            } catch (error) {
                errors.push(`${endpoint}: ${error.message}`);
                continue;
            }
        }

        throw new Error(`All API endpoints failed: ${errors.join(', ')}`);
    }

    async fetchWithTimeout(url) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), this.config.timeout);

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'User-Agent': 'VIN-Decoder/1.0',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            clearTimeout(timeoutId);
            return response;
        } catch (error) {
            clearTimeout(timeoutId);
            throw error;
        }
    }

    isValidAPIResponse(data) {
        // NHTSA API format
        if (data && data.Results && Array.isArray(data.Results) && data.Results.length > 0) {
            return true;
        }
        
        // Local API format
        if (data && (data.vehicle || data.year || data.make)) {
            return true;
        }
        
        return false;
    }

    processVINData(apiData) {
        // Handle NHTSA API response
        if (apiData.Results && apiData.Results[0]) {
            const result = apiData.Results[0];
            return this.buildVehicleInfo(result);
        }
        
        // Handle local API response
        if (apiData.vehicle) {
            return apiData;
        }
        
        return apiData;
    }

    buildVehicleInfo(nhtsa_data) {
        const parts = [];
        
        // Year
        if (nhtsa_data.ModelYear && nhtsa_data.ModelYear !== 'null') {
            parts.push(nhtsa_data.ModelYear);
        }
        
        // Make
        if (nhtsa_data.Make && nhtsa_data.Make !== 'null') {
            parts.push(nhtsa_data.Make.toUpperCase());
        }
        
        // Model
        if (nhtsa_data.Model && nhtsa_data.Model !== 'null') {
            parts.push(nhtsa_data.Model);
        }
        
        // Trim/Series
        const trim = nhtsa_data.Trim || nhtsa_data.Series;
        if (trim && trim !== 'null' && trim !== 'Not Available') {
            parts.push(`(${trim})`);
        }
        
        // Engine info
        if (nhtsa_data.EngineNumberOfCylinders && nhtsa_data.EngineNumberOfCylinders !== 'null') {
            parts.push(`(${nhtsa_data.EngineNumberOfCylinders} cyl)`);
        }

        const vehicle = parts.join(' ').trim();
        
        return {
            vehicle: vehicle,
            year: nhtsa_data.ModelYear,
            make: nhtsa_data.Make,
            model: nhtsa_data.Model,
            trim: trim,
            engine: nhtsa_data.EngineNumberOfCylinders,
            bodyType: nhtsa_data.BodyClass,
            driveType: nhtsa_data.DriveType,
            fuelType: nhtsa_data.FuelTypePrimary,
            transmission: nhtsa_data.TransmissionStyle,
            manufacturer: nhtsa_data.Manufacturer,
            plantCountry: nhtsa_data.PlantCountry,
            vehicleType: nhtsa_data.VehicleType,
            raw: nhtsa_data
        };
    }

    decodeVINLocal(vin) {
        // Basic local VIN decoder as fallback
        const vinInfo = {
            year: this.getModelYear(vin),
            make: this.getManufacturer(vin),
            vehicle: null
        };

        // Build basic vehicle string
        const parts = [];
        if (vinInfo.year) parts.push(vinInfo.year);
        if (vinInfo.make) parts.push(vinInfo.make);
        
        vinInfo.vehicle = parts.join(' ');
        
        return vinInfo;
    }

    getModelYear(vin) {
        const yearCode = vin.charAt(9);
        const yearMap = {
            'A': 2010, 'B': 2011, 'C': 2012, 'D': 2013, 'E': 2014,
            'F': 2015, 'G': 2016, 'H': 2017, 'J': 2018, 'K': 2019,
            'L': 2020, 'M': 2021, 'N': 2022, 'P': 2023, 'R': 2024,
            'S': 2025, 'T': 2026, 'V': 2027, 'W': 2028, 'X': 2029,
            'Y': 2030, '1': 2001, '2': 2002, '3': 2003, '4': 2004,
            '5': 2005, '6': 2006, '7': 2007, '8': 2008, '9': 2009
        };
        
        return yearMap[yearCode] || null;
    }

    getManufacturer(vin) {
        const wmi = vin.substring(0, 3);
        
        // Common WMI codes
        const manufacturerMap = {
            '1G1': 'CHEVROLET', '1G6': 'CADILLAC', '1GC': 'CHEVROLET',
            '1GT': 'GMC', '1FA': 'FORD', '1FB': 'FORD', '1FD': 'FORD',
            '1FM': 'FORD', '1FT': 'FORD', '1FU': 'FREIGHTLINER',
            '1GY': 'CADILLAC', '1N4': 'NISSAN', '1N6': 'NISSAN',
            '2G1': 'CHEVROLET', '2G2': 'PONTIAC', '2T1': 'TOYOTA',
            '2T2': 'LEXUS', '3FA': 'FORD', '3VW': 'VOLKSWAGEN',
            '4F2': 'MAZDA', '4F4': 'MAZDA', '4S3': 'SUBARU',
            '4S4': 'SUBARU', '4T1': 'TOYOTA', '4T4': 'TOYOTA',
            '5N1': 'NISSAN', '5NP': 'HYUNDAI', '5Y2': 'HYUNDAI',
            '5YF': 'HYUNDAI', '5YJ': 'TESLA', 'JH4': 'ACURA',
            'JHM': 'HONDA', 'JN1': 'NISSAN', 'JN8': 'NISSAN',
            'KM8': 'HYUNDAI', 'KNA': 'KIA', 'KNM': 'KIA',
            'WBA': 'BMW', 'WBS': 'BMW', 'WDD': 'MERCEDES-BENZ',
            'WDC': 'MERCEDES-BENZ', 'WAU': 'AUDI', 'WVW': 'VOLKSWAGEN'
        };
        
        return manufacturerMap[wmi] || null;
    }

    validateCheckDigit(vin) {
        const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
        const transliteration = {
            'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
            'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
            'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9
        };

        let sum = 0;
        for (let i = 0; i < 17; i++) {
            let value;
            if (i === 8) continue; // Skip check digit position
            
            if (isNaN(vin[i])) {
                value = transliteration[vin[i]];
            } else {
                value = parseInt(vin[i]);
            }
            
            sum += value * weights[i];
        }

        const checkDigit = sum % 11;
        const expectedDigit = checkDigit === 10 ? 'X' : checkDigit.toString();
        
        return vin[8] === expectedDigit;
    }

    // ========================================
    // CACHE MANAGEMENT
    // ========================================

    getFromCache(vin) {
        const cacheKey = this.config.cache.prefix + vin;
        
        // Check memory cache first
        if (this.cache.has(vin)) {
            const cached = this.cache.get(vin);
            if (this.isCacheValid(cached.timestamp)) {
                return cached.data;
            } else {
                this.cache.delete(vin);
            }
        }
        
        // Check localStorage
        try {
            const stored = localStorage.getItem(cacheKey);
            if (stored) {
                const parsed = JSON.parse(stored);
                if (this.isCacheValid(parsed.timestamp)) {
                    this.cache.set(vin, parsed);
                    return parsed.data;
                } else {
                    localStorage.removeItem(cacheKey);
                }
            }
        } catch (error) {
            console.warn('Cache read error:', error);
        }
        
        return null;
    }

    saveToCache(vin, data) {
        const cacheItem = {
            data: data,
            timestamp: Date.now()
        };
        
        // Save to memory cache
        this.cache.set(vin, cacheItem);
        
        // Enforce cache size limit
        if (this.cache.size > this.config.cache.maxSize) {
            const firstKey = this.cache.keys().next().value;
            this.cache.delete(firstKey);
        }
        
        // Save to localStorage
        try {
            const cacheKey = this.config.cache.prefix + vin;
            localStorage.setItem(cacheKey, JSON.stringify(cacheItem));
        } catch (error) {
            console.warn('Cache write error:', error);
        }
    }

    isCacheValid(timestamp) {
        return Date.now() - timestamp < this.config.cache.duration;
    }

    loadCacheFromLocalStorage() {
        try {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith(this.config.cache.prefix)) {
                    const stored = localStorage.getItem(key);
                    if (stored) {
                        const parsed = JSON.parse(stored);
                        if (this.isCacheValid(parsed.timestamp)) {
                            const vin = key.replace(this.config.cache.prefix, '');
                            this.cache.set(vin, parsed);
                        } else {
                            localStorage.removeItem(key);
                        }
                    }
                }
            });
        } catch (error) {
            console.warn('Cache load error:', error);
        }
    }

    cleanupOldCache() {
        try {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith(this.config.cache.prefix)) {
                    const stored = localStorage.getItem(key);
                    if (stored) {
                        const parsed = JSON.parse(stored);
                        if (!this.isCacheValid(parsed.timestamp)) {
                            localStorage.removeItem(key);
                        }
                    }
                }
            });
        } catch (error) {
            console.warn('Cache cleanup error:', error);
        }
    }

    clearLocalStorageCache() {
        try {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith(this.config.cache.prefix)) {
                    localStorage.removeItem(key);
                }
            });
        } catch (error) {
            console.warn('Cache clear error:', error);
        }
    }

    // ========================================
    // RATE LIMITING
    // ========================================

    checkRateLimit() {
        const now = Date.now();
        const windowMs = this.config.rateLimit.perMinutes * 60 * 1000;
        
        // Clean old requests
        this.requestHistory = this.requestHistory.filter(time => now - time < windowMs);
        
        // Check if under limit
        if (this.requestHistory.length < this.config.rateLimit.requests) {
            this.requestHistory.push(now);
            return true;
        }
        
        return false;
    }

    calculateHitRate() {
        // This would need request tracking to implement properly
        return 0;
    }
}

// ============================================================================
// GLOBAL INSTANCE AND UTILITIES
// ============================================================================

// Create global instance
window.VINDecoder = new GlobalVINDecoder();

// Convenience functions
window.decodeVIN = function(vin) {
    return window.VINDecoder.decode(vin);
};

window.validateVIN = function(vin) {
    return window.VINDecoder.validateVIN(vin);
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Global VIN Decoder ready:', window.VINDecoder.getCacheStats());
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlobalVINDecoder;
}