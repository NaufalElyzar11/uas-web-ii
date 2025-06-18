<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<head>
<link rel="stylesheet" href="<?= base_url('css/create.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
    <h1 class="mb-4">Pembelian Tiket</h1>
    
    <div class="row d-flex align-items-stretch">
        <div class="col-md-8">
            <div class="card mb-4 h-100">
                <div class="card-body">
                    <h2 class="card-title"><?= esc($wisata['nama']) ?></h2>
                    <p class="card-text"><i class="fas fa-map-marker-alt"></i> <?= esc($wisata['daerah']) ?></p>
                    <div class="mb-3">
                        <img src="<?= (filter_var($wisata['gambar_wisata'], FILTER_VALIDATE_URL)) ? $wisata['gambar_wisata'] : base_url('uploads/wisata/' . ($wisata['gambar_wisata'] ?? 'default.jpg')) ?>" 
                            class="img-fluid rounded" 
                            alt="<?= esc($wisata['nama']) ?>" 
                            style="max-height: 300px; width: auto;">
                    </div>
                    <div class="wisata-info">
                        <p><strong>Kategori:</strong> <?= esc($wisata['nama_kategori']) ?></p>
                        <p><strong>Harga:</strong> Rp <?= number_format($wisata['harga'], 0, ',', '.') ?> per orang</p>
                        <p><strong>Deskripsi:</strong></p>
                        <p><?= nl2br(esc($wisata['deskripsi'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pembelian</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('booking/store') ?>" method="post">
                        <input type="hidden" name="wisata_id" value="<?= $wisata['wisata_id'] ?>">
                        
                        <div class="mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" required min="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_orang" class="form-label">Jumlah Orang</label>
                            <input type="number" class="form-control" id="jumlah_orang" name="jumlah_orang" required min="1" value="1">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Harga per Orang</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <div class="form-control bg-light" style="border: none;" id="harga_satuan"><?= number_format($wisata['harga'], 0, ',', '.') ?></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <div class="form-control bg-light" style="border: none;" id="total_harga"><?= number_format($wisata['harga'], 0, ',', '.') ?></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Beli Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaSatuan = <?= $wisata['harga'] ?>;
    const jumlahInput = document.getElementById('jumlah_orang');
    const totalHargaInput = document.getElementById('total_harga');
    
    jumlahInput.addEventListener('input', function() {
        const jumlah = parseInt(jumlahInput.value) || 1;
        const total = hargaSatuan * jumlah;
        totalHargaInput.textContent = new Intl.NumberFormat('id-ID').format(total);
    });
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_kunjungan').setAttribute('min', today);

   jumlahInput.addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
            const total = hargaSatuan * 1;
            totalHargaInput.textContent = new Intl.NumberFormat('id-ID').format(total);
        }
    });
});
</script>

<style>
.wisata-info {
    line-height: 1.6;
}
.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
}
.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
}
</style>
<?= $this->endSection() ?>
