<?php
require_once '../config/database.php';
require_once '../utils/security.php';

check_auth();
check_admin();
regenerate_session();

$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    // Delete destination
    if (isset($_POST['delete'])) {
        $wisata_id = (int)$_POST['wisata_id'];
        try {
            $stmt = $conn->prepare("DELETE FROM wisata WHERE wisata_id = ?");
            $stmt->execute([$wisata_id]);
            $success = 'Destinasi wisata berhasil dihapus.';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    // Add or update destination
    else {
        $nama = sanitize_input($_POST['nama']);
        $lokasi = sanitize_input($_POST['lokasi']);
        $deskripsi = sanitize_input($_POST['deskripsi']);
        $harga = (float)sanitize_input($_POST['harga']);
        $kategori = sanitize_input($_POST['kategori']);
        $daerah = sanitize_input($_POST['daerah']);

        try {
            // Update existing destination
            if (isset($_POST['wisata_id'])) {
                $wisata_id = (int)$_POST['wisata_id'];
                $stmt = $conn->prepare("
                    UPDATE wisata 
                    SET nama = ?, lokasi = ?, deskripsi = ?, harga = ?, kategori = ?, daerah = ? 
                    WHERE wisata_id = ?
                ");
                $stmt->execute([$nama, $lokasi, $deskripsi, $harga, $kategori, $daerah, $wisata_id]);
                $success = 'Destinasi wisata berhasil diperbarui.';
            }
            // Add new destination
            else {
                $stmt = $conn->prepare("
                    INSERT INTO wisata (nama, lokasi, deskripsi, harga, kategori, daerah) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$nama, $lokasi, $deskripsi, $harga, $kategori, $daerah]);
                $success = 'Destinasi wisata berhasil ditambahkan.';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get all destinations
$stmt = $conn->query("SELECT * FROM wisata ORDER BY nama");
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all categories (from minat table)
$stmt = $conn->query("SELECT nama FROM minat ORDER BY nama");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wisata - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="wisata.php">Kelola Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="berita.php">Kelola Berita</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Add New Destination Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Tambah Destinasi Wisata</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Destinasi</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" min="0" step="1000" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="daerah" class="form-label">Daerah</label>
                            <input type="text" class="form-control" id="daerah" name="daerah" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Tambah Destinasi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Destinations List -->
        <div class="card">
            <div class="card-header">
                <h4>Daftar Destinasi Wisata</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($destinations as $dest): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($dest['nama']); ?></td>
                                <td><?php echo htmlspecialchars($dest['lokasi']); ?></td>
                                <td><?php echo htmlspecialchars($dest['kategori']); ?></td>
                                <td>Rp <?php echo number_format($dest['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="editDestination(<?php echo htmlspecialchars(json_encode($dest)); ?>)">
                                        Edit
                                    </button>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="wisata_id" value="<?php echo $dest['wisata_id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus destinasi ini?')">
                                            Hapus
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
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Destinasi Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="wisata_id" id="edit_wisata_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama" class="form-label">Nama Destinasi</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="edit_lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="edit_lokasi" name="lokasi" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_harga" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="edit_harga" name="harga" min="0" step="1000" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="edit_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="edit_kategori" name="kategori" required>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>">
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="edit_daerah" class="form-label">Daerah</label>
                                <input type="text" class="form-control" id="edit_daerah" name="daerah" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDestination(destination) {
            document.getElementById('edit_wisata_id').value = destination.wisata_id;
            document.getElementById('edit_nama').value = destination.nama;
            document.getElementById('edit_lokasi').value = destination.lokasi;
            document.getElementById('edit_deskripsi').value = destination.deskripsi;
            document.getElementById('edit_harga').value = destination.harga;
            document.getElementById('edit_kategori').value = destination.kategori;
            document.getElementById('edit_daerah').value = destination.daerah;
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html> 