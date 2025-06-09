<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('destinasi') ?>">Destinasi</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($wisata['nama']) ?></li>
        </ol>
    </nav>

    <div class="wisata-images">
        <iframe
            class="media-box"
            src="<?= esc($wisata['link_video'] ?? '') ?>"
            title="<?= esc($wisata['nama']) ?>"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>

        <div class="small-Card">
            <?php foreach ($galeri as $gambar): ?>
                <img src="<?= base_url('uploads/wisata/' . $gambar) ?>" alt="Thumbnail" class="small-Img">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="wisata-info">
        <div class="wisata-header">
            <div class="badges">
                <span class="badge"><?= esc($wisata['kategori'] ?? 'Umum') ?></span>
                <span class="badge daerah"><?= esc($wisata['daerah'] ?? 'Indonesia') ?></span>
            </div>
            <h1><?= esc($wisata['nama']) ?></h1>
        </div>

        <div class="price-box">
            <span>Mulai dari</span>
            <h2>Rp <?= number_format($wisata['harga'] ?? 0, 0, ',', '.') ?></h2>
            <span>/orang</span>
        </div>
        <div class="wisata-actions">
            <a href="<?= base_url('booking/create/' . $wisata['wisata_id']) ?>" class="btn btn-primary">
                <i class="fas fa-ticket-alt"></i> Booking Sekarang
            </a>
            <a href="<?= base_url('wishlist/add/' . $wisata['wisata_id']) ?>" class="btn btn-outline">
                <i class="far fa-heart"></i> Tambah ke Wishlist
            </a>
        </div>

        <div class="wisata-description">
            <h3>Tentang Destinasi</h3>
            <p><?= nl2br(esc($wisata['deskripsi'] ?? 'Tidak ada deskripsi')) ?></p>
        </div>

        <div class="wisata-details">
            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Lokasi</strong>
                    <span><?= esc($wisata['daerah'] ?? 'Indonesia') ?></span>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-tags"></i>
                <div>
                    <strong>Kategori</strong>
                    <span><?= esc($wisata['kategori'] ?? 'Umum') ?></span>
                </div>
            </div>
            <div class="detail-item">
                <i class="fas fa-fire"></i>
                <div>
                    <strong>Popularitas</strong>
                    <span><?= $wisata['trending_score'] ?? 0 ?> point</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.querySelectorAll('.small-Card img').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('featured-video').poster = this.src;
        });
    });
</script>

<?= $this->endSection() ?>