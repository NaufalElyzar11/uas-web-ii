<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="wishlist-page-wrapper">

  <header>
    <h1>Wishlist Saya</h1>
  </header>

  <main class="wishlist-container">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (empty($wishlist)): ?>
      <div class="empty-wishlist">

        <h3>Wishlist Anda Kosong</h3>
        <p>Sepertinya Anda belum menambahkan destinasi apa pun. Mari jelajahi!</p>
        <a href="<?= base_url('destinasi') ?>" class="btn btn-primary"><i class="fas fa-search"></i> Jelajahi Destinasi</a>
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
            <a href="<?= base_url('wishlist/remove/' . $item['wisata_id']) ?>" class="remove">×</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  </div> 
  <link rel="stylesheet" href="<?= base_url('css/wishlist.css') ?>">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.remove');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); 
            
            const removalUrl = this.href;
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Destinasi ini akan dihapus dari wishlist Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = removalUrl;
                }
            });
        });
    });
});
</script>

  <?= $this->endSection() ?>