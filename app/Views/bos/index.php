<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'BOS Inventory Management' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $page_title ?? 'Vehicle Inventory Detail Report' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
<li class="breadcrumb-item active">BOS Inventory</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #405189;
        --primary-hover: #364574;
        --secondary-color: #74788d;
        --light-gray: #f8f9fa;
        --border-color: #e3ebf0;
        --text-primary: #495057;
        --text-secondary: #74788d;
        --text-muted: #adb5bd;
        --success-color: #0ab39c;
        --warning-color: #f7b84b;
        --danger-color: #f06548;
        --white: #ffffff;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        border-top: 4px solid var(--primary-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-top-color: var(--primary-hover);
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
    }

    .controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .last-update {
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f3f4 100%);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .update-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .avg-days-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(64, 81, 137, 0.1);
        border-radius: var(--radius-sm);
        border: 1px solid rgba(64, 81, 137, 0.2);
        margin-top: 0.25rem;
    }

    .avg-label {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.8rem;
    }

    .avg-value {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, #364574 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .avg-unit {
        color: var(--text-muted);
        font-size: 0.75rem;
        font-style: italic;
    }

    .error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
        display: none;
    }

    .error.show {
        display: block;
    }

    .table-container {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        padding: 1.5rem;
    }

    .table-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .table-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .table-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: var(--primary-color);
    }

    .table-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }

    .table-wrapper {
        margin: -0.5rem;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .table {
        margin: 0;
        font-size: 0.875rem;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f3f4 100%);
        border: none;
        border-bottom: 2px solid var(--primary-color);
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .table thead th:first-child {
        border-top-left-radius: var(--radius-md);
    }

    .table thead th:last-child {
        border-top-right-radius: var(--radius-md);
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f3f6;
    }

    .table tbody tr:hover {
        background-color: rgba(64, 81, 137, 0.02);
        transform: scale(1.001);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
        color: var(--text-primary);
    }

    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: var(--radius-md);
    }

    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: var(--radius-md);
    }

    /* Days badge styling - Bootstrap badges with custom enhancements */
    .badge {
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 35px;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Override Bootstrap badge colors for better contrast and consistency */
    .badge.bg-success {
        background-color: var(--success-color) !important;
        color: white;
    }

    .badge.bg-warning {
        background-color: var(--warning-color) !important;
        color: white;
    }

    .badge.bg-danger {
        background-color: var(--danger-color) !important;
        color: white;
    }

    /* Stock number styling */
    .stock-number {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        background: linear-gradient(135deg, var(--primary-color) 0%, #364574 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Vehicle styling */
    .vehicle-info {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Notes preview styling */
    .notes-preview {
        color: var(--text-secondary);
        font-size: 0.8rem;
        line-height: 1.4;
        cursor: default;
    }

    .notes-preview.has-content {
        cursor: help;
        border-bottom: 1px dotted var(--text-muted);
    }

    /* Row number styling */
    .row-number-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--text-muted) 0%, #8b9dc3 100%);
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Loading spinner animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .stats {
            grid-template-columns: 1fr;
        }

        .controls {
            flex-direction: column;
            align-items: stretch;
        }

        .last-update {
            text-align: center;
            margin-top: 1rem;
        }
    }

    @media (max-width: 480px) {
        .table-container {
            padding: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Error Container -->
<div id="errorContainer" class="error"></div>

<!-- Stats -->
<div class="stats">
    <div class="stat-card">
        <div class="stat-label">Total Records</div>
        <div id="totalRecords" class="stat-value">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Last Update</div>
        <div id="lastUpdateStat" class="stat-value">--:--</div>
    </div>
</div>

<!-- Controls -->
<div class="controls">
    <button id="refreshBtn" class="btn btn-primary">
        <i data-feather="refresh-cw"></i>
        Refresh
    </button>
    <button id="exportBtn" class="btn btn-outline-secondary">
        <i data-feather="download"></i>
        Export
    </button>
</div>

<div id="lastUpdate" class="last-update">
    <div class="update-info">
        <span id="lastUpdateTime"></span>
        <div id="avgDaysInfo" class="avg-days-info" style="display: none;">
            <span class="avg-label">Average Days in Detail:</span>
            <span id="avgDaysValue" class="avg-value">0.0</span>
            <span class="avg-unit">days</span>
        </div>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">
            <i data-feather="list" class="table-icon"></i>
            Vehicle Inventory Detail Report
        </h3>
        <p class="table-subtitle">Real-time tracking of vehicles in Detail Department</p>
    </div>
    <div class="table-wrapper">
        <table id="inventoryTable" class="table table-hover" style="width: 100%">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Date in Detail</th>
                    <th>Days in Detail</th>
                    <th># Keys</th>
                    <th>Stock #</th>
                    <th>Vehicle</th>
                    <th>Write Up Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this -->
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ========================================
    // BOS INVENTORY MANAGEMENT SYSTEM
    // ========================================

class InventoryManager {
    constructor() {
        // Configuration
        this.debugMode = new URLSearchParams(window.location.search).get('debug') === 'true';
        this.pollingInterval = 30000; // 30 seconds
        this.maxRetries = 3;
        this.retryCount = 0;

        // State
        this.inventoryData = [];
        this.dataTable = null;
        this.pollingTimer = null;
        this.isAuthenticated = false;
        this.userInfo = null;
        
        // Initialization
        this.init();
    }

    async init() {
        try {
            console.log('Initializing BOS inventory manager...');
            
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initializeSystem());
            } else {
                this.initializeSystem();
            }
        } catch (error) {
            console.error('Failed to initialize inventory manager:', error);
            this.showError('Failed to initialize system: ' + error.message);
        }
    }

    async initializeSystem() {
        await this.checkAuthentication();
        this.setupEventListeners();
        this.initializeDataTables();
        this.loadInventoryData();
        this.startPolling();
    }

    setupEventListeners() {
        // Refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadInventoryData();
            });
        }
        
        // Export button
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                this.exportData();
            });
        }
    }

    initializeDataTables() {
        console.log('Initializing DataTables...');
        
        this.dataTable = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            responsive: true,
            autoWidth: false,
            order: [[1, 'desc']], // Order by Date in Detail (descending)
            columnDefs: [
                {
                    targets: 0,
                    className: 'text-center row-number',
                    orderable: false,
                    searchable: false,
                    width: '50px'
                },
                {
                    targets: 1,
                    className: 'text-center',
                    width: '120px'
                },
                {
                    targets: 2,
                    className: 'text-center',
                    width: '100px'
                },
                {
                    targets: 3,
                    className: 'text-center',
                    width: '80px'
                },
                {
                    targets: 4,
                    className: 'text-center',
                    width: '120px'
                },
                {
                    targets: 5,
                    className: 'text-left',
                    width: '200px'
                },
                {
                    targets: 6,
                    className: 'text-center',
                    width: '120px'
                },
                {
                    targets: 7,
                    className: 'text-left notes-column',
                    orderable: false,
                    width: 'auto'
                }
            ],
            language: {
                processing: 'Loading data...',
                lengthMenu: 'Show _MENU_ entries',
                zeroRecords: 'No vehicles found in detail',
                info: 'Showing _START_ to _END_ of _TOTAL_ vehicles',
                infoEmpty: 'No vehicles available',
                infoFiltered: '(filtered from _MAX_ total vehicles)',
                search: 'Search vehicles:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            },
            drawCallback: function() {
                this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = '<span class="row-number-badge">' + (i + 1) + '</span>';
                });
                
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            },
            initComplete: function() {
                console.log('DataTables initialized successfully');
            }
        });
    }

    // === DATA LOADING ===

    async loadInventoryData(showLoadingIndicator = true) {
        if (showLoadingIndicator) {
            this.showLoading(true);
        }
        this.hideError();

        try {
            const url = this.buildApiUrl();
            const response = await this.fetchWithRetry(url);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${await response.text()}`);
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Unknown error occurred');
            }

            this.inventoryData = result.data || [];

            if (this.dataTable) {
                this.updateDataTable();
            }

            this.updateLastUpdate();
            this.updateStats();
            this.retryCount = 0;

        } catch (error) {
            this.handleLoadError(error);
        } finally {
            if (showLoadingIndicator) {
                this.showLoading(false);
            }
        }
    }

    buildApiUrl() {
        const params = new URLSearchParams();
        if (this.debugMode) {
            params.append('debug', 'true');
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('refresh') === 'true') {
            params.append('refresh', 'true');
        }
        const queryString = params.toString();
        return `get_inventory.php${queryString ? '?' + queryString : ''}`;
    }

    async fetchWithRetry(url, retries = 3) {
        for (let i = 0; i < retries; i++) {
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    cache: 'no-cache'
                });
                return response;
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
    }

    // === DATATABLES METHODS ===

    updateDataTable() {
        if (!this.dataTable) return;
        
        try {
            // Clear existing data
            this.dataTable.clear();
            
            // Add new rows
            this.inventoryData.forEach((row, index) => {
                const dateInDetail = row[0] || '';
                const daysInDetail = this.calculateDaysInDetail(dateInDetail);
                
                const rowData = [
                    '', // Row number (will be populated by drawCallback)
                    this.formatDate(dateInDetail), // Date in Detail
                    this.formatDaysInDetail(daysInDetail), // Days in Detail
                    row[1] || '', // Keys
                    this.formatStockNumber(row[2] || ''), // Stock #
                    this.formatVehicle(row[3] || ''), // Vehicle
                    this.formatDate(row[4] || ''), // Write Up Date
                    this.formatNotes(row[5] || '') // Notes
                ];
                this.dataTable.row.add(rowData);
            });
            
            // Redraw the table
            this.dataTable.draw();
        } catch (error) {
            console.error('Error updating DataTable:', error);
        }
    }

    // Helper methods for formatting data
    parseDate(dateString) {
        if (!dateString) return null;
        
        try {
            let date;
            
            // Handle different date formats
            if (dateString.includes('/')) {
                // Handle MM/DD or MM/DD/YY or MM/DD/YYYY format
                const parts = dateString.split('/');
                if (parts.length === 2) {
                    // MM/DD format - assume current year
                    const currentYear = new Date().getFullYear();
                    date = new Date(currentYear, parseInt(parts[0]) - 1, parseInt(parts[1]));
                } else if (parts.length === 3) {
                    // MM/DD/YY or MM/DD/YYYY format
                    let year = parseInt(parts[2]);
                    
                    // Handle 2-digit years
                    if (year < 100) {
                        // If year is 00-30, assume 20xx, otherwise 19xx
                        year = year <= 30 ? 2000 + year : 1900 + year;
                    }
                    
                    // If year is clearly wrong (like 2001 for current data), use current year
                    const currentYear = new Date().getFullYear();
                    if (year < currentYear - 1) {
                        year = currentYear;
                    }
                    
                    date = new Date(year, parseInt(parts[0]) - 1, parseInt(parts[1]));
                }
            } else {
                // Try parsing as standard date format
                date = new Date(dateString);
                
                // If year is clearly wrong, adjust to current year
                const currentYear = new Date().getFullYear();
                if (date.getFullYear() < currentYear - 1) {
                    date.setFullYear(currentYear);
                }
            }
            
            if (isNaN(date.getTime())) return null;
            return date;
        } catch (error) {
            console.error('Error parsing date:', dateString, error);
            return null;
        }
    }

    calculateDaysInDetail(dateString) {
        if (!dateString) return 0;
        
        try {
            const detailDate = this.parseDate(dateString);
            if (!detailDate) return 0;
            
            const now = new Date();
            
            // Reset time to avoid timezone issues
            detailDate.setHours(0, 0, 0, 0);
            now.setHours(0, 0, 0, 0);
            
            const diffTime = now - detailDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            // Debug logging
            if (this.debugMode) {
                console.log(`Date: ${dateString} -> Parsed: ${detailDate.toDateString()} -> Days: ${diffDays}`);
            }
            
            return Math.max(0, diffDays);
        } catch (error) {
            console.error('Error calculating days in detail:', error);
            return 0;
        }
    }

    formatDaysInDetail(days) {
        let badgeClass = 'bg-success';
        let label = `${days}d`;
        
        if (days >= 0 && days <= 1) {
            badgeClass = 'bg-success';
        } else if (days >= 2 && days <= 4) {
            badgeClass = 'bg-warning';
        } else if (days >= 5) {
            badgeClass = 'bg-danger';
        }
        
        return `<span class="badge ${badgeClass}" data-days="${days}">${label}</span>`;
    }

    formatStockNumber(stockNumber) {
        if (!stockNumber) return '';
        return `<span class="stock-number">${stockNumber}</span>`;
    }

    formatVehicle(vehicle) {
        if (!vehicle) return '';
        return `<span class="vehicle-info">${vehicle}</span>`;
    }

    formatNotes(notes) {
        if (!notes) return '';
        const hasContent = notes.trim().length > 0;
        const className = hasContent ? 'notes-preview has-content' : 'notes-preview';
        const displayText = notes.length > 50 ? notes.substring(0, 50) + '...' : notes;
        return `<span class="${className}">${displayText}</span>`;
    }

    formatDate(dateString) {
        if (!dateString) return '';
        
        try {
            const date = this.parseDate(dateString);
            if (!date) return dateString;
            
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        } catch (error) {
            console.error('Error formatting date:', dateString, error);
            return dateString;
        }
    }

    // === POLLING ===

    startPolling() {
        console.log('Setting up polling for updates');
        
        // Clear any existing polling
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
        }
        
        // Set up new polling
        this.pollingTimer = setInterval(() => {
            this.loadInventoryData(false);
        }, this.pollingInterval);
        
        console.log('Polling started');
    }

    // === EXPORT ===

    exportData() {
        if (!this.inventoryData || this.inventoryData.length === 0) {
            alert('No data to export');
            return;
        }
        
        // Create CSV content
        const headers = ['Row #', 'Date in Detail', 'Days in Detail', '# Keys', 'Stock #', 'Vehicle', 'Write Up Date', 'Notes'];
        let csvContent = headers.join(',') + '\n';
        
        this.inventoryData.forEach((row, index) => {
            const dateInDetail = row[0] || '';
            const daysInDetail = this.calculateDaysInDetail(dateInDetail);
            
            const csvRow = [
                index + 1, // Row number
                dateInDetail,
                daysInDetail,
                row[1] || '',
                row[2] || '',
                row[3] || '',
                row[4] || '',
                row[5] || ''
            ].map(field => {
                // Escape quotes and wrap in quotes if contains comma or quotes
                const stringField = String(field);
                if (stringField.includes(',') || stringField.includes('"') || stringField.includes('\n')) {
                    return '"' + stringField.replace(/"/g, '""') + '"';
                }
                return stringField;
            }).join(',');
            csvContent += csvRow + '\n';
        });
        
        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `bos_inventory_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // === STATS UPDATE ===

    updateStats() {
        const totalRecords = this.inventoryData ? this.inventoryData.length : 0;
        
        const totalEl = document.getElementById('totalRecords');
        if (totalEl) {
            totalEl.textContent = totalRecords.toLocaleString();
        }
    }

    updateLastUpdate() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        
        const lastUpdateTimeEl = document.getElementById('lastUpdateTime');
        const lastUpdateStatEl = document.getElementById('lastUpdateStat');

        if (lastUpdateTimeEl) {
            lastUpdateTimeEl.textContent = `Last updated: ${timeString}`;
        }
        
        if (lastUpdateStatEl) {
            lastUpdateStatEl.textContent = timeString;
        }
        
        // Update average days if user is authenticated
        if (this.isAuthenticated) {
            this.updateAverageDays();
        }
    }

    // === AUTHENTICATION & AVERAGE CALCULATION ===
    
    async checkAuthentication() {
        try {
            const response = await fetch('<?= base_url('bos/check-auth') ?>', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                cache: 'no-cache'
            });
            
            if (response.ok) {
                const result = await response.json();
                this.isAuthenticated = result.authenticated || false;
                this.userInfo = result.user || null;
                
                if (this.debugMode) {
                    console.log('Authentication check:', {
                        authenticated: this.isAuthenticated,
                        user: this.userInfo
                    });
                }
                
                // Show/hide average days info based on authentication
                const avgDaysInfo = document.getElementById('avgDaysInfo');
                if (avgDaysInfo) {
                    avgDaysInfo.style.display = this.isAuthenticated ? 'flex' : 'none';
                }
            } else {
                console.warn('Authentication check failed:', response.status);
                this.isAuthenticated = false;
            }
        } catch (error) {
            console.error('Error checking authentication:', error);
            this.isAuthenticated = false;
        }
    }
    
    calculateAverageDays() {
        if (!this.inventoryData || this.inventoryData.length === 0) {
            return 0;
        }
        
        let totalDays = 0;
        let validEntries = 0;
        
        this.inventoryData.forEach(row => {
            const dateInDetail = row[0]; // First column is date
            if (dateInDetail) {
                const days = this.calculateDaysInDetail(dateInDetail);
                if (days >= 0) {
                    totalDays += days;
                    validEntries++;
                }
            }
        });
        
        if (validEntries === 0) return 0;
        
        const average = totalDays / validEntries;
        return Math.round(average * 10) / 10; // Round to 1 decimal place
    }
    
    updateAverageDays() {
        if (!this.isAuthenticated) return;
        
        const avgDaysValue = document.getElementById('avgDaysValue');
        const avgDaysInfo = document.getElementById('avgDaysInfo');
        
        if (avgDaysValue && avgDaysInfo) {
            const average = this.calculateAverageDays();
            avgDaysValue.textContent = average.toFixed(1);
            
            // Show the info if it's hidden
            if (avgDaysInfo.style.display === 'none') {
                avgDaysInfo.style.display = 'flex';
            }
            
            if (this.debugMode) {
                console.log('Average days updated:', average);
            }
        }
    }

    // === ERROR HANDLING ===
    
    handleLoadError(error) {
        console.error('Error loading inventory data:', error);
        this.showError(`Failed to load inventory data: ${error.message}`);
        
        if (this.retryCount < this.maxRetries) {
            this.retryCount++;
            console.log(`Retrying... (${this.retryCount}/${this.maxRetries})`);
            setTimeout(() => {
                this.loadInventoryData();
            }, 2000 * this.retryCount);
        } else {
            console.error('Max retries reached. Please refresh the page.');
            this.showError('Max retries reached. Please refresh the page manually.');
        }
    }

    showError(message) {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.classList.add('show');
        }
    }

    hideError() {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.classList.remove('show');
        }
    }

    showLoading(show) {
        // Use custom loader instead of DataTables processing
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            if (show) {
                if (!document.getElementById('customLoader')) {
                    const loader = document.createElement('div');
                    loader.id = 'customLoader';
                    loader.style.cssText = `
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(255, 255, 255, 0.95);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 1000;
                        border-radius: var(--radius-lg);
                    `;
                    loader.innerHTML = `
                        <div style="text-align: center; color: var(--text-secondary);">
                            <div style="width: 2.5rem; height: 2.5rem; border: 3px solid var(--border-color); border-radius: 50%; border-top-color: var(--primary-color); animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                            <div style="font-weight: 500; font-size: 0.875rem;">Loading data...</div>
                        </div>
                    `;
                    tableContainer.style.position = 'relative';
                    tableContainer.appendChild(loader);
                }
            } else {
                const loader = document.getElementById('customLoader');
                if (loader) {
                    loader.remove();
                }
            }
        }
    }
}

// Initialize the inventory manager when the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && typeof feather !== 'undefined') {
        console.log('Starting BOS inventory manager...');
        window.inventoryManager = new InventoryManager();
        
        // Initialize feather icons
        feather.replace();
    } else {
        console.error('Required libraries not loaded');
    }
});
</script>
<?= $this->endSection() ?>
