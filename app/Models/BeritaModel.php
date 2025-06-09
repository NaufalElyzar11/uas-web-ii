<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table            = 'berita';
    protected $primaryKey       = 'berita_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul',
        'konten',
        'wisata_id',
        'tanggal_post'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';    /**
     * Dapatkan berita terbaru beserta informasi wisata terkait
     */
    public function getBeritaTerbaru($limit = 6)
    {
        try {
            // Periksa apakah kolom tanggal_post ada
            $sortField = in_array('tanggal_post', $this->allowedFields) ? 'berita.tanggal_post' : 'berita.' . $this->primaryKey;
            
            return $this->select('berita.*, wisata.nama, wisata.gambar_wisata')
                        ->join('wisata', 'wisata.wisata_id = berita.wisata_id', 'left')
                        ->orderBy($sortField, 'DESC')
                        ->limit($limit)
                        ->find();
        } catch (\Exception $e) {
            log_message('error', 'Error in getBeritaTerbaru: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan detail berita dengan informasi wisata
     */
    public function getBeritaDetail($beritaId)
    {
        try {
            return $this->select('berita.*, wisata.nama, wisata.daerah, wisata.gambar_wisata')
                        ->join('wisata', 'wisata.wisata_id = berita.wisata_id', 'left')
                        ->where('berita.' . $this->primaryKey, $beritaId)
                        ->first();
        } catch (\Exception $e) {
            log_message('error', 'Error in getBeritaDetail: ' . $e->getMessage());
            return null;
        }
    }
}
