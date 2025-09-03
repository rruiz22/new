<!-- ============================================== -->
<!-- GLOBAL SALES ORDER VIEW MODAL - Fullscreen -->
<!-- ============================================== -->

<style data-timestamp="<?= time() ?>">
/* ================================================ */
/* VIEW MODAL - Fullscreen with Tabs */
/* ================================================ */

#global-sales-order-view-modal {
    --view-modal-z-index: 10000;
    z-index: 10000 !important;
}

#global-sales-order-view-modal.show {
    display: block !important;
}

#global-sales-order-view-modal .modal-dialog {
    max-width: none;
    width: 100vw;
    height: 100vh;
    margin: 0;
    transform: none !important;
}

#global-sales-order-view-modal .modal-content {
    height: 100vh;
    border-radius: 0;
    border: none;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background-color: #ffffff;
    position: relative;
    z-index: 1;
}

/* Ensure modal backdrop doesn't interfere */
#global-sales-order-view-modal.modal {
    --bs-modal-z-index: 10000;
}

#global-sales-order-view-modal .modal-backdrop {
    z-index: 9999 !important;
}

#global-sales-order-view-modal .modal-header {
    flex-shrink: 0;
    border-bottom: 1px solid #e9ecef;
    padding: 0; /* Remove padding since top-bar has its own */
}

#global-sales-order-view-modal .modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 0;
    -webkit-overflow-scrolling: touch;
}

#global-sales-order-view-modal .modal-footer {
    flex-shrink: 0;
    border-top: 1px solid #e9ecef;
    padding: 16px 24px;
    background: #f8f9fa;
}

/* Top Bar Styling */
.view-modal-top-bar {
    background: #f8fafc;
    border: none;
    border-bottom: 1px solid #e2e8f0;
    padding: 0;
    margin: 0;
}

.view-modal-top-bar .row {
    margin: 0;
}

.view-modal-top-bar .top-bar-item {
    min-height: 100px;
    border-right: 1px solid #e2e8f0;
    padding: 16px 12px;
    display: flex;
    align-items: center;
}

.view-modal-top-bar .top-bar-item:last-child {
    border-right: none;
}

/* Tab Navigation */
.view-modal-nav-tabs {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0 24px;
    margin: 0;
}

.view-modal-nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    padding: 12px 16px;
    color: #6c757d;
    font-weight: 500;
}

.view-modal-nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #495057;
    background: rgba(0,0,0,0.03);
}

.view-modal-nav-tabs .nav-link.active {
    color: #0d6efd;
    background: transparent;
    border-bottom-color: #0d6efd;
}

/* Tab Content */
.view-modal-tab-content {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.view-modal-tab-pane {
    height: 100%;
}

/* Cards in modal */
.view-modal-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.view-modal-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 16px 20px;
}

.view-modal-card .card-body {
    padding: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .view-modal-top-bar .top-bar-item {
        min-height: 80px;
        padding: 12px 8px;
    }
    
    .view-modal-tab-content {
        padding: 16px;
    }
    
    .view-modal-nav-tabs {
        padding: 0 16px;
    }
}
</style>

<!-- Modal Structure -->
<div class="modal fade" id="global-sales-order-view-modal" tabindex="-1" aria-labelledby="globalSalesOrderViewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header with Top Bar -->
            <div class="modal-header view-modal-top-bar" id="viewModalTopBar">
                <!-- Top bar content will be loaded here -->
                <div class="container-fluid">
                    <div class="row g-0">
                        <div class="col-md-2 col-6">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="hash" class="text-primary"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Order ID</div>
                                    <div class="top-bar-value" id="viewOrderId">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="user" class="text-success"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Client</div>
                                    <div class="top-bar-value" id="viewClient">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="calendar" class="text-info"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Date</div>
                                    <div class="top-bar-value" id="viewDate">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="clock" class="text-warning"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Time</div>
                                    <div class="top-bar-value" id="viewTime">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="activity" class="text-secondary"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Status</div>
                                    <div class="top-bar-value">
                                        <span class="badge" id="viewStatus">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="top-bar-item">
                                <div class="top-bar-icon">
                                    <i data-feather="x" class="text-muted" role="button" data-bs-dismiss="modal" title="Close"></i>
                                </div>
                                <div class="top-bar-content">
                                    <div class="top-bar-label">Actions</div>
                                    <div class="top-bar-value">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editCurrentOrder()">
                                            <i data-feather="edit-2" width="14"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="view-modal-nav-tabs">
                <ul class="nav nav-tabs nav-tabs-bordered" id="viewModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                            <i data-feather="file-text" width="16" class="me-1"></i>
                            Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities-pane" type="button" role="tab">
                            <i data-feather="activity" width="16" class="me-1"></i>
                            Activities
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments-pane" type="button" role="tab">
                            <i data-feather="message-circle" width="16" class="me-1"></i>
                            Comments
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="followers-tab" data-bs-toggle="tab" data-bs-target="#followers-pane" type="button" role="tab">
                            <i data-feather="users" width="16" class="me-1"></i>
                            Followers
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Modal Body with Tab Content -->
            <div class="modal-body view-modal-tab-content">
                <div class="tab-content" id="viewModalTabContent">
                    <!-- Details Tab -->
                    <div class="tab-pane fade show active" id="details-pane" role="tabpanel">
                        <div id="detailsContent">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading details...</span>
                                </div>
                                <div class="mt-2 text-muted">Loading order details...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Activities Tab -->
                    <div class="tab-pane fade" id="activities-pane" role="tabpanel">
                        <div id="activitiesContent">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading activities...</span>
                                </div>
                                <div class="mt-2 text-muted">Loading activities...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Tab -->
                    <div class="tab-pane fade" id="comments-pane" role="tabpanel">
                        <div id="commentsContent">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading comments...</span>
                                </div>
                                <div class="mt-2 text-muted">Loading comments...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Followers Tab -->
                    <div class="tab-pane fade" id="followers-pane" role="tabpanel">
                        <div id="followersContent">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading followers...</span>
                                </div>
                                <div class="mt-2 text-muted">Loading followers...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="editCurrentOrder()">
                            <i data-feather="edit-2" width="16" class="me-1"></i>
                            Edit Order
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="printCurrentOrder()">
                            <i data-feather="printer" width="16" class="me-1"></i>
                            Print
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="downloadCurrentOrderPDF()">
                            <i data-feather="download" width="16" class="me-1"></i>
                            Download PDF
                        </button>
                    </div>
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i data-feather="x" width="16" class="me-1"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>