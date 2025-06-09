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
            'wisata' => $wisata
        ];
        
        return view('destinasi/detail', $data);
    }
}
