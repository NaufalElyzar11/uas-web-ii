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
                    <?php if (session()->get('isLoggedIn')): ?>
                        <a href="<?= base_url('booking/pembelian/' . $wisata['wisata_id']) ?>" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Beli Sekarang
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                        </a>
                    <?php endif; ?>

                    <?php if (session()->get('isLoggedIn')): ?>
                        <a href="javascript:void(0);"
                        id="wishlistButton"
                        class="btn btn-primary <?= !empty($isInWishlist) ? 'wishlist-added' : '' ?>"
                        data-wisata-id="<?= $wisata['wisata_id'] ?>">
                        <i class="fas fa-heart"></i>
                        <span id="wishlistText"><?= !empty($isInWishlist) ? 'Sudah di Wishlist' : 'Tambah ke Wishlist' ?></span>
                    </a>
                    <?php endif; ?>
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
            <h2 class="review-section-title mb-4">Ulasan Pengunjung</h2>

            <div class="rating-summary-card mb-5">
                <div class="average-rating-display">
                    <div class="rating-score"><?= number_format($averageRating, 1) ?></div>
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($averageRating) ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="total-reviews">Berdasarkan <?= count($reviews) ?> ulasan</div>
                </div>
            </div>

            <div class="reviews-list">
                <?php if (empty($reviews)): ?>
                    <div class="alert alert-secondary text-center">Belum ada ulasan untuk destinasi ini.</div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item-card mb-4">
                            <div class="review-item-header">
                                <?php if (session()->get('isLoggedIn') && session()->get('user_id') == $review['user_id']): ?>
                                    <a href="<?= base_url('destinasi/review/delete/' . $review['review_id']) ?>"
                                        class="btn-delete-review"
                                        data-review-id="<?= $review['review_id'] ?>"
                                        title="Hapus ulasan Anda">×</a>
                                <?php endif; ?>

                                <div class="review-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="review-user-info">
                                    <h4 class="user-name"><?= esc($review['nama_user']) ?></h4>
                                    <small class="review-date">
                                        <?= date('d M Y', strtotime($review['tanggal_review'])) ?>
                                    </small>
                                </div>
                                <div class="review-item-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'filled' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-item-content">
                                <p><?= nl2br(esc($review['komentar'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Logika Modal Gambar ---
    let currentIndex = 0;
    const images = document.querySelectorAll('.small-Img');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeButton = modal.querySelector('.close');
    const prevButton = modal.querySelector('.prev');
    const nextButton = modal.querySelector('.next');

            function openModal(index) {
        currentIndex = parseInt(index); // Pastikan index adalah angka
        if (images.length > 0) {
            modalImage.src = images[currentIndex].src;
            modal.style.display = "block";
        }
    }

            function closeModal() {
        modal.style.display = "none";
    }

            function moveImage(step) {
        currentIndex += step;
        if (currentIndex >= images.length) {
            currentIndex = 0; // Kembali ke gambar pertama
        } else if (currentIndex < 0) {
            currentIndex = images.length - 1; // Mundur ke gambar terakhir
        }
        modalImage.src = images[currentIndex].src;
    }

    // Pasang event listener untuk modal
    images.forEach(img => {
        img.addEventListener('click', function() {
            openModal(this.getAttribute('data-index'));
        });
    });

    if(closeButton) closeButton.addEventListener('click', closeModal);
    if(prevButton) prevButton.addEventListener('click', () => moveImage(-1));
    if(nextButton) nextButton.addEventListener('click', () => moveImage(1));

                // --- Logika Wishlist ---
    function toggleWishlist(button) {
    // Dapatkan elemen span untuk teks
    const wishlistText = button.querySelector('#wishlistText');
    const wisataId = button.getAttribute('data-wisata-id');

    // Cek status berdasarkan ada/tidaknya class 'wishlist-added' PADA TOMBOL, bukan 'text-danger' pada ikon.
    const isWishlisted = button.classList.contains('wishlist-added');
    
    // Langsung ubah tampilan tombol (Optimistic UI Update)
    // Toggle class 'wishlist-added' PADA TOMBOL.
    button.classList.toggle('wishlist-added', !isWishlisted); 
    wishlistText.textContent = isWishlisted ? 'Tambah ke Wishlist' : 'Sudah di Wishlist';
    
    // Tentukan URL berdasarkan aksi yang akan dilakukan
    const action = isWishlisted ? 'remove' : 'add';
    const url = `<?= base_url('wishlist/') ?>${action}/${wisataId}`;

    // Kirim permintaan ke server
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Respon jaringan bermasalah');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Aksi berhasil, tampilkan notifikasi sukses
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message || 'Wishlist diperbarui!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                // Jika server mengirim `success: false`, lempar error
                throw new Error(data.message || 'Gagal memperbarui wishlist.');
            }
        })
        .catch(error => {
            // JIKA TERJADI ERROR
            console.error('Error Wishlist:', error);
            
            // Kembalikan tampilan tombol ke kondisi SEMULA karena aksi gagal
            button.classList.toggle('wishlist-added', isWishlisted); 
            wishlistText.textContent = isWishlisted ? 'Sudah di Wishlist' : 'Tambah ke Wishlist';
            
            // Tampilkan notifikasi error kepada pengguna
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Aksi gagal, coba lagi.',
                showConfirmButton: false,
                timer: 3000
            });
        });
}

            // Pasang event listener untuk tombol wishlist
    const wishlistButton = document.getElementById('wishlistButton');
    if (wishlistButton) {
        wishlistButton.addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah perilaku default dari tag <a>
            toggleWishlist(this);
        });
    }

    // --- Logika Hapus Ulasan (Bagian ini sudah benar) ---
    const deleteReviewButtons = document.querySelectorAll('.btn-delete-review');
    deleteReviewButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;

            Swal.fire({
                title: 'Hapus Ulasan?',
                text: "Anda tidak akan bisa mengembalikan ulasan ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>

    <?= $this->endSection() ?>