<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Tambah Wisata</h1>

<?php if (session()->has('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<form action="<?= base_url('admin/wisata/store') ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
    </div>

    <div class="form-group">
        <label>Daerah</label>
        <select name="daerah" class="form-control" required>
            <option value="" disabled selected>Pilih Daerah</option>
            <?php
            $daerahList = [
                'Banjarbaru',
                'Banjarmasin',
                'Banjar',
                'Barito Kuala',
                'Tapin',
                'Hulu Sungai Selatan',
                'Hulu Sungai Tengah',
                'Hulu Sungai Utara',
                'Tanah Laut',
                'Tanah Bumbu',
                'Kotabaru',
                'Barito Timur',
                'Balangan'
            ];
            foreach ($daerahList as $daerah): ?>
                <option value="<?= $daerah ?>" <?= old('daerah') === $daerah ? 'selected' : '' ?>><?= $daerah ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required><?= old('deskripsi') ?></textarea>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="<?= old('harga') ?>" required min="1" id="inputHarga">
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="kategori_id" class="form-control" required>
            <option value="" disabled selected>Pilih Kategori</option>
            <?php foreach ($kategoriList as $kategori) : ?>
                <option value="<?= $kategori['kategori_id'] ?>" <?= old('kategori_id') == $kategori['kategori_id'] ? 'selected' : '' ?>>
                    <?= $kategori['nama_kategori'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Latitude</label>
        <input type="text" name="latitude" class="form-control" value="<?= old('latitude') ?>" required>
    </div>

    <div class="form-group">
        <label>Longitude</label>
        <input type="text" name="longitude" class="form-control" value="<?= old('longitude') ?>" required>
    </div>

    <div class="form-group">
        <label>Link Video (YouTube)</label>
        <input type="url" name="link_video" class="form-control" value="<?= old('link_video') ?>" pattern="https://(www\.)?(youtube\.com|youtu\.be)/.+" placeholder="https://youtube.com/..." title="Masukkan link YouTube yang valid">
        <small class="text-muted">Contoh: https://www.youtube.com/watch?v=xxxx atau https://youtu.be/xxxx</small>
    </div>

    <div class="form-group">
        <label>Gambar</label>
        <input type="file" name="gambar[]" class="form-control-file" multiple
            accept="image/jpeg,image/png,image/jpg">
        <small class="text-muted">Pilih hingga 7 gambar (JPG/PNG, maks 2MB per gambar)</small>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/wisata') ?>" class="btn btn-secondary">Batal</a>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hargaInput = document.getElementById('inputHarga');
        if (hargaInput) {
            hargaInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^\d]/g, '');
            });
        }
    });
</script>

<?= $this->endSection() ?>