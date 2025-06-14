<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/riwayat.css') ?>">
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
                    <a href="<?= base_url('destinasi/detail/' . $booking['wisata_id']) ?>" class="btn-action primary-btn">Nilai</a>
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

<?= $this->endSection() ?>