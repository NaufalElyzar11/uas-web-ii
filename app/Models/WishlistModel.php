<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table = 'wishlist';
    protected $primaryKey = 'wishlist_id';
    protected $allowedFields = ['user_id', 'wisata_id'];

    public function getUserWishlist($userId)
    {
        return $this->select('wishlist.wishlist_id, wisata.nama, wisata.daerah, wisata.kategori, wisata.harga, wisata.gambar_wisata, wisata.wisata_id')
                    ->join('wisata', 'wisata.wisata_id = wishlist.wisata_id')
                    ->where('wishlist.user_id', $userId)
                    ->findAll();
    }

    public function isInWishlist($userId, $wisataId)
    {
        return $this->where('user_id', $userId)
                    ->where('wisata_id', $wisataId)
                    ->countAllResults() > 0;
    }
}
