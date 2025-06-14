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

        // Get reviews
        $reviewModel = new \App\Models\ReviewModel();
        $reviews = $reviewModel->getReviewsByWisataId($wisata['wisata_id']);
        $averageRating = $reviewModel->getAverageRating($wisata['wisata_id']);
        
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
            'isInWishlist' => $isInWishlist,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ];
        
        return view('destinasi/detail', $data);
    }

    public function addReview()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silahkan login terlebih dahulu untuk memberikan review'
            ]);
        }

        $rules = [
            'wisata_id' => 'required|numeric',
            'rating' => 'required|numeric|greater_than[0]|less_than[6]',
            'komentar' => 'required|min_length[10]|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Review tidak valid. Rating harus 1-5 dan komentar minimal 10 karakter.'
            ]);
        }
        
        $reviewData = [
            'user_id' => session()->get('user_id'),
            'wisata_id' => $this->request->getPost('wisata_id'),
            'rating' => $this->request->getPost('rating'),
            'komentar' => $this->request->getPost('komentar')
        ];
        
        try {
            $this->reviewModel->insert($reviewData);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Review berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error adding review: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan review'
            ]);
        }
    }
}
