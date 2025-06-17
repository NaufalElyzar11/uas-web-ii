<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Manajemen Booking</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Booking</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-booking" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking ID</th>
                        <th>Username</th>
                        <th>Wisata ID</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jumlah Orang</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($b['booking_id']) ?></td>
                        <td><?= esc($b['user_id']) ?></td>
                        <td><?= esc($b['wisata_id']) ?></td>
                        <td><?= esc($b['tanggal_kunjungan']) ?></td>
                        <td><?= esc($b['jumlah_orang']) ?></td>
                        <td><?= esc($b['total_harga']) ?></td>
                        <td>
                            <span class="badge badge-<?= $b['status'] === 'pending' ? 'warning' : ($b['status'] === 'confirmed' ? 'success' : 'danger') ?>">
                                <?= esc(ucfirst($b['status'])) ?>
                            </span>
                        <td>
                            <form action="<?= base_url('admin/booking/delete/'.$b['booking_id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
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
    $(document).ready(function () {
        $('#tabel-booking').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
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
                    customize: function (doc) {
                        doc.styles.tableHeader.alignment = 'left';
                        doc.defaultStyle.fontSize = 10;
                    }
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>