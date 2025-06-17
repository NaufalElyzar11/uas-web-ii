<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-4 text-gray-800">Tambah Wisata</h1>

<form action="<?= base_url('admin/wisata/store') ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
    </div>

    <div class="form-group">
        <label>Daerah</label>
        <input type="text" name="daerah" class="form-control" value="<?= old('daerah') ?>" required>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required><?= old('deskripsi') ?></textarea>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="<?= old('harga') ?>" required>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
            <option value="" disabled selected>Pilih Kategori</option>
            <?php
            $kategoriList = ['Alam', 'Pantai', 'Gunung', 'Budaya', 'Kota', 'Religi'];
            foreach ($kategoriList as $kategori):
            ?>
                <option value="<?= $kategori ?>" <?= old('kategori') === $kategori ? 'selected' : '' ?>>
                    <?= $kategori ?>
                </option>
            <?php endforeach; ?>
        </select>
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

<?= $this->endSection() ?>