<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table         = 'contacts';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['client_id', 'user_id', 'name', 'position', 'email', 'phone', 'is_primary', 'status'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'client_id' => 'required|integer',
        'user_id'   => 'permit_empty|integer',
        'name'      => 'required|min_length[3]|max_length[255]',
        'position'  => 'permit_empty|max_length[255]',
        'email'     => 'required|valid_email|max_length[255]',
        'phone'     => 'permit_empty|max_length[50]',
        'is_primary' => 'permit_empty|in_list[0,1]',
        'status'    => 'permit_empty|in_list[active,inactive]',
    ];
    
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    /**
     * Get contacts by client ID
     *
     * @param int $clientId
     * @return array
     */
    public function getContactsByClientId($clientId)
    {
        return $this->where('client_id', $clientId)->findAll();
    }
    
    /**
     * Get primary contact for a client
     *
     * @param int $clientId
     * @return array|null
     */
    public function getPrimaryContact($clientId)
    {
        return $this->where('client_id', $clientId)
                    ->where('is_primary', 1)
                    ->first();
    }
    
    /**
     * Get contact with client details
     *
     * @param int $id Contact ID
     * @return array|null
     */
    public function getContactWithClient($id)
    {
        $contact = $this->find($id);
        
        if (!$contact) {
            return null;
        }
        
        $clientModel = new ClientModel();
        $contact['client'] = $clientModel->find($contact['client_id']);
        
        return $contact;
    }
} 