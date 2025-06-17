<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class Booking extends BaseController
{
    protected $bookingModel;
    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Booking',
            'bookings' => $this->bookingModel->findAll()
        ];
        return view('admin/booking/index', $data);
    }
} 