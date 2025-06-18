<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\ReviewModel;
use App\Models\BookingModel;
use App\Models\KategoriModel;

class Destinasi extends BaseController
{
    protected $wisataModel;
    protected $reviewModel;
    protected $bookingModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->reviewModel = new ReviewModel();
        $this->bookingModel = new BookingModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Semua Destinasi Wisata',
            'wisata' => $this->wisataModel->getWisataWithKategori(),
            'kategoriList' => $this->kategoriModel->findAll()
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
        
        $wisata = $this->wisataModel
            ->select('wisata.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->find($id);
        
        if ($wisata === null) {
            return redirect()->to('destinasi')->with('error', 'Destinasi wisata tidak ditemukan');
        }
        $galeri = [];
        
        try {
            $galleryPath = FCPATH . 'uploads/wisata/gallery/' . $id;
            if (is_dir($galleryPath)) {
                $files = scandir($galleryPath);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $galeri[] = 'gallery/' . $id . '/' . $file;
                    }
                }
            }
            
            if (empty($galeri) && !empty($wisata['gambar_wisata'])) {
                $galeri[] = $wisata['gambar_wisata'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading gallery images: ' . $e->getMessage());
            $galeri = [];
        }
        $isInWishlist = false;
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $wishlistModel = new \App\Models\WishlistModel();
            $isInWishlist = $wishlistModel->isInWishlist($userId, $wisata['wisata_id']);
        }

        $reviews = $this->reviewModel->getReviewsByWisataId($wisata['wisata_id']);
        $averageRating = $this->reviewModel->getAverageRating($wisata['wisata_id']);
        $trendingScore = $this->bookingModel->getTotalPengunjung($wisata['wisata_id']);
        
        $data = [
            'title' => $wisata['nama'],
            'wisata' => $wisata,
            'galeri' => $galeri,
            'isInWishlist' => $isInWishlist,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'trendingScore' => $trendingScore
        ];
        
        return view('destinasi/detail', $data);
    }

    public function addReview()
    {
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

    public function deleteReview($reviewId)
{
    $review = $this->reviewModel->find($reviewId);

    if (!$review) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Review tidak ditemukan.'
        ]);
    }

    if ($review['user_id'] != session()->get('user_id')) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Anda tidak memiliki izin untuk menghapus review ini.'
        ]);
    }

    try {
        $this->reviewModel->delete($reviewId);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Review berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error deleting review: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menghapus review.'
        ]);
    }
}

}
