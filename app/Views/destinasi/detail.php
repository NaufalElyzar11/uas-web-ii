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
        <div class="left-column">
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

            <iframe
                width="700"
                height="300"
                style="border:0"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps?q=<?= esc($wisata['latitude']) ?>,<?= esc($wisata['longitude']) ?>&output=embed">
            </iframe>
        </div>

        <div class="right-column wisata-info">
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
                    <a href="<?= base_url('booking/pembelian/' . $wisata['wisata_id']) ?>" class="btn btn-primary">
                        <i class="fas fa-ticket-alt"></i> Beli Sekarang
                    </a>

                    <a href="javascript:void(0);"
                        id="wishlistButton"
                        class="btn btn-outline<?= !empty($isInWishlist) ? ' added' : '' ?>"
                        onclick="toggleWishlist()">
                        <i class="<?= !empty($isInWishlist) ? 'fas' : 'far' ?> fa-heart" id="wishlistIcon"></i>
                        <span id="wishlistText"><?= !empty($isInWishlist) ? 'Sudah di Wishlist' : 'Tambah ke Wishlist' ?></span>
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

    <!-- Review Section -->
    <div class="review-section mt-5">
        <div class="container">
            <h2 class="mb-4">Ulasan Pengunjung</h2>
            
            <!-- Rating Summary -->
            <div class="rating-summary mb-4">
                <div class="average-rating">
                    <h3><?= number_format($averageRating, 1) ?></h3>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($averageRating) ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p>Berdasarkan <?= count($reviews) ?> ulasan</p>
                </div>
            </div>

            <!-- Review Form -->
            <?php if (session()->get('isLoggedIn')): ?>
            <div class="review-form mb-4">
                <h5>Tulis Ulasan</h5>
                <form id="reviewForm">
                    <input type="hidden" name="wisata_id" value="<?= $wisata['wisata_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                            <label for="star<?= $i ?>">☆</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </form>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Silahkan <a href="<?= base_url('auth/login') ?>" class="alert-link">login</a> untuk menulis ulasan.
            </div>
            <?php endif; ?>

            <!-- Reviews List -->
            <div class="reviews-list">
                <?php if (empty($reviews)): ?>
                    <div class="alert alert-info">Belum ada ulasan untuk destinasi ini</div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-item mb-4">
                        <div class="review-header">
                            <div class="user-info">
                                <h4><?= esc($review['nama_user']) ?></h4>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <small class="text-muted">
                                <?= date('d M Y', strtotime($review['tanggal_review'])) ?>
                            </small>
                        </div>
                        <div class="review-content">
                            <p><?= nl2br(esc($review['komentar'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Review Section Styles */
.review-section {
    background-color: #f8f9fa;
    padding: 40px 0;
    border-radius: 10px;
}

.rating-summary {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.average-rating {
    text-align: center;
}

.average-rating h3 {
    font-size: 3rem;
    margin: 0;
    color: #0d6566;
}

.stars {
    margin: 10px 0;
}

.stars i {
    font-size: 1.2rem;
    margin: 0 2px;
}

.review-form {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin: 0 2px;
}

.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}

.review-item {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.user-info h4 {
    margin: 0;
    font-size: 1.1rem;
}

.review-content {
    color: #555;
    line-height: 1.6;
}
</style>

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
            fetch(`<?= base_url('wishlist/remove/') ?>${wisataId}`)
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
            fetch(`<?= base_url('wishlist/add/') ?>${wisataId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Added to wishlist');
                    }
                });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('<?= base_url('destinasi/addReview') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Ulasan berhasil ditambahkan!');
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal menambahkan ulasan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim ulasan');
                });
            });
        }
    });
</script>

<?= $this->endSection() ?>