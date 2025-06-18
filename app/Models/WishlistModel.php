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
        return $this->select('wishlist.wishlist_id, wisata.nama, wisata.daerah, kategori.nama_kategori, wisata.harga, wisata.gambar_wisata, wisata.wisata_id')
                    ->join('wisata', 'wisata.wisata_id = wishlist.wisata_id')
                    ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
                    ->where('wishlist.user_id', $userId)
                    ->findAll();
    }

    public function isInWishlist($userId, $wisataId)
    {
        return $this->where('user_id', $userId)
                    ->where('wisata_id', $wisataId)
                    ->countAllResults() > 0;
    }

    public function addToWishlist($userId, $wisataId)
    {
        $data = [
            'user_id' => $userId,
            'wisata_id' => $wisataId
        ];
        if ($this->insert($data)) {
        return true;  
    } 
        return false;
    }

    public function removeFromWishlist($userId, $wisataId)
    {
        return $this->where('user_id', $userId)
                    ->where('wisata_id', $wisataId)
                    ->delete(); {
                        return true;
                    }
        return false;
    }
}
