<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Edit Berita</h1>

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
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Berita</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/berita/update/' . $berita['berita_id']) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" class="form-control" id="judul" name="judul" required
                    value="<?= old('judul', $berita['judul']) ?>" placeholder="Masukkan judul berita">
            </div>

            <div class="form-group">
                <label for="konten">Konten Berita</label>
                <textarea class="form-control" id="konten" name="konten" rows="10" required
                    placeholder="Tulis konten berita di sini"><?= old('konten', $berita['konten']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="wisata_id">Pilih Wisata</label>
                <select class="form-control" id="wisata_id" name="wisata_id" required>
                    <option value="">-- Pilih Wisata --</option>
                    <?php foreach ($wisataList as $wisata): ?>
                        <option value="<?= $wisata['wisata_id'] ?>" <?= old('wisata_id', $berita['wisata_id']) == $wisata['wisata_id'] ? 'selected' : '' ?>>
                            <?= esc($wisata['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Gambar Saat Ini</label>
                <div class="mb-2">
                    <img src="<?= esc($berita['gambar']) ?>"
                        alt="<?= esc($berita['judul']) ?>"
                        style="max-width: 200px; height: auto;"
                        onerror="this.src='<?= base_url('uploads/berita/default.jpg') ?>'">
                </div>

                <label for="gambar">Ganti Link Gambar</label>
                <input type="url" class="form-control" id="gambar" name="gambar"
                    placeholder="https://example.com/gambar.jpg"
                    value="<?= esc($berita['gambar']) ?>">
                <small class="text-muted">Masukkan URL gambar baru. Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Berita</button>
            <a href="<?= base_url('admin/berita') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>