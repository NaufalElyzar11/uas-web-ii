<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4"><?= $title ?></h1>
    
    <!-- Search Form -->
    <div class="search-container mb-4">
        <form action="<?= base_url('destinasi/search') ?>" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="Cari destinasi wisata..." value="<?= $keyword ?? '' ?>" required>
            <button type="submit"><i class="fas fa-search"></i> Cari</button>
        </form>
    </div>

    <?php if (!empty($wisata)): ?>
        <p>Ditemukan <?= count($wisata) ?> hasil pencarian untuk "<?= esc($keyword) ?>"</p>
        
        <div class="wisata-grid">
            <?php foreach ($wisata as $item): ?>
            <div class="wisata-card">
                <a href="<?= base_url('destinasi/detail/' . $item['wisata_id']) ?>" class="card-link">
                    <img src="<?= (filter_var($item['gambar_wisata'] ?? '', FILTER_VALIDATE_URL)) ? $item['gambar_wisata'] : base_url('uploads/wisata/' . ($item['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($item['nama']) ?>" class="wisata-img">
                    <div class="wisata-content">
                        <div class="wisata-labels">
                            <span class="badge"><?= esc($item['kategori'] ?? 'Umum') ?></span>
                            <span class="badge daerah"><?= esc($item['daerah'] ?? 'Indonesia') ?></span>
                        </div>
                        <h3><?= esc($item['nama']) ?></h3>
                        <p class="price">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></p>
                        <p class="description"><?= esc(substr($item['deskripsi'] ?? '', 0, 100)) ?>...</p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?= empty($keyword) ? 'Silakan masukkan kata kunci pencarian' : 'Tidak ditemukan hasil untuk "' . esc($keyword) . '"' ?>
        </div>
    <?php endif; ?>
</div>

<style>
.search-container {
    max-width: 600px;
    margin: 0 auto;
}
.search-form {
    display: flex;
    background: #f5f7fa;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.search-form input {
    flex-grow: 1;
    padding: 12px 20px;
    border: none;
    outline: none;
    font-size: 1rem;
    background: transparent;
}
.search-form button {
    background: #0066ff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: background 0.2s;
}
.search-form button:hover {
    background: #0055d4;
}
.wisata-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.wisata-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.wisata-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}
.wisata-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.wisata-content {
    padding: 15px;
}
.wisata-labels {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
}
.badge {
    background-color: #4f8cff;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
}
.badge.daerah {
    background-color: #fbbf24;
    color: #1e293b;
}
.price {
    color: #0066ff;
    font-weight: bold;
    margin: 8px 0;
}
.description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0;
}
</style>
<?= $this->endSection() ?>
