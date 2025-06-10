<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use App\Models\WisataModel;

class Wishlist extends BaseController
{
    protected $wishlistModel;
    protected $wisataModel;
    
        public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }
        
        $this->wishlistModel = new WishlistModel();
        $this->wisataModel = new WisataModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $wishlistItems = $this->wishlistModel->getUserWishlist($userId);
        
        $data = [
            'title' => 'Wishlist Wisata',
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => session()->get('daerah') ?? 'Indonesia'
            ],
            'wishlist' => $wishlistItems
        ];
        
        return view('user/wishlist', $data);
    }
    
 public function add($wisataId)
{
    $userId = session()->get('user_id');
    log_message('debug', 'Adding to wishlist: User ID: ' . $userId . ' Wisata ID: ' . $wisataId);
    
    $result = $this->wishlistModel->addToWishlist($userId, $wisataId);
    
    return $this->response->setJSON(['success' => $result]);
}

public function remove($wisataId)
{
    $userId = session()->get('user_id');
    log_message('debug', 'Removing from wishlist: User ID: ' . $userId . ' Wisata ID: ' . $wisataId);
    
    $result = $this->wishlistModel->removeFromWishlist($userId, $wisataId);
    
    return $this->response->setJSON(['success' => $result]);
}


}
