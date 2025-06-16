<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/riwayat.css') ?>">

    <header>
        <h1>History</h1>
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

    <!-- Upcoming Bookings -->
    <?php if (!empty($upcomingBookings)): ?>
        <?php foreach ($upcomingBookings as $booking): ?>
        <div class="order-item">
            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag upcoming">AKAN DATANG</span>
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
                    <span>Total Pesanan:</span>
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

    <!-- Completed Bookings -->
    <?php if (!empty($completedBookings)): ?>
        <?php foreach ($completedBookings as $booking): ?>
        <div class="order-item">
            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag delivered">SELESAI</span>
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
                    <span>Total Pesanan:</span>
                    <span class="total-price">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="order-actions-bottom">
                    <a href="javascript:void(0);" onclick="openReviewModal('<?= $booking['wisata_id'] ?>')" class="btn-action primary-btn">Nilai</a>
                    <a href="<?= base_url('booking/pembelian/' . $booking['wisata_id']) ?>" class="btn-action secondary-btn">Beli Lagi</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Canceled Bookings -->
    <?php if (!empty($canceledBookings)): ?>
        <?php foreach ($canceledBookings as $booking): ?>
        <div class="order-item">
            <div class="order-header">
                <div class="order-status">
                    <span class="status-tag canceled">DIBATALKAN</span>
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
                    <span>Total Pesanan:</span>
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
            Booking Sekarang
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Review Modal -->
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
                <textarea class="form-control" id="komentar" name="komentar" rows="4" required placeholder="Ceritakan detail pengalaman Anda di destinasi ini..."></textarea>
            </div>
            <div class="review-button-container">
                <button type="submit" class="btn btn-submit-review">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('reviewModal');
const closeBtn = document.getElementsByClassName('close')[0];

function openReviewModal(wisataId) {
    document.getElementById('wisata_id').value = wisataId;
    modal.style.display = "block";
}

closeBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('destinasi/addReview') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ulasan berhasil ditambahkan!');
            location.reload();
        } else {
            alert(data.message || 'Gagal menambahkan ulasan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim ulasan');
    });
});
</script>

<?= $this->endSection() ?>