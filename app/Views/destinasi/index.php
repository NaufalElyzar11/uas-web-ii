<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/destinasi.css') ?>">
<div class="container mt-4">
    <h2 class="mb-4 text-center"><?= $title ?></h2>

    <div class="search-container mb-4">
        <form action="<?= base_url('destinasi/search') ?>" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="Cari destinasi wisata..." required>
            <button type="submit"><i class="fas fa-search"></i> Cari </button>
        </form>
    </div>

    <div class="filter-container mb-4">
        <div class="filters">
            <label>
                <span>Kategori:</span>
                <select id="kategori-filter" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $kategori) : ?>
                        <option value="<?= $kategori['kategori_id'] ?>"><?= esc($kategori['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Daerah:</span>
                <select id="daerah-filter" class="form-select">
                    <option value="">Semua Daerah</option>
                    <?php
                    $daerahList = [];
                    foreach ($wisata as $item) {
                        if (!empty($item['daerah']) && !in_array($item['daerah'], $daerahList)) {
                            $daerahList[] = $item['daerah'];
                            echo '<option value="' . esc($item['daerah']) . '">' . esc($item['daerah']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </label>
            <label>
                <span>Urutkan:</span>
                <select id="sort-filter" class="form-select">
                    <option value="name-asc">Nama (A-Z)</option>
                    <option value="name-desc">Nama (Z-A)</option>
                    <option value="price-asc">Harga (Terendah)</option>
                    <option value="price-desc">Harga (Tertinggi)</option>
                </select>
            </label>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($wisata)): ?>
        <div class="wisata-grid">
            <?php foreach ($wisata as $item): ?>
                <div class="wisata-card"
                    data-kategori="<?= esc($item['kategori_id'] ?? '0') ?>"
                    data-daerah="<?= esc($item['daerah'] ?? 'Indonesia') ?>"
                    data-nama="<?= esc($item['nama'] ?? '') ?>"
                    data-harga="<?= $item['harga'] ?? 0 ?>">
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
        <div class="alert alert-info">Tidak ada destinasi wisata untuk ditampilkan.</div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriFilter = document.getElementById('kategori-filter');
        const daerahFilter = document.getElementById('daerah-filter');
        const sortFilter = document.getElementById('sort-filter');
        const wisataCards = document.querySelectorAll('.wisata-card');

        function applyFilters() {
            const selectedKategori = kategoriFilter.value;
            const selectedDaerah = daerahFilter.value;
            const sortOption = sortFilter.value;


            const wisataArray = Array.from(wisataCards);


            wisataArray.sort((a, b) => {
                if (sortOption === 'name-asc') {
                    return a.dataset.nama.localeCompare(b.dataset.nama);
                } else if (sortOption === 'name-desc') {
                    return b.dataset.nama.localeCompare(a.dataset.nama);
                } else if (sortOption === 'price-asc') {
                    return parseInt(a.dataset.harga) - parseInt(b.dataset.harga);
                } else if (sortOption === 'price-desc') {
                    return parseInt(b.dataset.harga) - parseInt(a.dataset.harga);
                }
                return 0;
            });

            wisataCards.forEach(card => {
                card.style.display = 'none';
            });

            wisataArray.forEach(card => {
                const cardKategori = card.dataset.kategori;
                const cardDaerah = card.dataset.daerah;

                if ((selectedKategori === '' || cardKategori === selectedKategori) &&
                    (selectedDaerah === '' || cardDaerah === selectedDaerah)) {
                    card.style.display = 'block';
                }
            });


            const container = document.querySelector('.wisata-grid');
            wisataArray.forEach(card => {
                container.appendChild(card);
            });
        }

        kategoriFilter.addEventListener('change', applyFilters);
        daerahFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
    });
</script>
<?= $this->endSection() ?>