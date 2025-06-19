<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\WisataModel;

class Booking extends BaseController
{
    protected $bookingModel;
    protected $wisataModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        $this->bookingModel = new BookingModel();
        $this->wisataModel = new WisataModel();
    }

    public function index()
    {
        return redirect()->to('riwayat');
    }

    public function create($wisataId)
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Silahkan login terlebih dahulu untuk melakukan pemesanan.');
        }

        $wisata = $this->wisataModel
            ->select('wisata.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = wisata.kategori_id', 'left')
            ->find($wisataId);

        if (!$wisata) {
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Pembelian Tiket',
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

        return view('booking/create', $data);
    }

    public function store()
    {
        $rules = [
            'wisata_id' => 'required|numeric',
            'tanggal_kunjungan' => 'required|valid_date',
            'jumlah_orang' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Form pembelian tidak valid. Periksa kembali input Anda.');
        }

        $wisataId = $this->request->getPost('wisata_id');
        $wisata = $this->wisataModel->find($wisataId);
        if (!$wisata) {
            return redirect()->back()->with('error', 'Destinasi tidak ditemukan.');
        }

        $jumlahOrang = $this->request->getPost('jumlah_orang');
        $totalHarga = $wisata['harga'] * $jumlahOrang;

        $bookingData = [
            'user_id' => session()->get('user_id'),
            'wisata_id' => $wisataId,
            'tanggal_kunjungan' => $this->request->getPost('tanggal_kunjungan'),
            'jumlah_orang' => $jumlahOrang,
            'total_harga' => $totalHarga,
            'status' => 'completed'
        ];

        try {
            $this->bookingModel->insert($bookingData);
            return redirect()->to('riwayat')->with('success', '');
        } catch (\Exception $e) {
            log_message('error', 'Error creating booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat melakukan pembelian.');
        }
    }

    public function completePayment($bookingId)
    {
        $userId = session()->get('user_id');

        // Check if booking belongs to user
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }

        // Simple payment simulation - in real implementation this would connect to a payment gateway
        try {
            // Update booking status to completed after payment
            $this->bookingModel->update($bookingId, [
                'status' => 'completed'
            ]);

            return redirect()->to('riwayat')->with('success', 'Pembayaran berhasil. Terima kasih atas kunjungan Anda!');
        } catch (\Exception $e) {
            log_message('error', 'Error processing payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }
}
