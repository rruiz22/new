<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{
    // Add getters and setters for our custom fields
    protected $first_name;
    protected $last_name;
    protected $user_type;
    protected $phone;
    
    // Getter for first_name
    public function getFirstName(): ?string
    {
        return $this->attributes['first_name'] ?? $this->first_name ?? null;
    }
    
    // Setter for first_name
    public function setFirstName(?string $first_name): self
    {
        $this->attributes['first_name'] = $first_name;
        $this->first_name = $first_name;
        return $this;
    }
    
    // Getter for last_name
    public function getLastName(): ?string
    {
        return $this->attributes['last_name'] ?? $this->last_name ?? null;
    }
    
    // Setter for last_name
    public function setLastName(?string $last_name): self
    {
        $this->attributes['last_name'] = $last_name;
        $this->last_name = $last_name;
        return $this;
    }
    
    // Getter for user_type
    public function getUserType(): ?string
    {
        return $this->attributes['user_type'] ?? $this->user_type ?? null;
    }
    
    // Setter for user_type
    public function setUserType(?string $user_type): self
    {
        $this->attributes['user_type'] = $user_type;
        $this->user_type = $user_type;
        return $this;
    }
    
    // Getter for phone
    public function getPhone(): ?string
    {
        return $this->attributes['phone'] ?? $this->phone ?? null;
    }
    
    // Setter for phone
    public function setPhone(?string $phone): self
    {
        $this->attributes['phone'] = $phone;
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * Override fill() method to handle custom attributes
     */
    public function fill(array $data = null): self
    {
        parent::fill($data);
        
        // Set custom fields if they exist in the data
        if (isset($data['first_name'])) {
            $this->setFirstName($data['first_name']);
        }
        
        if (isset($data['last_name'])) {
            $this->setLastName($data['last_name']);
        }
        
        if (isset($data['user_type'])) {
            $this->setUserType($data['user_type']);
        }
        
        if (isset($data['phone'])) {
            $this->setPhone($data['phone']);
        }
        
        return $this;
    }
} 