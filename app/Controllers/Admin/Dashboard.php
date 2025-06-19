<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WisataModel;
use App\Models\UserModel;
use App\Models\ReviewModel;
use App\Models\BookingModel;

class Dashboard extends BaseController
{
    protected $wisataModel;
    protected $userModel;
    protected $reviewModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'totalWisata' => $this->wisataModel->countAll(),
            'totalUsers' => $this->userModel->countAll(),
            'totalReviews' => $this->reviewModel->countAll(),
            'totalBookings' => $this->bookingModel->countAll(),
            'wisataTerbaru' => $this->wisataModel->select('wisata.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
                ->orderBy('wisata.created_at', 'DESC')->limit(5)->find(),
            'userTerbaru' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->find()
        ];

        return view('admin/dashboard', $data);
    }
}
