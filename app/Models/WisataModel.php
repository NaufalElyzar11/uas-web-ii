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
        'gambar_wisata'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;    /**
     * Dapatkan wisata terbaru (kurang dari 1 bulan)
     */
    public function getWisataTerbaru($limit = 4)
    {
        try {
            // Periksa apakah kolom created_at ada
            if (!in_array('created_at', $this->allowedFields) && !$this->createdField) {
                // Fallback jika tidak ada field created_at
                return $this->orderBy($this->primaryKey, 'DESC')
                        ->limit($limit)
                        ->find();
            }
            
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
    }    /**
     * Dapatkan wisata popular berdasarkan statistik kunjungan
     */
    public function getWisataPopuler($limit = 5)
    {
        try {
            $db = \Config\Database::connect();
            
            // Join with statistik_kunjungan table and get sum of visits
            $query = $db->table('wisata')
                ->select('wisata.*, SUM(statistik_kunjungan.jumlah_pengunjung) as total_kunjungan')
                ->join('statistik_kunjungan', 'statistik_kunjungan.wisata_id = wisata.wisata_id', 'left')
                ->groupBy('wisata.wisata_id')
                ->orderBy('total_kunjungan', 'DESC')
                ->limit($limit);
                
            $result = $query->get()->getResultArray();
            
            // If no results with statistics, fallback to trending_score or recent
            if (empty($result)) {
                if (in_array('trending_score', $this->allowedFields)) {
                    return $this->orderBy('trending_score', 'DESC')
                                ->limit($limit)
                                ->find();
                } else {
                    return $this->orderBy($this->primaryKey, 'DESC')
                                ->limit($limit)
                                ->find();
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error in getWisataPopuler: ' . $e->getMessage());
            
            // Fallback to using trending_score on error
            try {
                if (in_array('trending_score', $this->allowedFields)) {
                    return $this->orderBy('trending_score', 'DESC')
                                ->limit($limit)
                                ->find();
                } else {
                    return $this->orderBy($this->primaryKey, 'DESC')
                                ->limit($limit)
                                ->find();
                }
            } catch (\Exception $e2) {
                log_message('error', 'Error in getWisataPopuler fallback: ' . $e2->getMessage());
                return [];
            }
        }
    }/**
     * Dapatkan wisata berdasarkan daerah user
     */
    public function getWisataTerdekat($userDaerah, $limit = 4)
    {
        try {
            // Periksa apakah kolom daerah ada
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
