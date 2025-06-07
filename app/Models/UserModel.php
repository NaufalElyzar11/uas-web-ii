<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = [
        'nama',
        'username',
        'email',
        'password',
        'daerah',
        'jenis_kelamin',
        'umur',
        'role'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    
    protected function beforeInsert(array $data)
    {
        $data = $this->getUpdatedDataWithHashedPassword($data);
        log_message('debug', 'Before Insert Data: ' . json_encode($data));
        return $data;
    }
    
    protected function beforeUpdate(array $data)
    {
        $data = $this->getUpdatedDataWithHashedPassword($data);
        log_message('debug', 'Before Update Data: ' . json_encode($data));
        return $data;
    }
    
    private function getUpdatedDataWithHashedPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            log_message('debug', 'Hashing password for: ' . ($data['data']['username'] ?? 'unknown user'));
            $data['data']['password'] = password_hash($plaintextPassword, PASSWORD_DEFAULT);
        }
        return $data;
    }
} 