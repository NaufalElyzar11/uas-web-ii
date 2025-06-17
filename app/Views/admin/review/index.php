<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Manajemen Review</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Review</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Wisata ID</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($reviews as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($r['user_id']) ?></td>
                        <td><?= esc($r['wisata_id']) ?></td>
                        <td><?= esc($r['rating']) ?></td>
                        <td><?= esc($r['komentar']) ?></td>
                        <td><?= esc($r['tanggal_review']) ?></td>
                        <td>
                            <form action="<?= base_url('admin/review/delete/'.$r['review_id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
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