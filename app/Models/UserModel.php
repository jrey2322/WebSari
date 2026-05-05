<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
    ];

    // Find user by email
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // Get all active users
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }
}