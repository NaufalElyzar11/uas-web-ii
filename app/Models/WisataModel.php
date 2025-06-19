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
        'kategori_id',
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

    public function getWisataWithKategori()
    {
        return $this->select('wisata.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->findAll();
    }

    public function getTrendingWisata($limit = 4)
    {
        return $this->select('wisata.*, kategori.nama_kategori, COALESCE(SUM(bookings.jumlah_orang),0) as total_kunjungan')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->join('bookings', 'bookings.wisata_id = wisata.wisata_id', 'left')
            ->groupBy('wisata.wisata_id, kategori.nama_kategori')
            ->orderBy('total_kunjungan', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function getWisataTerbaru($limit = 4)
    {
        try {
            $builder = $this->select('wisata.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left');

            if (!in_array('created_at', $this->allowedFields) && !$this->createdField) {
                return $builder->orderBy('wisata.wisata_id', 'DESC')
                    ->limit($limit)
                    ->find();
            }

            $date = new \DateTime();
            $date->sub(new \DateInterval('P1M'));
            $monthAgo = $date->format('Y-m-d H:i:s');

            return $builder->where('wisata.created_at >=', $monthAgo)
                ->orderBy('wisata.created_at', 'DESC')
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
            $builder = $this->select('wisata.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left');

            if (!in_array('daerah', $this->allowedFields)) {
                log_message('warning', 'daerah field not found, returning all wisata');
                return $builder->orderBy('wisata.wisata_id', 'DESC')
                    ->limit($limit)
                    ->find();
            }

            return $builder->where('daerah', $userDaerah)
                ->orderBy('wisata.wisata_id', 'DESC')
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
            $builder = $this->select('wisata.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
                ->like('wisata.nama', $keyword)
                ->orLike('wisata.daerah', $keyword)
                ->orLike('kategori.nama_kategori', $keyword);

            if (in_array('trending_score', $this->allowedFields)) {
                return $builder->orderBy('trending_score', 'DESC')->findAll();
            }
            return $builder->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error in searchWisata: ' . $e->getMessage());
            return [];
        }
    }

    public function getFirstGalleryImage($wisataId)
    {
        $galleryPath = FCPATH . 'uploads/wisata/gallery/' . $wisataId;
        if (is_dir($galleryPath)) {
            $files = scandir($galleryPath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                    return base_url('uploads/wisata/gallery/' . $wisataId . '/' . $file);
                }
            }
        }
        return null;
    }

    public function getRekomendasiWisataByUserMinat($userId, $limit = 4)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('wisata')
            ->select('wisata.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->join('minat_user', 'minat_user.kategori_id = wisata.kategori_id', 'inner')
            ->where('minat_user.user_id', $userId)
            ->limit($limit);
        return $builder->get()->getResultArray();
    }
}
