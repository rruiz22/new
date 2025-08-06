<?php

namespace Modules\AuditTrail\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\AuditTrail\Models\AuditTrailModel;

class AuditController extends BaseController
{
    protected $helpers = ['url', 'form', 'device', 'location'];
    protected $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditTrailModel();
    }

    /**
     * Display the main audit trail page
     */
    public function index()
    {
        // Get filters from request
        $filters = [
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'user_id' => $this->request->getGet('user_id'),
            'action' => $this->request->getGet('action'),
            'module' => $this->request->getGet('module'),
        ];

        // Remove empty filters
        $filters = array_filter($filters);

        // Get current page
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        // Get total count for pagination
        $totalRecords = $this->auditModel->getAuditRecordsCount($filters);
        
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get audit records with manual pagination
        $auditRecords = $this->auditModel->getAuditRecordsWithUsersPaginated($perPage, $offset, $filters);
        
        // Get data for filter dropdowns
        $users = $this->auditModel->getUsers();
        $actions = $this->auditModel->getActions();
        $modules = $this->auditModel->getModules();

        $data = [
            'title' => lang('App.audit_trail'),
            'page_title' => lang('App.audit_trail'),
            'breadcrumbs' => [
                [
                    'title' => lang('App.dashboard'),
                    'url' => base_url('dashboard')
                ],
                [
                    'title' => lang('App.audit_trail'),
                    'url' => ''
                ]
            ],
            'auditRecords' => $auditRecords,
            'users' => $users,
            'actions' => $actions,
            'modules' => $modules,
            'filters' => $filters,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords
        ];

        return view('Modules\AuditTrail\Views\audit\index', $data);
    }

    /**
     * Show specific audit record
     */
    public function show($id)
    {
        $auditRecord = $this->auditModel->getAuditRecordWithUser($id);
        
        if (!$auditRecord) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Audit record #$id not found");
        }

        $data = [
            'title' => lang('App.audit_trail') . ' - Detail',
            'page_title' => 'Audit Detail #' . $id,
            'audit_id' => $id,
            'auditRecord' => $auditRecord,
            'breadcrumbs' => [
                [
                    'title' => lang('App.dashboard'),
                    'url' => base_url('dashboard')
                ],
                [
                    'title' => lang('App.audit_trail'),
                    'url' => base_url('audit')
                ],
                [
                    'title' => 'Detail #' . $id,
                    'url' => ''
                ]
            ]
        ];

        return view('Modules\AuditTrail\Views\audit\show', $data);
    }

    /**
     * Export audit trail to PDF
     */
    public function exportPdf()
    {
        // TODO: Implement PDF export functionality
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setJSON([
                'status' => 'success',
                'message' => 'PDF export functionality will be implemented soon.'
            ]);
    }

    /**
     * Export audit trail to Excel
     */
    public function exportExcel()
    {
        // TODO: Implement Excel export functionality
        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setJSON([
                'status' => 'success',
                'message' => 'Excel export functionality will be implemented soon.'
            ]);
    }

    public function analytics()
    {
        $data = [
            'title' => 'Geographic Analytics - Audit Trail',
            'locationStats' => $this->auditModel->getLocationStatistics(),
            'mapData' => $this->auditModel->getMapData(),
            'recentActivities' => $this->auditModel->getRecentActivitiesByLocation(50),
            'timeRangeStats' => $this->auditModel->getLocationStatsByTimeRange()
        ];

        return view('Modules\AuditTrail\Views\audit\analytics', $data);
    }

    public function getMapDataJson()
    {
        $mapData = $this->auditModel->getMapData();
        return $this->response->setJSON($mapData);
    }

    public function getLocationStatsJson()
    {
        $stats = $this->auditModel->getLocationStatistics();
        return $this->response->setJSON($stats);
    }
}