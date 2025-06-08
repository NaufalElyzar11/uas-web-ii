<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Selamat Datang, <?= esc($user['nama']) ?>!</h5>
            <p class="card-text">Temukan destinasi wisata menarik dan pesan tiket Anda sekarang.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Destinasi Tersedia</h6>
                    <h2 class="mb-0">10+</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Wishlist Anda</h6>
                    <h2 class="mb-0">3</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Tiket Aktif</h6>
                    <h2 class="mb-0">1</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Kunjungan</h6>
                    <h2 class="mb-0">5</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="mb-4">Destinasi Populer</h5>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://syamsudinnoor-airport.co.id/frontend/uploads/defaults/UQjs8d20170907100549.jpg" class="card-img-top" alt="Destinasi 1">
                <div class="card-body">
                    <h5 class="card-title">Loksado</h5>
                    <p class="card-text">Rasakan kesejukan udara pegunungan dan pemandangan yang memukau.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://ik.imagekit.io/tvlk/blog/2024/07/shutterstock_2314280007.jpg" class="card-img-top" alt="Destinasi 2">
                <div class="card-body">
                    <h5 class="card-title">Pantai Teluk Tamiang</h5>
                    <p class="card-text">Nikmati keindahan pantai dengan pasir putih dan air yang jernih.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://indonesiakaya.com/wp-content/uploads/2020/10/Pulau_Kembang_yang_termasuk_di_dalam_wilayah_Kecamatan_Alalak_Kabupaten_Barito_Kuala.jpg" class="card-img-top" alt="Destinasi 3">
                <div class="card-body">
                    <h5 class="card-title">Pulau Kembang</h5>
                    <p class="card-text">Jelajahi keindahan pulau ini dengan beragam monyet.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 