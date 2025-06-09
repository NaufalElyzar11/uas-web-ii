<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!DOCTYPE html>
<html lang="id">
<link rel="stylesheet" href="<?= base_url('css/wishlist.css') ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= esc($title) ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <header>
    <h1>Wishlist</h1>
    <nav>
      <a href="<?= base_url() ?>">Home</a> | <span>Wishlist</span>
    </nav>
  </header>

  <main class="wishlist-container">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (empty($wishlist)): ?>
      <div class="empty-wishlist text-center">
        <h3>Wishlist Anda Kosong</h3>
        <p>Tambahkan destinasi favorit ke wishlist Anda untuk melihatnya di sini.</p>
        <a href="<?= base_url('destinasi') ?>" class="btn btn-primary">
          <i class="fas fa-search"></i> Jelajahi Destinasi
        </a>
      </div>
    <?php else: ?>
      <div class="wishlist-list">
        <?php foreach ($wishlist as $item): ?>
          <div class="wishlist-item">
            <img src="<?= (filter_var($item['gambar_wisata'], FILTER_VALIDATE_URL)) ? $item['gambar_wisata'] : base_url('uploads/wisata/' . ($item['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($item['nama']) ?>" />
            <div class="product-info">
              <h3><?= esc($item['nama']) ?></h3>
              <p class="description">
                <i class="fas fa-map-marker-alt"></i> <?= esc($item['daerah']) ?><br>
                <span class="badge bg-info"><?= esc($item['kategori']) ?></span>
              </p>
              <p class="price">Rp <?= number_format($item['harga'], 0, ',', '.') ?> / orang</p>
              <p class="stock">Tersedia</p>
            </div>
            <a href="<?= base_url('destinasi/detail/' . $item['wisata_id']) ?>" class="add-to-cart">Lihat</a>
            <a href="<?= base_url('wishlist/remove/' . $item['wishlist_id']) ?>" class="remove" onclick="return confirm('Hapus dari wishlist?')">×</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
