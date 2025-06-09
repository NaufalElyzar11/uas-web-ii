<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4">Wishlist Wisata</h1>
    
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('info') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (empty($wishlist)): ?>
    <div class="empty-wishlist text-center py-5">
        <div class="empty-icon">
            <i class="far fa-heart"></i>
        </div>
        <h3 class="mt-3">Wishlist Anda Kosong</h3>
        <p class="text-muted">Tambahkan destinasi favorit ke wishlist Anda untuk melihatnya di sini.</p>
        <a href="<?= base_url('destinasi') ?>" class="btn btn-primary mt-3">
            <i class="fas fa-search"></i> Jelajahi Destinasi
        </a>
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($wishlist as $item): ?>
        <div class="col-md-4 mb-4">
            <div class="card wishlist-card h-100">
                <img src="<?= (filter_var($item['gambar_wisata'], FILTER_VALIDATE_URL)) ? $item['gambar_wisata'] : base_url('uploads/wisata/' . ($item['gambar_wisata'] ?? 'default.jpg')) ?>" 
                    class="card-img-top" alt="<?= esc($item['nama']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($item['nama']) ?></h5>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt"></i> <?= esc($item['daerah']) ?><br>
                        <span class="badge bg-info"><?= esc($item['kategori']) ?></span>
                    </p>
                    <p class="card-text price">
                        <strong>Rp <?= number_format($item['harga'], 0, ',', '.') ?></strong> / orang
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="<?= base_url('destinasi/detail/' . $item['wisata_id']) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="<?= base_url('booking/create/' . $item['wisata_id']) ?>" class="btn btn-sm btn-success">
                        <i class="fas fa-ticket-alt"></i> Booking
                    </a>
                    <a href="<?= base_url('wishlist/remove/' . $item['wishlist_id']) ?>" class="btn btn-sm btn-danger" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus item ini dari wishlist?')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.container {
    max-width: 900px;
    margin: 0 auto;
}
.empty-wishlist {
    margin: 40px 0;
}
.empty-icon {
    font-size: 5rem;
    color: #ddd;
}
.btn-primary {
    background-color: #0066ff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
</style>
<?= $this->endSection() ?>
