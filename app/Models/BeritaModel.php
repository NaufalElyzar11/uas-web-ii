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
        'gambar',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    protected $validationRules = [
        'judul' => 'required|min_length[5]|max_length[255]',
        'konten' => 'required|min_length[10]',
        'status' => 'required|in_list[published,draft]'
    ];

    protected $validationMessages = [
        'judul' => [
            'required' => 'Judul berita harus diisi',
            'min_length' => 'Judul berita minimal 5 karakter',
            'max_length' => 'Judul berita maksimal 255 karakter'
        ],
        'konten' => [
            'required' => 'Konten berita harus diisi',
            'min_length' => 'Konten berita minimal 10 karakter'
        ],
        'status' => [
            'required' => 'Status berita harus diisi',
            'in_list' => 'Status berita harus published atau draft'
        ]
    ];

    public function getBeritaTerbaru($limit = 6)
    {
        try {
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

    public function getPublishedBerita($limit = null)
    {
        $builder = $this->builder();
        $builder->where('status', 'published');
        $builder->orderBy('created_at', 'DESC');

        if ($limit !== null) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }
}
