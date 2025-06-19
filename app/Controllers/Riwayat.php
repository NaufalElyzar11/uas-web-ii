<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Riwayat extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

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

        $booking = $this->bookingModel->find($bookingId);
        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }

        if ($booking['status'] == 'completed') {
            return redirect()->back()->with('error', 'Booking yang sudah selesai tidak dapat dibatalkan.');
        }

        $this->bookingModel->update($bookingId, [
            'status' => 'canceled'
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function delete($bookingId)
    {
        // Pastikan hanya request AJAX yang dilayani untuk keamanan
        if (!$this->request->isAJAX()) {
            return redirect()->to(base_url('riwayat'));
        }

        // Pastikan pengguna sudah login
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.'])->setStatusCode(401);
        }

        // Ganti 'BookingModel' jika nama model Anda berbeda
        $bookingModel = new BookingModel();
        $userId = session()->get('user_id');

        // 1. Verifikasi dulu bahwa booking ini milik user yang sedang login
        $booking = $bookingModel->where('booking_id', $bookingId)
            ->where('user_id', $userId)
            ->first();

        // Jika tidak ditemukan, jangan lanjutkan
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Riwayat tidak ditemukan atau Anda tidak memiliki izin.'])->setStatusCode(404);
        }

        // 2. Lakukan penghapusan
        if ($bookingModel->delete($bookingId)) {
            // Jika berhasil, kirim respons sukses
            return $this->response->setJSON(['success' => true, 'message' => 'Riwayat berhasil dihapus.']);
        } else {
            // Jika gagal, kirim respons error
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus riwayat dari database.'])->setStatusCode(500);
        }
    }

    /**
     * Mengambil data tiket untuk ditampilkan di modal via AJAX
     * dan membuat kode tiket jika belum ada.
     */
    public function showTicket($booking_id)
    {
        // Pastikan ini adalah request AJAX untuk keamanan
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $bookingModel = new BookingModel(); // Ganti dengan nama model Anda
        $userId = session()->get('user_id'); // Ambil ID user dari session

        // 1. Ambil data booking DAN pastikan booking ini milik user yang sedang login
        $booking = $bookingModel->where('booking_id', $booking_id)
            ->where('user_id', $userId)
            ->first();

        $booking = $bookingModel
            ->select('bookings.*, wisata.nama as nama_wisata') // Ambil semua dari tabel bookings, dan kolom 'nama' dari tabel wisata (diberi alias 'nama_wisata')
            ->join('wisata', 'wisata.wisata_id = bookings.wisata_id') // Sesuaikan nama tabel dan kolom penghubung
            ->where('bookings.booking_id', $booking_id)
            ->where('bookings.user_id', $userId)
            ->first();

        if (!$booking) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Booking tidak ditemukan atau Anda tidak memiliki akses.'])->setStatusCode(404);
        }

        // 2. Cek apakah kode tiket sudah ada
        if (empty($booking['kode_tiket'])) {
            // Jika belum ada, buat kode unik baru
            $prefix = "TIKET";
            $uniqueCode = $prefix . "-" . strtoupper(substr(md5($booking['booking_id']), 0, 6)) . "-" . time();

            // Simpan kode unik ini ke database agar permanen
            $bookingModel->update($booking_id, ['kode_tiket' => $uniqueCode]);

            // Set kode tiket yang baru dibuat ke variabel booking
            $booking['kode_tiket'] = $uniqueCode;
        }

        // 3. Siapkan data yang akan dikirim sebagai JSON ke frontend
        // Kita tidak mengirim semua data, hanya yang perlu saja.
        $ticketData = [
            'nama_wisata'   => $booking['nama_wisata'], // Asumsi nama wisata ada di kolom 'nama'
            'jumlah_orang'  => $booking['jumlah_orang'],
            'total_harga'   => "Rp " . number_format($booking['total_harga'], 0, ',', '.'),
            'kode_tiket'    => $booking['kode_tiket']
        ];

        return $this->response->setJSON(['status' => 'success', 'data' => $ticketData]);
    }
}
