<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Tambah Berita</h1>

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
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Berita</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/berita/store') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" class="form-control" id="judul" name="judul" required
                    value="<?= old('judul') ?>" placeholder="Masukkan judul berita">
            </div>

            <div class="form-group">
                <label for="konten">Konten Berita</label>
                <textarea class="form-control" id="konten" name="konten" rows="10" required
                    placeholder="Tulis konten berita di sini"><?= old('konten') ?></textarea>
            </div>

            <div class="form-group">
                <label for="gambar">Link Gambar Berita</label>
                <input type="url" class="form-control" id="gambar" name="gambar" required
                    placeholder="https://example.com/contoh.jpg">
                <small class="text-muted">Masukkan URL gambar (format: JPG, JPEG, PNG)</small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Berita</button>
            <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>