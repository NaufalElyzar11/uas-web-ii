<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Edit Wisata</h1>
<form action="<?= base_url('admin/wisata/update/'.$wisata['wisata_id']) ?>" method="post">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama', $wisata['nama']) ?>" required>
    </div>
    <div class="form-group">
        <label>Daerah</label>
        <input type="text" name="daerah" class="form-control" value="<?= old('daerah', $wisata['daerah']) ?>" required>
    </div>
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required><?= old('deskripsi', $wisata['deskripsi']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" value="<?= old('harga', $wisata['harga']) ?>" required>
    </div>
    <div class="form-group">
        <label>Kategori</label>
        <input type="text" name="kategori" class="form-control" value="<?= old('kategori', $wisata['kategori']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/wisata') ?>" class="btn btn-secondary">Batal</a>
</form>
<?= $this->endSection() ?> 