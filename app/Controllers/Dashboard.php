<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\BeritaModel;
use App\Models\UserModel;
use App\Models\ReviewModel;

class Dashboard extends BaseController
{   protected $wisataModel;
    protected $beritaModel;
    protected $userModel;
    protected $reviewModel;
    protected $statistikModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        // Load model
        $this->wisataModel = new WisataModel();
        $this->beritaModel = new BeritaModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }    public function index()
    {
        try {
            // Ambil data user yang sedang login
            $userId = session()->get('user_id');
            $userData = $this->userModel->find($userId);
            $userDaerah = $userData['daerah'] ?? 'Kalimantan Selatan'; // Default jika tidak ada

            // Ambil data dari database dengan error handling
            try {
                $wisataTerbaru = $this->wisataModel->getWisataTerbaru(4);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataTerbaru: ' . $e->getMessage());
                $wisataTerbaru = [];
            }
            
            try {
                $wisataPopuler = $this->wisataModel->getWisataPopuler(5);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataPopuler: ' . $e->getMessage());
                $wisataPopuler = [];
            }
            
            try {
                $wisataTerdekat = $this->wisataModel->getWisataTerdekat($userDaerah, 4);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataTerdekat: ' . $e->getMessage());
                $wisataTerdekat = [];
            }
              try {
                $berita = $this->beritaModel->getBeritaTerbaru(6);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching berita: ' . $e->getMessage());
                $berita = [];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in Dashboard index: ' . $e->getMessage());
            
            // Set default values for all data in case of error
            $wisataTerbaru = [];
            $wisataPopuler = [];
            $wisataTerdekat = [];
            $berita = [];
            $userDaerah = 'Indonesia';
            
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
        }
          $data = [
            'title' => 'Dashboard Wisata Indonesia',
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => $userDaerah
            ],
            'wisataTerbaru' => $wisataTerbaru,
            'wisataPopuler' => $wisataPopuler,
            'wisataTerdekat' => $wisataTerdekat,
            'berita' => $berita,
            'currentDate' => date('d M Y')
        ];
        
        // Log count of items for debugging
        log_message('debug', 'Item counts - Wisata Terbaru: ' . count($wisataTerbaru) . 
                            ', Wisata Populer: ' . count($wisataPopuler) . 
                            ', Wisata Terdekat: ' . count($wisataTerdekat) .
                            ', Berita: ' . count($berita));
        
        return view('dashboard/index', $data);
    }
}