<?php

namespace App\Controllers;

use App\Models\WisataModel;

class Destinasi extends BaseController
{
    protected $wisataModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        // Load model
        $this->wisataModel = new WisataModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Semua Destinasi Wisata',
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => session()->get('daerah') ?? 'Indonesia'
            ],
            'wisata' => $this->wisataModel->findAll()
        ];
        
        return view('destinasi/index', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        
        $wisata = [];
        if (!empty($keyword)) {
            $wisata = $this->wisataModel->search($keyword);
        }
        
        $data = [
            'title' => 'Hasil Pencarian: ' . $keyword,
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => session()->get('daerah') ?? 'Indonesia'
            ],
            'keyword' => $keyword,
            'wisata' => $wisata
        ];
        
        return view('destinasi/search', $data);
    }
      public function detail($id = null)
    {
        if ($id === null) {
            return redirect()->to('destinasi');
        }
        
        $wisata = $this->wisataModel->find($id);
        
        if ($wisata === null) {
            return redirect()->to('destinasi')->with('error', 'Destinasi wisata tidak ditemukan');
        }
        // Get gallery images for this destination
        $galeri = [];
        
        try {
            // Check if there's a gallery directory for this destination
            $galleryPath = FCPATH . 'uploads/wisata/gallery/' . $id;
            if (is_dir($galleryPath)) {
                // Get all images from the gallery directory
                $files = scandir($galleryPath);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $galeri[] = 'gallery/' . $id . '/' . $file;
                    }
                }
            }
            
            // If no gallery images found, use the main image
            if (empty($galeri) && !empty($wisata['gambar_wisata'])) {
                $galeri[] = $wisata['gambar_wisata'];
            }
        } catch (\Exception $e) {
            // If any error occurs, just use an empty gallery
            log_message('error', 'Error loading gallery images: ' . $e->getMessage());
            $galeri = [];
        }
        // Wishlist status
        $isInWishlist = false;
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $wishlistModel = new \App\Models\WishlistModel();
            $isInWishlist = $wishlistModel->isInWishlist($userId, $wisata['wisata_id']);
        }
        
        $data = [
            'title' => $wisata['nama'],
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => session()->get('daerah') ?? 'Indonesia'
            ],
            'wisata' => $wisata,
            'galeri' => $galeri,
            'isInWishlist' => $isInWishlist
        ];
        
        return view('destinasi/detail', $data);
    }
}
