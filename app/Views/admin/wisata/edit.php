<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Edit Wisata</h1>

<?php if (session()->has('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Wisata</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/wisata/update/' . $wisata['wisata_id']) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= old('nama', $wisata['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label>Daerah</label>
                <select name="daerah" class="form-control" required>
                    <option value="" disabled>Pilih Daerah</option>
                    <?php
                    $daerahList = [
                        'Banjarbaru', 'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
                        'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
                        'Kotabaru', 'Barito Timur', 'Balangan'
                    ];
                    foreach ($daerahList as $daerah): ?>
                        <option value="<?= $daerah ?>" <?= old('daerah', $wisata['daerah']) === $daerah ? 'selected' : '' ?>><?= $daerah ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required><?= old('deskripsi', $wisata['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= old('harga', $wisata['harga']) ?>" required min="1" id="inputHarga">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="" disabled>Pilih Kategori</option>
                    <?php foreach ($kategoriList as $kategori) : ?>
                        <option value="<?= $kategori['kategori_id'] ?>" <?= old('kategori_id', $wisata['kategori_id']) == $kategori['kategori_id'] ? 'selected' : '' ?>>
                            <?= $kategori['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control" value="<?= old('latitude', $wisata['latitude']) ?>" required>
            </div>

            <div class="form-group">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control" value="<?= old('longitude', $wisata['longitude']) ?>" required>
            </div>

            <div class="form-group">
                <label>Galeri Saat Ini</label>
                <div class="row mb-3">
                    <?php
                    $galleryPath = FCPATH . 'uploads/wisata/gallery/' . $wisata['wisata_id'];
                    if (is_dir($galleryPath)) {
                        $files = scandir($galleryPath);
                        foreach ($files as $file) {
                            if ($file != '.' && $file != '..' && in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<div class="col-md-3 mb-2 position-relative">';
                                echo '<div class="gallery-item">';
                                echo '<img src="' . base_url('uploads/wisata/gallery/' . $wisata['wisata_id'] . '/' . $file) . '" 
                                    class="img-fluid rounded" 
                                    alt="Gallery Image">';
                                echo '<button type="button" 
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-image" 
                                    data-filename="' . $file . '"
                                    data-wisata-id="' . $wisata['wisata_id'] . '">
                                    <i class="fas fa-trash"></i>
                                </button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                    ?>
                </div>

                <label>Tambah Gambar Baru</label>
                <input type="file" name="gambar[]" class="form-control-file" multiple accept="image/jpeg,image/png,image/jpg">
                <small class="text-muted">Pilih hingga 7 gambar (JPG/PNG, maks 2MB per gambar)</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Wisata</button>
            <a href="<?= base_url('admin/wisata') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-image');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filename = this.getAttribute('data-filename');
            const wisataId = this.getAttribute('data-wisata-id');
            
            Swal.fire({
                title: 'Hapus Gambar?',
                text: "Gambar yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('admin/wisata/delete-image/') ?>${wisataId}/${filename}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Gambar berhasil dihapus',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Remove the image container from the DOM
                                this.closest('.col-md-3').remove();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Gagal menghapus gambar',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus gambar',
                            'error'
                        );
                    });
                }
            });
        });
    });

    var hargaInput = document.getElementById('inputHarga');
    if(hargaInput) {
        hargaInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^\d]/g, '');
        });
    }
});
</script>

<style>
.gallery-item {
    position: relative;
    margin-bottom: 15px;
}

.gallery-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.delete-image {
    opacity: 0.8;
    transition: opacity 0.3s;
}

.delete-image:hover {
    opacity: 1;
}
</style>

<?= $this->endSection() ?> 