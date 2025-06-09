<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Remove top navigation for dashboard view -->
<style>
  .navbar {
    display: none;
  }
  .content {
    padding: 0;
  }
</style>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1>Jelajahi Wisata Indonesia</h1>
    <p>Temukan destinasi impian, inspirasi liburan, dan pengalaman tak terlupakan di seluruh Nusantara.</p>
    
    <!-- Search Form -->
    <div class="search-container mt-4">
      <form action="<?= base_url('destinasi/search') ?>" method="get" class="search-form">
        <input type="text" name="keyword" placeholder="Cari destinasi wisata..." required>
        <button type="submit"><i class="fas fa-search"></i> Cari</button>
      </form>
    </div>
  </div>
  <div class="hero-bg-shape"></div>
</section>

<main>  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- User region indicator -->
  <div class="user-region">
    <div class="region-container">
      <i class="fas fa-map-marker-alt"></i>
      <span>Anda berada di wilayah: <strong><?= esc($user['daerah'] ?? 'Indonesia') ?></strong></span>
      <a href="<?= base_url('profile') ?>" class="change-region">Ubah</a>
    </div>
  </div><!-- Wisata Terbaru -->
  <section class="section-wisata">
    <h2>Wisata Terbaru</h2>
    <div class="card-container">
      <?php if (empty($wisataTerbaru)): ?>
      <div class="alert alert-info">Belum ada wisata terbaru dalam 1 bulan terakhir</div>
      <?php else: ?>        <?php foreach ($wisataTerbaru as $wisata): ?>
        <div class="card" data-wisata="<?= esc($wisata['nama']) ?>" data-price="<?= $wisata['harga'] ?? 0 ?>">
          <a href="<?= base_url('destinasi/detail/' . $wisata['wisata_id']) ?>" class="card-link">
            <img src="<?= (filter_var($wisata['gambar_wisata'], FILTER_VALIDATE_URL)) ? $wisata['gambar_wisata'] : base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($wisata['nama']) ?>">
            <div class="card-content">
              <div class="card-labels">
                <span class="badge"><?= esc($wisata['kategori'] ?? 'Umum') ?></span>
                <span class="badge daerah"><?= esc($wisata['daerah'] ?? 'Indonesia') ?></span>
                <span class="price">Rp <?= number_format($wisata['harga'] ?? 0, 0, ',', '.') ?></span>
              </div>
              <h3><?= esc($wisata['nama']) ?></h3>
              <p><?= esc(substr($wisata['deskripsi'] ?? '', 0, 100)) ?>...</p>
              <div class="card-action">
                <small class="text-muted"><i class="fas fa-calendar-alt"></i> <?= isset($wisata['created_at']) ? date('d M Y', strtotime($wisata['created_at'])) : 'Baru ditambahkan' ?></small>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
  <!-- Wisata Populer -->
  <section class="trending-section">
    <h2>Wisata Populer</h2>
    <div class="trending-grid">      <?php if (empty($wisataPopuler)): ?>
      <div class="alert alert-info">Belum ada wisata populer saat ini</div>
      <?php else: ?>
        <?php $i = 1; foreach ($wisataPopuler as $wisata): ?>
        <a href="<?= base_url('destinasi/detail/' . $wisata['wisata_id']) ?>" class="destination-link">
          <div class="destination <?= ($i <= 2) ? 'large' : 'small' ?>">
            <img src="<?= (filter_var($wisata['gambar_wisata'], FILTER_VALIDATE_URL)) ? $wisata['gambar_wisata'] : base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($wisata['nama']) ?>">
            <div class="label">🇮🇩 <?= esc($wisata['nama']) ?></div>
            <div class="trending-badge"><i class="fas fa-fire"></i> Populer</div>
          </div>
        </a>
        <?php $i++; endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- Wisata Terdekat -->
  <section class="section-wisata">
    <h2>Wisata Terdekat</h2>
    <div class="card-container">      <?php if (empty($wisataTerdekat)): ?>
      <div class="alert alert-info">Belum ada wisata di daerah <?= esc($user['daerah'] ?? 'Anda') ?></div>
      <?php else: ?>
        <?php foreach ($wisataTerdekat as $wisata): ?>
        <div class="card" data-wisata="<?= esc($wisata['nama']) ?>" data-price="<?= $wisata['harga'] ?? 0 ?>">
          <a href="<?= base_url('destinasi/detail/' . $wisata['wisata_id']) ?>" class="card-link">
            <img src="<?= base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($wisata['nama']) ?>">
            <div class="card-content">
              <div class="card-labels">
                <span class="badge"><?= esc($wisata['kategori'] ?? 'Umum') ?></span>
                <span class="badge daerah"><?= esc($wisata['daerah'] ?? 'Indonesia') ?></span>
                <span class="price">Rp <?= number_format($wisata['harga'] ?? 0, 0, ',', '.') ?></span>
              </div>
              <h3><?= esc($wisata['nama']) ?></h3>
              <p><?= esc(substr($wisata['deskripsi'] ?? '', 0, 100)) ?>...</p>
              <div class="card-action">
                <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?= esc($wisata['daerah'] ?? 'Indonesia') ?></small>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="cta-container">
      <h2>Temukan Lebih Banyak Destinasi</h2>
      <p>Jelajahi ratusan destinasi wisata terbaik di seluruh Indonesia</p>
      <a href="<?= base_url('destinasi') ?>" class="cta-button">Lihat Semua Destinasi</a>
    </div>
  </section>
  
  <!-- Berita Terbaru -->
  <section id="news-section">
    <h2>Berita Terbaru</h2>
    <div class="news-container">      <?php if (empty($berita)): ?>
      <div class="alert alert-info">Belum ada berita wisata terbaru</div>
      <?php else: ?>
        <?php foreach ($berita as $item): ?>
        <div class="news-item">
          <div class="news-content">
            <img src="<?= base_url('uploads/wisata/' . ($item['gambar_wisata'] ?? 'default.jpg')) ?>" alt="<?= esc($item['judul']) ?>">
            <div class="text-content">
              <h3><?= esc($item['judul']) ?></h3>
              <p><?= esc(substr($item['konten'] ?? '', 0, 120)) ?>...</p>
              <div class="news-footer">
                <a href="<?= base_url('berita/detail/' . $item['berita_id']) ?>" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                <div class="news-meta">
                  <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?= esc($item['nama'] ?? 'Tidak diketahui') ?></small>
                  <?php if(!empty($item['tanggal_post'])): ?>
                  <small class="text-muted"><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($item['tanggal_post'])) ?></small>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 