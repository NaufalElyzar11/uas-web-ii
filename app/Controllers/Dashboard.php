<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\BeritaModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $wisataModel;
    protected $beritaModel;
    protected $userModel;

    public function __construct()
    {
        // Load model
        $this->wisataModel = new WisataModel();
        $this->beritaModel = new BeritaModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        try {
            // Get user data if logged in
            $userDaerah = 'Kalimantan Selatan'; // Default value
            if (session()->get('isLoggedIn')) {
                $userId = session()->get('user_id');
                $userData = $this->userModel->find($userId);
                $userDaerah = $userData['daerah'] ?? 'Kalimantan Selatan';
            }

            // Fetch data with error handling
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

            $data = [
                'title' => 'Dashboard Wisata Indonesia',
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
        } catch (\Exception $e) {
            log_message('error', '[Dashboard::index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
}