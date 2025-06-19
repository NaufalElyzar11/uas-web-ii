<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/destinasi.css') ?>">

<div class="container mt-4">
    <h1 class="mb-4"><?= $title ?></h1>

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
                        <img src="<?= esc($item['gambar_wisata']) ?>" alt="<?= esc($item['nama']) ?>" class="wisata-img">
                        <div class="wisata-content">
                            <div class="wisata-labels">
                                <span class="badge"><?= esc($item['nama_kategori'] ?? 'Umum') ?></span>
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

<?= $this->endSection() ?>