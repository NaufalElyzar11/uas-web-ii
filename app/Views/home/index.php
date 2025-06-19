<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/home.css') ?>">

<section class="hero">
  <div class="hero-content">
    <h1>Jelajahi Wisata Kalimantan Selatan</h1>
    <p>Temukan destinasi impian, inspirasi liburan, dan pengalaman tak terlupakan di seluruh Kalimantan Selatan.</p>
    
    <div class="search-container mt-4">
      <form action="<?= base_url('destinasi/search') ?>" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="Cari destinasi wisata..." required>
        <button type="submit"><i class="fas fa-search"></i> Cari</button>
      </form>
    </div>
  </div>
  <div class="hero-bg-shape"></div>
</section>

<main>
  <section class="trending-section">
    <h2>Wisata Trending</h2>
    <div class="trending-grid">
      <?php if (empty($wisataTrending)): ?>
        <div class="alert alert-info">Belum ada wisata yang sedang trending saat ini</div>
      <?php else: ?>
        <?php $i = 1; foreach ($wisataTrending as $wisata): ?>
        <a href="<?= base_url('destinasi/detail/' . $wisata['wisata_id']) ?>" class="destination-link">
          <div class="destination large">
            <?php
              $galleryPath = FCPATH . 'uploads/wisata/gallery/' . $wisata['wisata_id'];
              $imageUrl = base_url('uploads/wisata/default.jpg');
              if (is_dir($galleryPath)) {
                  $files = array_diff(scandir($galleryPath), ['.', '..']);
                  if (!empty($files)) {
                      $firstImage = reset($files);
                      $imageUrl = base_url('uploads/wisata/gallery/' . $wisata['wisata_id'] . '/' . $firstImage);
                  }
              }
            ?>
            <img src="<?= $imageUrl ?>" alt="<?= esc($wisata['nama']) ?>">
            <div class="label"><?= esc($wisata['nama']) ?></div>
            <div class="trending-badge">
              <i class="fas fa-users"></i> <?= number_format($wisata['total_kunjungan'] ?? 0, 0, ',', '.') ?> pengunjung
            </div>
          </div>
        </a>
        <?php $i++; endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="cta-section">
    <div class="cta-container">
      <h2>Temukan Lebih Banyak Destinasi</h2>
      <p>Jelajahi ratusan destinasi wisata terbaik di seluruh Kalimantan Selatan</p>
      <a href="<?= base_url('destinasi') ?>" class="cta-button">Lihat Semua Destinasi</a>
    </div>
  </section>
  
  <section id="news-section">
    <h2>Berita Terbaru</h2>
    <div class="news-container">
      <?php if (empty($berita)): ?>
      <div class="alert alert-info">Belum ada berita wisata terbaru</div>
      <?php else: ?>
        <?php foreach ($berita as $item): ?>
        <div class="news-item">
          <div class="news-content">
            <img src="<?= (filter_var($item['gambar'], FILTER_VALIDATE_URL)) ? $item['gambar'] : base_url('uploads/berita/' . ($item['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($item['judul']) ?>">
            <div class="text-content">
              <h3><?= esc($item['judul']) ?></h3>
              <p><?= esc(substr($item['konten'] ?? '', 0, 120)) ?>...</p>
              <div class="news-footer">
                <a href="<?= $item['link_berita'] ?>" class="btn btn-sm btn-primary" target="_blank" rel="noopener">Baca Selengkapnya</a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<footer>
  <div class="footer-content">
    <p>&copy; <?= date('Y') ?> BanuaTour. Temukan destinasi impianmu.</p>
  </div>
</footer>
<?= $this->endSection() ?> 