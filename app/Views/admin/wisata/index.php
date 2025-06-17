<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Manajemen Wisata</h1>
<a href="<?= base_url('admin/wisata/create') ?>" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Wisata</a>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Wisata</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Daerah</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Trending</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($wisata as $w): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($w['nama']) ?></td>
                        <td><?= esc($w['daerah']) ?></td>
                        <td><?= esc($w['kategori']) ?></td>
                        <td><?= number_format($w['harga'],0,',','.') ?></td>
                        <td><?= esc($w['trending_score']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/wisata/edit/'.$w['wisata_id']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="<?= base_url('admin/wisata/delete/'.$w['wisata_id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 