<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table            = 'wishlists';
    protected $primaryKey       = 'wishlist_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'wisata_id',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    /**
     * Get user's wishlist items with wisata details
     *
     * @param int $userId
     * @return array
     */
    public function getUserWishlist($userId)
    {
        return $this->select('wishlists.*, wisata.nama, wisata.daerah, wisata.harga, wisata.gambar_wisata, wisata.kategori')
            ->join('wisata', 'wisata.wisata_id = wishlists.wisata_id')
            ->where('wishlists.user_id', $userId)
            ->orderBy('wishlists.created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Check if a destination is already in user's wishlist
     *
     * @param int $userId
     * @param int $wisataId
     * @return bool
     */
    public function isInWishlist($userId, $wisataId)
    {
        $result = $this->where('user_id', $userId)
            ->where('wisata_id', $wisataId)
            ->first();
        
        return $result ? true : false;
    }
}
