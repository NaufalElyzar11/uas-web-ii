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
        'trending_score'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;    

    public function getTrendingWisata($limit = 4)
    {
        return $this->select('wisata.*, SUM(bookings.jumlah_orang) as total_kunjungan')
            ->join('bookings', 'bookings.wisata_id = wisata.wisata_id', 'left')
            ->groupBy('wisata.wisata_id')
            ->orderBy('total_kunjungan', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function getPopularWisata($limit = 4)
    {
        return $this->getTrendingWisata($limit);
    }

    public function getWisataTerbaru($limit = 4)
    {
        try {
            if (!in_array('created_at', $this->allowedFields) && !$this->createdField) {
                return $this->orderBy($this->primaryKey, 'DESC')
                        ->limit($limit)
                        ->find();
            }
            
            $date = new \DateTime();
            $date->sub(new \DateInterval('P1M')); 
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

    public function getWisataTerdekat($userDaerah, $limit = 4)
    {
        try {
            if (!in_array('daerah', $this->allowedFields)) {
                log_message('warning', 'daerah field not found, returning all wisata');
                return $this->orderBy($this->primaryKey, 'DESC')
                            ->limit($limit)
                            ->find();
            }
            
            return $this->where('daerah', $userDaerah)
                        ->orderBy($this->primaryKey, 'DESC')
                        ->limit($limit)
                        ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in getWisataTerdekat: ' . $e->getMessage());
            return [];
        }
    }
    
    public function searchWisata($keyword)
    {
        try {
            if (in_array('trending_score', $this->allowedFields)) {
                return $this->orderBy('trending_score', 'DESC')
                        ->like('nama', $keyword)
                        ->orLike('daerah', $keyword)
                        ->findAll();
            }
            return $this->like('nama', $keyword)
                        ->orLike('daerah', $keyword)
                        ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error in searchWisata: ' . $e->getMessage());
            return [];
        }
    }
}
