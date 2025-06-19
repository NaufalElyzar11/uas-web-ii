<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/riwayat.css') ?>">

    <header>
        <h1>Riwayat Anda</h1>
    </header>

<div class="orders-container">
    
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($upcomingBookings)): ?>
        <?php foreach ($upcomingBookings as $booking): ?>
        <div class="order-item">
            <a href="javascript:void(0);" 
           class="btn-delete-history" 
           data-booking-id="<?= $booking['booking_id'] ?>" 
           title="Hapus riwayat ini">
            &times;
        </a>

            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag upcoming">AKAN DATANG</span>

                     <div class="order-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            <?php
                            echo \CodeIgniter\I18n\Time::parse($booking['tanggal_kunjungan'], 'Asia/Makassar', 'id_ID')
                            ->toLocalizedString('d MMMM yyyy');
                            ?>
                            </span>
                        </div>
                </div>
            </div>

            <div class="product-details">
                <img src="<?= (filter_var($booking['gambar_wisata'], FILTER_VALIDATE_URL)) ? $booking['gambar_wisata'] : base_url('uploads/wisata/' . ($booking['gambar_wisata'] ?? 'default.jpg')) ?>" 
                    alt="<?= esc($booking['nama']) ?>" 
                    class="product-image">
                <div class="product-info-text">
                    <p class="product-name"><?= esc($booking['nama']) ?></p>
                    <p class="product-quantity">x<?= $booking['jumlah_orang'] ?> orang</p>
                </div>
                <div class="product-price">
                    <span class="discounted-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="order-footer">
                <div class="total-price-summary">
                    <span>Total Harga:</span>
                    <span class="total-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="order-actions-bottom">
                    <a href="<?= base_url('riwayat/cancel/' . $booking['booking_id']) ?>" 
                       class="btn-action secondary-btn"
                       onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                        Batalkan
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($completedBookings)): ?>
        <?php foreach ($completedBookings as $booking): ?>
        <div class="order-item">
            <a href="javascript:void(0);" 
           class="btn-delete-history" 
           data-booking-id="<?= $booking['booking_id'] ?>" 
           title="Hapus riwayat ini">
            &times;
           </a>

            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag delivered">SELESAI</span>

                    <div class="order-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            <?php
                            echo \CodeIgniter\I18n\Time::parse($booking['tanggal_kunjungan'], 'Asia/Makassar', 'id_ID')
                            ->toLocalizedString('d MMMM yyyy');
                            ?>
                            </span>
                        </div>
                </div>
            </div>

            <div class="product-details">
                <img src="<?= (filter_var($booking['gambar_wisata'], FILTER_VALIDATE_URL)) ? $booking['gambar_wisata'] : base_url('uploads/wisata/' . ($booking['gambar_wisata'] ?? 'default.jpg')) ?>" 
                    alt="<?= esc($booking['nama']) ?>" 
                    class="product-image">
                <div class="product-info-text">
                    <p class="product-name"><?= esc($booking['nama']) ?></p>
                    <p class="product-quantity">x<?= $booking['jumlah_orang'] ?> orang</p>
                </div>
                <div class="product-price">
                    <span class="discounted-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="order-footer">
                <div class="total-price-summary">
                    <span>Total Harga:</span>
                    <span class="total-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="order-actions-bottom">
                <a href="javascript:void(0);" 
                    onclick="openTicketModal('<?= $booking['booking_id'] ?>')" 
                    class="btn-action primary-btn">
                    Lihat Tiket
                </a>
                    <a href="javascript:void(0);" onclick="openReviewModal('<?= $booking['wisata_id'] ?>')" class="btn-action primary-btn">Nilai</a>
                    <a href="<?= base_url('booking/pembelian/' . $booking['wisata_id']) ?>" class="btn-action secondary-btn">Beli Lagi</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($canceledBookings)): ?>
        <?php foreach ($canceledBookings as $booking): ?>
        <div class="order-item">
            <a href="javascript:void(0);" 
           class="btn-delete-history" 
           data-booking-id="<?= $booking['booking_id'] ?>" 
           title="Hapus riwayat ini">
            &times;
        </a>

            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag canceled">DIBATALKAN</span>

                    <div class="order-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            <?php
                            echo \CodeIgniter\I18n\Time::parse($booking['tanggal_kunjungan'], 'Asia/Makassar', 'id_ID')
                            ->toLocalizedString('d MMMM yyyy');
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="product-details">
                <img src="<?= (filter_var($booking['gambar_wisata'], FILTER_VALIDATE_URL)) ? $booking['gambar_wisata'] : base_url('uploads/wisata/' . ($booking['gambar_wisata'] ?? 'default.jpg')) ?>" 
                    alt="<?= esc($booking['nama']) ?>" 
                    class="product-image">
                <div class="product-info-text">
                    <p class="product-name"><?= esc($booking['nama']) ?></p>
                    <p class="product-quantity">x<?= $booking['jumlah_orang'] ?> orang</p>
                </div>
                <div class="product-price">
                    <span class="discounted-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="order-footer">
                <div class="total-price-summary">
                    <span>Total Harga:</span>
                    <span class="total-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div> 
                <div class="order-actions-bottom">
                    <a href="<?= base_url('booking/pembelian/' . $booking['wisata_id']) ?>" class="btn-action secondary-btn">Beli Lagi</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (empty($upcomingBookings) && empty($completedBookings) && empty($canceledBookings)): ?>
    <div class="empty-history text-center py-5">
        <div class="empty-icon">
            <i class="far fa-calendar-alt"></i>
        </div>
        <h3 class="mt-3">Belum Ada Riwayat Kunjungan</h3>
        <p class="text-muted">Anda belum memiliki riwayat kunjungan.</p>
        <a href="<?= base_url('destinasi') ?>" class="btn-action primary-btn mt-3">
            Beli Sekarang
        </a>
    </div>
    <?php endif; ?>
</div>

<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Berikan Ulasan</h3>
        <form id="reviewForm">
            <input type="hidden" name="wisata_id" id="wisata_id">
            <div class="mb-3">
                <label class="form-label">Rating Anda</label>
                <div class="rating-input">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                    <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="komentar" class="form-label">Komentar Anda</label>
                <textarea class="form-control" id="komentar" name="komentar" rows="4" required placeholder="Ceritakan detail pengalaman Anda di destinasi wisata ini..."></textarea>
            </div>
            <div class="review-button-container">
                <button type="submit" class="btn btn-submit-review">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<div id="ticketModal" class="modal">
    <div class="modal-content ticket-style">
        <span class="close-ticket">&times;</span>
        <div class="ticket-header">
            <h3>E-TIKET ANDA</h3>
            <p id="ticketNamaWisata">Nama Wisata</p>
        </div>
        <div class="ticket-body">
            <div id="qrcode" class="qrcode-container">
                </div>
            <p class="ticket-instructions">Pindai QR Code ini di pintu masuk</p>
            <div class="ticket-details">
                <div class="detail-item">
                    <span>Kode Tiket:</span>
                    <strong id="ticketKodeUnik">TIKET-XXXX-XXXX</strong>
                </div>
                <div class="detail-item">
                    <span>Jumlah Pengunjung:</span>
                    <strong id="ticketJumlahOrang">0 orang</strong>
                </div>
                 <div class="detail-item">
                    <span>Total Bayar:</span>
                    <strong id="ticketTotalHarga">Rp 0</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/davidshimjs-qrcodejs@0.0.2/qrcode.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const modal = document.getElementById('reviewModal');
    const closeBtn = document.querySelector('#reviewModal .close');

    window.openReviewModal = function(wisataId) {
        document.getElementById('wisata_id').value = wisataId;
        modal.style.display = "block";
    }

    if(closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    const reviewForm = document.getElementById('reviewForm');
    if(reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('<?= base_url('destinasi/addReview') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
    if (data.status === 'success') { 
        document.getElementById('reviewModal').style.display = 'none'; 
        
        Swal.fire({
            title: 'Berhasil!',
            text: 'Ulasan berhasil ditambahkan!',
            icon: 'success',
            timer: 2000, 
            showConfirmButton: false
        }).then(() => {
            location.reload(); 
        });

    } else {
        Swal.fire('Gagal', data.message || 'Gagal menambahkan ulasan.', 'error');
    }
})
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat mengirim ulasan.', 'error');
            });
        });
    }

    const deleteButtons = document.querySelectorAll('.btn-delete-history');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const bookingId = this.getAttribute('data-booking-id');
            const url = `<?= base_url('riwayat/delete/') ?>${bookingId}`;
            const historyItemElement = this.closest('.order-item');

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Riwayat ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#555',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    fetch(url, {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Terhapus!', 'Riwayat berhasil dihapus.', 'success');
                            
                            historyItemElement.style.transition = 'opacity 0.3s, transform 0.3s';
                            historyItemElement.style.opacity = '0';
                            historyItemElement.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                historyItemElement.remove();
                            }, 300);

                        } else {
                            Swal.fire('Gagal', data.message || 'Gagal menghapus riwayat.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Tidak dapat menghubungi server.', 'error');
                    });
                }
            });
        });
    });

    const ticketModal = document.getElementById('ticketModal');
    const closeTicketBtn = document.querySelector('.close-ticket');
    const qrCodeContainer = document.getElementById('qrcode');
    
    // Variabel untuk menyimpan object QRCode agar bisa di-clear
    let qrcode = null;

    // Fungsi untuk membuka modal tiket
    window.openTicketModal = function(bookingId) {
        const url = `<?= base_url('riwayat/tiket/') ?>${bookingId}`;

        // Tampilkan loading spinner jika ada
        qrCodeContainer.innerHTML = 'Memuat tiket...';
        ticketModal.style.display = "block";

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memuat data tiket.');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const ticketData = data.data;
                // Isi data ke dalam modal
                document.getElementById('ticketNamaWisata').textContent = ticketData.nama_wisata;
                document.getElementById('ticketKodeUnik').textContent = ticketData.kode_tiket;
                document.getElementById('ticketJumlahOrang').textContent = ticketData.jumlah_orang + ' orang';
                document.getElementById('ticketTotalHarga').textContent = ticketData.total_harga;
                
                // Bersihkan QR code lama sebelum membuat yang baru
                qrCodeContainer.innerHTML = '';

                // Buat QR Code baru menggunakan library
                qrcode = new QRCode(qrCodeContainer, {
                    text: ticketData.kode_tiket,
                    width: 200,
                    height: 200,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });

            } else {
                Swal.fire('Gagal', data.message || 'Tidak dapat mengambil data tiket.', 'error');
                ticketModal.style.display = "none";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            ticketModal.style.display = "none";
        });
    }
    
    // Event untuk menutup modal tiket
    if(closeTicketBtn) {
        closeTicketBtn.onclick = function() {
            ticketModal.style.display = "none";
        }
    }
    
    // Event untuk menutup modal jika klik di luar modal (sudah ada di kode Anda, tapi pastikan juga berlaku untuk ticketModal)
    window.addEventListener('click', function(event) {
        if (event.target == ticketModal) {
            ticketModal.style.display = "none";
        }
    });
    
});
</script>

<?= $this->endSection() ?>