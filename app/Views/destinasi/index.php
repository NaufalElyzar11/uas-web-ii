<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4"><?= $title ?></h1>
    
    <!-- Search Form -->
    <div class="search-container mb-4">
        <form action="<?= base_url('destinasi/search') ?>" method="get" class="search-form">
            <input type="text" name="keyword" placeholder="Cari destinasi wisata..." required>
            <button type="submit"><i class="fas fa-search"></i> Cari</button>
        </form>
    </div>

    <!-- Filter Options -->
    <div class="filter-container mb-4">
        <div class="filters">
            <label>
                <span>Kategori:</span>
                <select id="kategori-filter" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Alam">Alam</option>
                    <option value="Pantai">Pantai</option>
                    <option value="Gunung">Gunung</option>
                    <option value="Budaya">Budaya</option>
                    <option value="Kota">Kota</option>
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
             data-kategori="<?= esc($item['kategori'] ?? 'Umum') ?>" 
             data-daerah="<?= esc($item['daerah'] ?? 'Indonesia') ?>"
             data-nama="<?= esc($item['nama'] ?? '') ?>"
             data-harga="<?= $item['harga'] ?? 0 ?>">
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
    <div class="alert alert-info">Tidak ada destinasi wisata untuk ditampilkan.</div>
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
.filter-container {
    background: #f5f7fa;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}
.filters label {
    display: flex;
    align-items: center;
    gap: 8px;
}
.filters select {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
    background-color: white;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functions
    const kategoriFilter = document.getElementById('kategori-filter');
    const daerahFilter = document.getElementById('daerah-filter');
    const sortFilter = document.getElementById('sort-filter');
    const wisataCards = document.querySelectorAll('.wisata-card');

    // Apply filters function
    function applyFilters() {
        const selectedKategori = kategoriFilter.value;
        const selectedDaerah = daerahFilter.value;
        const sortOption = sortFilter.value;

        // Convert nodelist to array for sorting
        const wisataArray = Array.from(wisataCards);

        // Sort the array
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

        // Hide all cards first
        wisataCards.forEach(card => {
            card.style.display = 'none';
        });

        // Show cards that match the filter
        wisataArray.forEach(card => {
            const cardKategori = card.dataset.kategori;
            const cardDaerah = card.dataset.daerah;
            
            // If both filters match (or are empty)
            if ((selectedKategori === '' || cardKategori === selectedKategori) && 
                (selectedDaerah === '' || cardDaerah === selectedDaerah)) {
                card.style.display = 'block';
            }
        });
        
        // Reorder cards in the DOM
        const container = document.querySelector('.wisata-grid');
        wisataArray.forEach(card => {
            container.appendChild(card);
        });
    }

    // Add event listeners
    kategoriFilter.addEventListener('change', applyFilters);
    daerahFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
});
</script>
<?= $this->endSection() ?>
