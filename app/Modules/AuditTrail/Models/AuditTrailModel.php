<?php

namespace Modules\AuditTrail\Models;

use CodeIgniter\Model;

class AuditTrailModel extends Model
{
    protected $table         = 'audit_trail';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id', 'action', 'module', 'record_id', 'description',
        'old_values', 'new_values', 'ip_address', 'user_agent',
        'session_id', 'request_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // Audit records should not be updated
    protected $deletedField  = null; // Audit records should not be deleted

    /**
     * Get audit records with user information and pagination
     */
    public function getAuditRecordsWithUsers($perPage = 10, $page = 1, $filters = [])
    {
        $builder = $this->db->table('audit_trail')
        ->select('
            audit_trail.*,
            users.first_name,
            users.last_name,
            users.avatar,
            users.avatar_style,
            users.user_type,
            custom_roles.title as role_title,
            custom_roles.color as role_color,
            contact_groups.name as group_name,
            contact_groups.color as group_color
        ')
        ->join('users', 'users.id = audit_trail.user_id', 'left')
        ->join('custom_roles', 'custom_roles.id = users.role_id', 'left')
        ->join('user_contact_groups', 'user_contact_groups.user_id = users.id', 'left')
        ->join('contact_groups', 'contact_groups.id = user_contact_groups.group_id', 'left');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $builder->where('audit_trail.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('audit_trail.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('audit_trail.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['action'])) {
            $builder->where('audit_trail.action', $filters['action']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('audit_trail.module', $filters['module']);
        }

        $builder->orderBy('audit_trail.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get audit records with user information and manual pagination
     */
    public function getAuditRecordsWithUsersPaginated($perPage = 10, $offset = 0, $filters = [])
    {
        $builder = $this->db->table('audit_trail')
        ->select('
            audit_trail.*,
            users.first_name,
            users.last_name,
            users.avatar,
            users.avatar_style,
            users.user_type,
            custom_roles.title as role_title,
            custom_roles.color as role_color,
            contact_groups.name as group_name,
            contact_groups.color as group_color
        ')
        ->join('users', 'users.id = audit_trail.user_id', 'left')
        ->join('custom_roles', 'custom_roles.id = users.role_id', 'left')
        ->join('user_contact_groups', 'user_contact_groups.user_id = users.id', 'left')
        ->join('contact_groups', 'contact_groups.id = user_contact_groups.group_id', 'left');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $builder->where('audit_trail.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('audit_trail.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('audit_trail.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['action'])) {
            $builder->where('audit_trail.action', $filters['action']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('audit_trail.module', $filters['module']);
        }

        $builder->orderBy('audit_trail.created_at', 'DESC');
        $builder->limit($perPage, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Get total count for pagination
     */
    public function getAuditRecordsCount($filters = [])
    {
        $builder = $this->db->table('audit_trail');

        // Apply same filters as in getAuditRecordsWithUsers
        if (!empty($filters['date_from'])) {
            $builder->where('audit_trail.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('audit_trail.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('audit_trail.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['action'])) {
            $builder->where('audit_trail.action', $filters['action']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('audit_trail.module', $filters['module']);
        }

        return $builder->countAllResults();
    }

    /**
     * Get a specific audit record with user information
     */
    public function getAuditRecordWithUser($id)
    {
        $builder = $this->db->table('audit_trail')
        ->select('
            audit_trail.*,
            users.first_name,
            users.last_name,
            users.avatar,
            users.avatar_style,
            users.user_type,
            custom_roles.title as role_title,
            custom_roles.color as role_color,
            contact_groups.name as group_name,
            contact_groups.color as group_color
        ')
        ->join('users', 'users.id = audit_trail.user_id', 'left')
        ->join('custom_roles', 'custom_roles.id = users.role_id', 'left')
        ->join('user_contact_groups', 'user_contact_groups.user_id = users.id', 'left')
        ->join('contact_groups', 'contact_groups.id = user_contact_groups.group_id', 'left')
        ->where('audit_trail.id', $id);

        return $builder->get()->getRowArray();
    }

    /**
     * Get all users for filter dropdown
     */
    public function getUsers()
    {
        $db = \Config\Database::connect();
        return $db->table('users')
                 ->select('id, first_name, last_name')
                 ->where('deleted_at', null)
                 ->orderBy('first_name', 'ASC')
                 ->get()
                 ->getResultArray();
    }

    /**
     * Get all distinct actions for filter dropdown
     */
    public function getActions()
    {
        return $this->distinct()
                   ->select('action')
                   ->orderBy('action', 'ASC')
                   ->findAll();
    }

    /**
     * Get all distinct modules for filter dropdown
     */
    public function getModules()
    {
        return $this->distinct()
                   ->select('module')
                   ->orderBy('module', 'ASC')
                   ->findAll();
    }

    /**
     * Create an audit log entry
     */
    public function logActivity($data)
    {
        $auditData = [
            'user_id' => $data['user_id'] ?? null,
            'action' => $data['action'],
            'module' => $data['module'],
            'record_id' => $data['record_id'] ?? null,
            'description' => $data['description'] ?? null,
            'old_values' => isset($data['old_values']) ? json_encode($data['old_values']) : null,
            'new_values' => isset($data['new_values']) ? json_encode($data['new_values']) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'session_id' => session_id() ?? null,
            'request_id' => uniqid('req_', true),
        ];

        return $this->insert($auditData);
    }

    /**
     * Get location statistics for analytics
     */
    public function getLocationStatistics()
    {
        // Get country statistics
        $countryStats = $this->db->table($this->table)
                                 ->select('country, country_code, COUNT(*) as count')
                                 ->where('country IS NOT NULL')
                                 ->where('country !=', '')
                                 ->groupBy('country, country_code')
                                 ->orderBy('count', 'DESC')
                                 ->limit(10)
                                 ->get()
                                 ->getResultArray();

        // Get city statistics
        $cityStats = $this->db->table($this->table)
                              ->select('city, region, country, country_code, COUNT(*) as count')
                              ->where('city IS NOT NULL')
                              ->where('city !=', '')
                              ->groupBy('city, region, country, country_code')
                              ->orderBy('count', 'DESC')
                              ->limit(15)
                              ->get()
                              ->getResultArray();

        return [
            'countries' => $countryStats,
            'cities' => $cityStats
        ];
    }

    /**
     * Get map data for interactive map
     */
    public function getMapData()
    {
        $query = $this->db->table($this->table . ' at')
                          ->select('at.*, u.first_name, u.last_name, u.avatar')
                          ->join('users u', 'u.id = at.user_id', 'left')
                          ->where('at.latitude IS NOT NULL')
                          ->where('at.longitude IS NOT NULL')
                          ->where('at.latitude !=', 0)
                          ->where('at.longitude !=', 0)
                          ->orderBy('at.created_at', 'DESC')
                          ->limit(100)
                          ->get()
                          ->getResultArray();

        // Group by coordinates to avoid cluttering
        $locations = [];
        foreach ($query as $record) {
            $key = round($record['latitude'], 4) . ',' . round($record['longitude'], 4);
            
            if (!isset($locations[$key])) {
                $locations[$key] = [
                    'latitude' => floatval($record['latitude']),
                    'longitude' => floatval($record['longitude']),
                    'city' => $record['city'],
                    'region' => $record['region'],
                    'country' => $record['country'],
                    'country_code' => $record['country_code'],
                    'activities' => []
                ];
            }
            
            $locations[$key]['activities'][] = [
                'id' => $record['id'],
                'user_name' => trim($record['first_name'] . ' ' . $record['last_name']),
                'user_avatar' => $record['avatar'],
                'action' => $record['action'],
                'module' => $record['module'],
                'description' => $record['description'],
                'created_at' => $record['created_at'],
                'ip_address' => $record['ip_address']
            ];
        }

        return array_values($locations);
    }

    /**
     * Get recent activities by location
     */
    public function getRecentActivitiesByLocation($limit = 20)
    {
        return $this->db->table($this->table . ' at')
                        ->select('at.*, u.first_name, u.last_name, u.avatar')
                        ->join('users u', 'u.id = at.user_id', 'left')
                        ->where('at.country IS NOT NULL')
                        ->where('at.country !=', '')
                        ->orderBy('at.created_at', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Get location statistics by time range
     */
    public function getLocationStatsByTimeRange()
    {
        $timeRanges = [
            '24h' => date('Y-m-d H:i:s', strtotime('-24 hours')),
            '7d' => date('Y-m-d H:i:s', strtotime('-7 days')),
            '30d' => date('Y-m-d H:i:s', strtotime('-30 days'))
        ];

        $stats = [];
        foreach ($timeRanges as $period => $since) {
            $stats[$period] = $this->db->table($this->table)
                                       ->select('country, country_code, COUNT(*) as count')
                                       ->where('country IS NOT NULL')
                                       ->where('country !=', '')
                                       ->where('created_at >=', $since)
                                       ->groupBy('country, country_code')
                                       ->orderBy('count', 'DESC')
                                       ->limit(5)
                                       ->get()
                                       ->getResultArray();
        }

        return $stats;
    }
} 