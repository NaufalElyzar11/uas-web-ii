<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Manajemen Wisata</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 text-gray-800 mb-0">Manajemen Wisata</h1>
            <a href="<?= base_url('admin/wisata/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Wisata
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-wisata" class="table table-bordered" width="100%" cellspacing="0">
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
                        <?php $no = 1;
                        foreach ($wisata as $w): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($w['nama']) ?></td>
                                <td><?= esc($w['daerah']) ?></td>
                                <td><?= esc($w['kategori']) ?></td>
                                <td><?= number_format($w['harga'], 0, ',', '.') ?></td>
                                <td><?= esc($w['trending_score']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/wisata/edit/' . $w['wisata_id']) ?>"
                                        class="btn btn-sm btn-warning mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= base_url('admin/wisata/delete/' . $w['wisata_id']) ?>"
                                        method="post" style="display:inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
            $('#tabel-wisata').DataTable({
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