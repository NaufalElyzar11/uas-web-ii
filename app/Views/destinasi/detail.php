<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/detail.css') ?>">

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('destinasi') ?>">Destinasi Lainnya</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($wisata['nama']) ?></li>
        </ol>
    </nav>

    <div class="wisata-detail">
        <div class="wisata-images">
            <?php $wisata_id = isset($wisata['id']) ? $wisata['id'] : 'default'; ?>

            <?php if (!empty($wisata['link_video'])): ?>
                <iframe
                    class="media-box"
                    src="<?= esc($wisata['link_video']) ?>"
                    title="<?= esc($wisata['nama']) ?>"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>

            <?php else: ?>
                <img
                    class="main-image"
                    src="<?= (filter_var($wisata['gambar_wisata'] ?? '', FILTER_VALIDATE_URL)) ? $wisata['gambar_wisata'] : base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>"
                    alt="<?= esc($wisata['nama']) ?>">
            <?php endif; ?>

            <?php if (!empty($galeri) && is_array($galeri)): ?>
                <div class="small-Card">
                    <?php foreach ($galeri as $index => $gambar): ?>
                        <img
                            src="<?= base_url('uploads/wisata/' . $gambar) ?>"
                            alt="Thumbnail"
                            class="small-Img"
                            onerror="this.src='<?= base_url('uploads/wisata/default.jpg') ?>'"
                            onclick="openModal(<?= $index ?>)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImage">
            <a class="prev" onclick="moveImage(-1)">&#10094;</a>
            <a class="next" onclick="moveImage(1)">&#10095;</a>
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
                <h2>Rp <?= number_format($wisata['harga'] ?? 0, 0, ',', '.') ?></h2>
                <span>/orang</span>
            </div>

            <div class="wisata-actions">
                <a href="<?= base_url('booking/create/' . $wisata['wisata_id']) ?>" class="btn btn-primary">
                    <i class="fas fa-ticket-alt"></i> Beli Sekarang
                </a>

                <a href="javascript:void(0);"
                    id="wishlistButton"
                    class="btn btn-outline"
                    onclick="toggleWishlist()">
                    <i class="far fa-heart" id="wishlistIcon"></i>
                    <span id="wishlistText">Tambah ke Wishlist</span>
                </a>
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
    let currentIndex = 0;

    function openModal(index) {
        currentIndex = index;
        const images = document.querySelectorAll('.small-Img');
        const modalImage = document.getElementById('modalImage');

        modalImage.src = images[index].src;
        document.getElementById('imageModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
    }

    function moveImage(step) {
        const images = document.querySelectorAll('.small-Img');
        currentIndex += step;
        if (currentIndex < 0) {
            currentIndex = images.length - 1;
        } else if (currentIndex >= images.length) {
            currentIndex = 0;
        }
        document.getElementById('modalImage').src = images[currentIndex].src;
    }

    function toggleWishlist() {
        const wishlistButton = document.getElementById('wishlistButton');
        const wishlistIcon = document.getElementById('wishlistIcon');
        const wishlistText = document.getElementById('wishlistText');
        const wisataId = <?= esc($wisata['wisata_id']); ?>; 

        if (wishlistButton.classList.contains('added')) {
            wishlistButton.classList.remove('added');
            wishlistIcon.classList.remove('fas');
            wishlistIcon.classList.add('far');
            wishlistText.textContent = 'Tambah ke Wishlist';

            fetch(`<?= base_url('wishlist/remove/') ?>/${wisataId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Removed from wishlist');
                    }
                });

        } else {
            wishlistButton.classList.add('added');
            wishlistIcon.classList.remove('far');
            wishlistIcon.classList.add('fas');
            wishlistText.textContent = 'Sudah di Wishlist';
            
            fetch(`<?= base_url('wishlist/add/') ?>/${wisataId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        wisataId: wisataId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Added to wishlist');
                    }
                });
        }
    }
</script>

<?= $this->endSection() ?>