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

    <div class="wisata-detail">
        <div class="wisata-images">
            <img src="<?= (filter_var($wisata['gambar_wisata'] ?? '', FILTER_VALIDATE_URL)) ? $wisata['gambar_wisata'] : base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($wisata['nama']) ?>" class="main-image">
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

<style>
.breadcrumb {
    background-color: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.breadcrumb-item a {
    color: #0066ff;
    text-decoration: none;
}
.wisata-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}
.wisata-images {
    position: relative;
}
.main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
.wisata-header {
    margin-bottom: 20px;
}
.badges {
    margin-bottom: 10px;
    display: flex;
    gap: 10px;
}
.badge {
    background-color: #4f8cff;
    color: white;
    padding: 5px 12px;
    border-radius: 5px;
    font-size: 0.9rem;
    display: inline-block;
}
.badge.daerah {
    background-color: #fbbf24;
    color: #1e293b;
}
.wisata-header h1 {
    font-size: 2rem;
    margin: 0;
    color: #2d3a4a;
}
.price-box {
    background-color: #f5f7fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: baseline;
    gap: 8px;
}
.price-box h2 {
    margin: 0;
    color: #0066ff;
    font-size: 1.8rem;
}
.wisata-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
}
.btn {
    padding: 12px 20px;
    border-radius: 5px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-primary {
    background-color: #0066ff;
    color: white;
    border: none;
}
.btn-primary:hover {
    background-color: #0055d4;
}
.btn-outline {
    background-color: transparent;
    color: #0066ff;
    border: 1px solid #0066ff;
}
.btn-outline:hover {
    background-color: #f0f8ff;
}
.wisata-description {
    margin-bottom: 30px;
}
.wisata-description h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #2d3a4a;
}
.wisata-description p {
    color: #555;
    line-height: 1.6;
}
.wisata-details {
    background-color: #f5f7fa;
    border-radius: 10px;
    padding: 20px;
}
.detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}
.detail-item i {
    font-size: 1.2rem;
    color: #0066ff;
    width: 20px;
    text-align: center;
}
.detail-item div {
    display: flex;
    flex-direction: column;
}
.detail-item strong {
    font-size: 0.9rem;
    color: #666;
}
.detail-item span {
    font-size: 1.1rem;
    color: #2d3a4a;
}

@media (max-width: 768px) {
    .wisata-detail {
        grid-template-columns: 1fr;
    }
    .main-image {
        height: 300px;
    }
}
</style>

<script>
function toggleWishlist(wisataId) {
    alert('Fitur wishlist belum tersedia');
    // Implementasi AJAX untuk wishlist akan dilakukan di masa mendatang
}
</script>
<?= $this->endSection() ?>
