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
        'status', // 'upcoming', 'completed', 'canceled'
        'created_at',
        'tanggal_booking',
        'status_pembayaran',
        'bukti_pembayaran',
        'kode_booking'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get user's bookings with destination details
     *
     * @param int $userId
     * @param string $status Filter by status (optional)
     * @return array
     */
    public function getUserBookings($userId, $status = null)
    {
        $query = $this->select('bookings.*, wisata.nama, wisata.daerah, wisata.harga, wisata.gambar_wisata, wisata.kategori')
            ->join('wisata', 'wisata.wisata_id = bookings.wisata_id')
            ->where('bookings.user_id', $userId);
        
        if ($status) {
            $query->where('bookings.status', $status);
        }
        
        return $query->orderBy('bookings.tanggal_kunjungan', 'DESC')
            ->findAll();
    }
    
    /**
     * Get upcoming bookings for a user
     *
     * @param int $userId
     * @return array
     */
    public function getUpcomingBookings($userId)
    {
        return $this->getUserBookings($userId, 'upcoming');
    }
    
    /**
     * Get completed bookings for a user
     *
     * @param int $userId
     * @return array
     */
    public function getCompletedBookings($userId)
    {
        return $this->getUserBookings($userId, 'completed');
    }
    
    /**
     * Get canceled bookings for a user
     *
     * @param int $userId
     * @return array
     */
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
}
