<div class="row">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.today_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +3.2%
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="todayOrdersCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('today-orders-tab')">View Details</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i data-feather="calendar" class="text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.pending_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-danger fs-14 mb-0">
                            <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -1.2%
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="pendingOrdersCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('pending-orders-tab')">View Details</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i data-feather="clock" class="text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.this_week') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +5.8%
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="weekOrdersCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('week-orders-tab')">View Details</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                            <i data-feather="calendar" class="text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.total_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +2.1%
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="totalOrdersCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('all-orders-tab')">View Details</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i data-feather="list" class="text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Recent Service Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap table-striped-columns mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Order #</th>
                                <th scope="col">Client</th>
                                <th scope="col">Service</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersTableBody">
                            <tr>
                                <td colspan="6" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-height-100">
            <div class="card-header">
                <h4 class="card-title mb-0">Order Status Distribution</h4>
            </div>
            <div class="card-body">
                <div id="orderStatusChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabId) {
    // Remove active class from all tabs
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Add active class to target tab
    const targetTab = document.querySelector(`[href="#${tabId}"]`);
    const targetPane = document.getElementById(tabId);
    
    if (targetTab && targetPane) {
        targetTab.classList.add('active');
        targetPane.classList.add('active');
    }
}

// Wait for both DOM and jQuery to be loaded  
function initDashboard() {
    if (typeof $ === 'undefined') {
        setTimeout(initDashboard, 100);
        return;
    }
    loadDashboardData();
}

// Initialize when DOM is loaded and jQuery is available
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}

function loadDashboardData() {
    // Load counts
    fetch('<?= base_url('/service_orders/dashboard-data') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('todayOrdersCount').textContent = data.todayCount || 0;
                document.getElementById('pendingOrdersCount').textContent = data.pendingCount || 0;
                document.getElementById('weekOrdersCount').textContent = data.weekCount || 0;
                document.getElementById('totalOrdersCount').textContent = data.totalCount || 0;
                
                // Update badges
                updateBadge('todayOrdersBadge', data.todayCount);
                updateBadge('tomorrowOrdersBadge', data.tomorrowCount);
                updateBadge('pendingOrdersBadge', data.pendingCount);
                updateBadge('weekOrdersBadge', data.weekCount);
                
                // Load recent orders
                loadRecentOrders(data.recentOrders || []);
            }
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
        });
}

function updateBadge(badgeId, count) {
    const badge = document.getElementById(badgeId);
    if (badge) {
        badge.textContent = count || 0;
        badge.style.display = count > 0 ? 'inline' : 'none';
    }
}

function loadRecentOrders(orders) {
    const tbody = document.getElementById('recentOrdersTableBody');
    if (!orders || orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No recent orders found</td></tr>';
        return;
    }
    
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td><span class="fw-semibold">#${order.id}</span></td>
            <td>${order.client_name || 'N/A'}</td>
            <td>${order.service_name || 'N/A'}</td>
            <td>${order.date}</td>
            <td><span class="badge bg-${getStatusColor(order.status)}">${order.status}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewOrder(${order.id})">
                    <i data-feather="eye" class="icon-xs"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function getStatusColor(status) {
    const statusColors = {
        'pending': 'warning',
        'processing': 'info',
        'in_progress': 'primary',
        'completed': 'success',
        'cancelled': 'danger'
    };
    return statusColors[status] || 'secondary';
}

function viewOrder(orderId) {
    // Implementation for viewing order details

}
</script> 
