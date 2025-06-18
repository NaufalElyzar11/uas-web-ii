<?php

namespace App\Models;

use CodeIgniter\Model;

class MinatUserModel extends Model
{
    protected $table = 'minat_user';
    protected $primaryKey = ['user_id', 'kategori_id'];
    protected $allowedFields = ['user_id', 'kategori_id'];

    public function getUserInterests($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
} 