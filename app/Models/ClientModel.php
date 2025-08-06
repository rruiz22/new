<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table         = 'clients';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['name', 'email', 'phone', 'address', 'website', 'tax_number', 'status'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'email'    => 'permit_empty|valid_email|max_length[255]',
        'phone'    => 'permit_empty|max_length[50]',
        'address'  => 'permit_empty',
        'website'  => 'permit_empty|max_length[255]',
        'tax_number' => 'permit_empty|max_length[50]',
        'status'   => 'permit_empty|in_list[active,inactive]',
    ];
    
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    /**
     * Get active clients
     *
     * @return array
     */
    public function getActiveClients()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get client with contacts
     *
     * @param int $id Client ID
     * @return array
     */
    public function getClientWithContacts($id)
    {
        $client = $this->find($id);
        
        if (!$client) {
            return null;
        }
        
        $contactModel = new ContactModel();
        $client['contacts'] = $contactModel->where('client_id', $id)->findAll();
        
        return $client;
    }
} 