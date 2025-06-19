<?php

namespace App\Controllers;

use App\Models\WisataModel;
use App\Models\BeritaModel;

class Home extends BaseController
{
    protected $wisataModel;
    protected $beritaModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->beritaModel = new BeritaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Selamat Datang di BanuaTour',
            'wisataPopuler' => $this->wisataModel->getTrendingWisata(4),
            'berita' => $this->beritaModel->getBeritaTerbaru(4),
        ];

        return view('home/index', $data);
    }
}
