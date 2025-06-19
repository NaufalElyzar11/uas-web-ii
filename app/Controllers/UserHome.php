<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\BeritaModel;
use App\Models\UserModel;
use App\Models\ReviewModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\LocationModel;

class UserHome extends BaseController
{
    protected $wisataModel;
    protected $beritaModel;
    protected $userModel;
    protected $reviewModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        $this->wisataModel = new WisataModel();
        $this->beritaModel = new BeritaModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }
    public function index()
    {
        try {
            $userId = session()->get('user_id');
            $userData = $this->userModel->find($userId);
            $userDaerah = $userData['daerah'] ?? 'Kalimantan Selatan';

            try {
                $wisataTerbaru = $this->wisataModel->getWisataTerbaru(4);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataTerbaru: ' . $e->getMessage());
                $wisataTerbaru = [];
            }

            try {
                $wisataTrending = $this->wisataModel->getTrendingWisata(4);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataTrending: ' . $e->getMessage());
                $wisataTrending = [];
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
            try {
                $wisataRekomendasi = $this->wisataModel->getRekomendasiWisataByUserMinat($userId, 4);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching wisataRekomendasi: ' . $e->getMessage());
                $wisataRekomendasi = [];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in Dashboard index: ' . $e->getMessage());

            $wisataTerbaru = [];
            $wisataTrending = [];
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
            'wisataTrending' => $wisataTrending,
            'wisataTerdekat' => $wisataTerdekat,
            'berita' => $berita,
            'wisataRekomendasi' => $wisataRekomendasi,
            'currentDate' => date('d M Y')
        ];

        log_message('debug', 'Item counts - Wisata Terbaru: ' . count($wisataTerbaru) .
            ', Wisata Trending: ' . count($wisataTrending) .
            ', Wisata Terdekat: ' . count($wisataTerdekat) .
            ', Berita: ' . count($berita));

        return view('user/index', $data);
    }

    public function import()
    {
        $rules = [
            'excel_file' => 'uploaded[excel_file]|max_size[excel_file,5120]|ext_in[excel_file,xlsx,xls]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('excel_file');
        $spreadsheet = IOFactory::load($file->getTempName());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $dataToInsert = [];
        $dbColumnName = 'location_name'; // Kolom di database

        foreach (array_slice($rows, 1) as $row) {
            if (!empty(trim($row[0] ?? ''))) {
                $dataToInsert[] = [$dbColumnName => trim($row[0])];
            }
        }

        if (empty($dataToInsert)) {
            return redirect()->back()->with('error', 'Tidak ada data valid untuk diimpor.');
        }

        $locationModel = new LocationModel();
        $locationModel->ignore(true)->insertBatch($dataToInsert);

        $count = $locationModel->db->affectedRows();
        return redirect()->back()->with('success', "{$count} data lokasi baru berhasil diimpor.");
    }
}
