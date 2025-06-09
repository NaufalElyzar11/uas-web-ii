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
     * Dapatkan 5 wisata dengan trending_score tertinggi
     */
    public function getWisataPopuler($limit = 5)
    {
        try {
            // Periksa apakah kolom trending_score ada
            if (!in_array('trending_score', $this->allowedFields)) {
                log_message('warning', 'trending_score field not found, using default sorting');
                return $this->orderBy($this->primaryKey, 'DESC')
                            ->limit($limit)
                            ->find();
            }
            
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
}
