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
        // Pengecekan login lebih baik dilakukan per method untuk endpoint API/AJAX
        // agar bisa memberikan respons JSON, bukan redirect.
        $this->wishlistModel = new WishlistModel();
        $this->wisataModel = new WisataModel();
    }

    public function index()
    {
        // Pastikan pengguna login untuk melihat halaman ini
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'));
        }

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
        // Cek login di sini untuk memberikan respons JSON jika gagal
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ]);
        }

        $userId = session()->get('user_id');

        // Cek apakah sudah ada di wishlist
        if ($this->wishlistModel->isInWishlist($userId, $wisataId)) {
            return $this->response->setJSON([
                'success' => true, // Kirim true agar UI tetap di state "Sudah di Wishlist"
                'message' => 'Destinasi ini memang sudah ada di wishlist Anda.'
            ]);
        }

        // Lakukan penambahan
        $result = $this->wishlistModel->addToWishlist($userId, $wisataId);

        // Kirim respons berdasarkan hasil operasi
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke wishlist!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan ke wishlist, silakan coba lagi.'
            ]);
        }
    }

    public function remove($wisataId)
    {
        // Cek login di sini
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ]);
        }

        $userId = session()->get('user_id');
        $result = $this->wishlistModel->removeFromWishlist($userId, $wisataId);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Berhasil dihapus dari wishlist!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus dari wishlist atau item tidak ditemukan.'
            ]);
        }
    }
}
