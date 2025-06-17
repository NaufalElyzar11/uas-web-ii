<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Manajemen Berita</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6>
            <a href="<?= base_url('admin/berita/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Berita
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-berita" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($berita as $b): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <img src="<?= esc($b['gambar']) ?>"
                                    alt="<?= esc($b['judul']) ?>"
                                    style="max-width: 80px; height: auto;"
                                    onerror="this.src='<?= base_url('uploads/berita/default.jpg') ?>'">
                            </td>
                            <td><?= esc($b['judul']) ?></td>
                            <td><?= date('d M Y', strtotime($b['tanggal_post'])) ?></td>
                            <td>
                                <div class="d-flex" style="gap: 0.5rem;">
                                    <a href="<?= base_url('admin/berita/edit/' . $b['berita_id']) ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= base_url('admin/berita/delete/' . $b['berita_id']) ?>"
                                        method="post"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-berita').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    className: 'btn btn-success mb-3 mr-2',
                    text: '<i class="fas fa-file-excel"></i> Export Excel'
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-danger mb-3',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.styles.tableHeader.alignment = 'left';
                        doc.defaultStyle.fontSize = 10;
                    }
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>