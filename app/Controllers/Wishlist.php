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
        
        // Check if the destination exists
        $wisata = $this->wisataModel->find($wisataId);
        if (!$wisata) {
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }
        
        // Check if already in wishlist
        if ($this->wishlistModel->isInWishlist($userId, $wisataId)) {
            return redirect()->back()->with('info', 'Destinasi sudah ada di wishlist Anda.');
        }
        
        // Add to wishlist
        $this->wishlistModel->insert([
            'user_id' => $userId,
            'wisata_id' => $wisataId
        ]);
        
        return redirect()->back()->with('success', 'Destinasi berhasil ditambahkan ke wishlist.');
    }
    
    public function remove($wishlistId)
    {
        $userId = session()->get('user_id');
        
        // Check if the wishlist item belongs to the user
        $wishlistItem = $this->wishlistModel->find($wishlistId);
        if (!$wishlistItem || $wishlistItem['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Item wishlist tidak ditemukan.');
        }
        
        // Remove from wishlist
        $this->wishlistModel->delete($wishlistId);
        
        return redirect()->to('wishlist')->with('success', 'Destinasi berhasil dihapus dari wishlist.');
    }
}
