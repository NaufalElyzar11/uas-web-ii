<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Riwayat extends BaseController
{
    protected $bookingModel;
    
    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }
        
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        
        // Get bookings based on status
        $upcomingBookings = $this->bookingModel->getUpcomingBookings($userId);
        $completedBookings = $this->bookingModel->getCompletedBookings($userId);
        $canceledBookings = $this->bookingModel->getCanceledBookings($userId);
        
        $data = [
            'title' => 'Riwayat Kunjungan',
            'user' => [
                'user_id' => $userId,
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role'),
                'daerah' => session()->get('daerah') ?? 'Indonesia'
            ],
            'upcomingBookings' => $upcomingBookings,
            'completedBookings' => $completedBookings,
            'canceledBookings' => $canceledBookings
        ];
        
        return view('user/riwayat', $data);
    }
    
    public function cancel($bookingId)
    {
        $userId = session()->get('user_id');
        
        // Check if booking belongs to user
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }
        
        // Check if booking can be canceled (not already completed)
        if ($booking['status'] == 'completed') {
            return redirect()->back()->with('error', 'Booking yang sudah selesai tidak dapat dibatalkan.');
        }
        
        // Update booking status
        $this->bookingModel->update($bookingId, [
            'status' => 'canceled'
        ]);
        
        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
