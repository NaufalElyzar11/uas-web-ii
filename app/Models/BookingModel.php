<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'booking_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'wisata_id',
        'tanggal_kunjungan',
        'jumlah_orang',
        'total_harga',
        'status',
        'created_at',
        'tanggal_booking',
        'status_pembayaran',
        'bukti_pembayaran',
        'kode_booking',
        'kode_tiket'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getUserBookings($userId, $status = null)
    {
        $query = $this->select('bookings.*, wisata.nama, wisata.daerah, wisata.harga, wisata.gambar_wisata, kategori.nama_kategori')
            ->join('wisata', 'wisata.wisata_id = bookings.wisata_id')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->where('bookings.user_id', $userId);

        if ($status) {
            $query->where('bookings.status', $status);
        }

        return $query->orderBy('bookings.tanggal_kunjungan', 'DESC')
            ->findAll();
    }

    public function getUpcomingBookings($userId)
    {
        return $this->getUserBookings($userId, 'upcoming');
    }

    public function getCompletedBookings($userId)
    {
        return $this->getUserBookings($userId, 'completed');
    }

    public function getCanceledBookings($userId)
    {
        return $this->getUserBookings($userId, 'canceled');
    }

    public function getTotalPengunjung($wisataId)
    {
        $result = $this->selectSum('jumlah_orang')
            ->where('wisata_id', $wisataId)
            ->first();

        return $result['jumlah_orang'] ?? 0;
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
}
