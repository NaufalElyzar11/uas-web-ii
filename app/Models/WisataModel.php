<?php

namespace App\Models;

use CodeIgniter\Model;

class WisataModel extends Model
{
    protected $table            = 'wisata';
    protected $primaryKey       = 'wisata_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'daerah', 
        'deskripsi', 
        'harga', 
        'kategori', 
        'trending_score', 
        'gambar_wisata',
        'link_video',
        'latitude',
        'longitude'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Dapatkan wisata terbaru (kurang dari 1 bulan)
     */
    public function getWisataTerbaru($limit = 4)
    {
        try {
            $date = new \DateTime();
            $date->sub(new \DateInterval('P1M')); // 1 bulan yang lalu
            $monthAgo = $date->format('Y-m-d H:i:s');

            return $this->where('created_at >=', $monthAgo)
                        ->orderBy('created_at', 'DESC')
                        ->limit($limit)
                        ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in getWisataTerbaru: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan 5 wisata dengan trending_score tertinggi
     */
    public function getWisataPopuler($limit = 5)
    {
        try {
            return $this->orderBy('trending_score', 'DESC')
                        ->limit($limit)
                        ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in getWisataPopuler: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan wisata berdasarkan daerah user
     */
    public function getWisataTerdekat($userDaerah, $limit = 4)
    {
        try {
            return $this->where('daerah', $userDaerah)
                        ->orderBy($this->primaryKey, 'DESC')
                        ->limit($limit)
                        ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in getWisataTerdekat: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search wisata by keyword (nama or daerah)
     */
    public function search($keyword)
    {
        try {
            return $this->like('nama', $keyword)
                      ->orLike('daerah', $keyword)
                      ->orLike('deskripsi', $keyword)
                      ->orLike('kategori', $keyword)
                      ->orderBy($this->primaryKey, 'DESC')
                      ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in search: ' . $e->getMessage());
            return [];
        }
    }
}
