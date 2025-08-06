<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Models\UserIdentityModel;

class ContactInvitationModel extends Model
{
    protected $table = 'contact_invitations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'email',
        'first_name', 
        'last_name',
        'client_id',
        'assigned_group_id',
        'invitation_token',
        'status',
        'expires_at',
        'sent_at',
        'accepted_at',
        'sent_by',
        'message'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'email' => 'required|valid_email',
        'status' => 'required|in_list[pending,accepted,expired,cancelled]',
        'invitation_token' => 'required|is_unique[contact_invitations.invitation_token]',
    ];

    protected $validationMessages = [];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateToken'];
    protected $beforeUpdate = [];
    protected $afterInsert = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate unique invitation token before insert
     */
    protected function generateToken(array $data)
    {
        if (!isset($data['data']['invitation_token'])) {
            $data['data']['invitation_token'] = $this->generateUniqueToken();
        }
        return $data;
    }

    /**
     * Generate a unique token
     */
    private function generateUniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
        } while ($this->where('invitation_token', $token)->first());
        
        return $token;
    }

    /**
     * Create a new invitation
     */
    public function createInvitation(array $data): int|false
    {
        $data['invitation_token'] = $this->generateUniqueToken();
        $data['status'] = 'pending';
        $data['expires_at'] = date('Y-m-d H:i:s', strtotime('+7 days'));
        $data['sent_by'] = session()->get('user_id') ?? auth()->id();
        
        return $this->insert($data);
    }

    /**
     * Get invitation by token
     */
    public function getByToken(string $token): array|null
    {
        $builder = $this->builder();
        
        $builder->select('contact_invitations.*, 
                         clients.name as client_name,
                         contact_groups.name as group_name,
                         contact_groups.color as group_color,
                         contact_groups.icon as group_icon,
                         users.first_name as sender_first_name,
                         users.last_name as sender_last_name')
                ->join('clients', 'clients.id = contact_invitations.client_id', 'left')
                ->join('contact_groups', 'contact_groups.id = contact_invitations.assigned_group_id', 'left')
                ->join('users', 'users.id = contact_invitations.sent_by', 'left')
                ->where('contact_invitations.invitation_token', $token)
                ->where('contact_invitations.status', 'pending')
                ->where('contact_invitations.expires_at >', date('Y-m-d H:i:s'));
        
        return $builder->get()->getRowArray();
    }

    /**
     * Validate invitation token
     */
    public function validateToken(string $token): bool
    {
        $invitation = $this->where('invitation_token', $token)
                          ->where('status', 'pending')
                          ->where('expires_at >', date('Y-m-d H:i:s'))
                          ->first();
        
        return $invitation !== null;
    }

    /**
     * Accept invitation
     */
    public function acceptInvitation(string $token): bool
    {
        return $this->where('invitation_token', $token)
                   ->set([
                       'status' => 'accepted',
                       'accepted_at' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    /**
     * Cancel invitation
     */
    public function cancelInvitation(int $id): bool
    {
        return $this->update($id, [
            'status' => 'cancelled'
        ]);
    }

    /**
     * Get pending invitations with relationships
     */
    public function getPendingInvitations(int $limit = 50): array
    {
        $builder = $this->builder();
        
        $builder->select('contact_invitations.*, 
                         clients.name as client_name,
                         contact_groups.name as group_name,
                         contact_groups.color as group_color,
                         users.first_name as sender_first_name,
                         users.last_name as sender_last_name')
                ->join('clients', 'clients.id = contact_invitations.client_id', 'left')
                ->join('contact_groups', 'contact_groups.id = contact_invitations.assigned_group_id', 'left')
                ->join('users', 'users.id = contact_invitations.sent_by', 'left')
                ->where('contact_invitations.status', 'pending')
                ->orderBy('contact_invitations.created_at', 'DESC')
                ->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get invitations by group
     */
    public function getInvitationsByGroup(int $groupId): array
    {
        $builder = $this->builder();
        
        $builder->select('contact_invitations.*, 
                         clients.name as client_name,
                         users.first_name as sender_first_name,
                         users.last_name as sender_last_name')
                ->join('clients', 'clients.id = contact_invitations.client_id', 'left')
                ->join('users', 'users.id = contact_invitations.sent_by', 'left')
                ->where('contact_invitations.assigned_group_id', $groupId)
                ->orderBy('contact_invitations.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get invitation statistics
     */
    public function getInvitationStats(): array
    {
        $stats = [
            'pending' => $this->where('status', 'pending')
                             ->where('expires_at >', date('Y-m-d H:i:s'))
                             ->countAllResults(),
            'accepted' => $this->where('status', 'accepted')->countAllResults(),
            'expired' => $this->where('status', 'pending')
                             ->where('expires_at <=', date('Y-m-d H:i:s'))
                             ->countAllResults(),
            'cancelled' => $this->where('status', 'cancelled')->countAllResults(),
        ];
        
        $stats['total'] = array_sum($stats);
        
        return $stats;
    }

    /**
     * Check if user exists by email using Shield's auth_identities
     */
    public function userExistsByEmail(string $email): bool
    {
        $identityModel = new UserIdentityModel();
        
        return $identityModel->where('type', 'email_password')
                            ->where('secret', $email)
                            ->first() !== null;
    }

    /**
     * Mark expired invitations
     */
    public function markExpiredInvitations(): int
    {
        return $this->where('status', 'pending')
                   ->where('expires_at <=', date('Y-m-d H:i:s'))
                   ->set('status', 'expired')
                   ->update();
    }

    /**
     * Clean old invitations (older than 30 days)
     */
    public function cleanOldInvitations(): int
    {
        return $this->where('created_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
                   ->whereIn('status', ['accepted', 'expired', 'cancelled'])
                   ->delete();
    }
} 