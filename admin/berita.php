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

    // Delete news
    if (isset($_POST['delete'])) {
        $berita_id = (int)$_POST['berita_id'];
        try {
            $stmt = $conn->prepare("DELETE FROM berita WHERE berita_id = ?");
            $stmt->execute([$berita_id]);
            $success = 'Berita berhasil dihapus.';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    // Add or update news
    else {
        $judul = sanitize_input($_POST['judul']);
        $konten = sanitize_input($_POST['konten']);
        $wisata_id = (int)sanitize_input($_POST['wisata_id']);
        $tanggal_post = date('Y-m-d');

        try {
            // Update existing news
            if (isset($_POST['berita_id'])) {
                $berita_id = (int)$_POST['berita_id'];
                $stmt = $conn->prepare("
                    UPDATE berita 
                    SET judul = ?, konten = ?, wisata_id = ?, tanggal_post = ? 
                    WHERE berita_id = ?
                ");
                $stmt->execute([$judul, $konten, $wisata_id, $tanggal_post, $berita_id]);
                $success = 'Berita berhasil diperbarui.';
            }
            // Add new news
            else {
                $stmt = $conn->prepare("
                    INSERT INTO berita (judul, konten, wisata_id, tanggal_post) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$judul, $konten, $wisata_id, $tanggal_post]);
                $success = 'Berita berhasil ditambahkan.';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get all news
$stmt = $conn->query("
    SELECT b.*, w.nama as wisata_nama 
    FROM berita b 
    JOIN wisata w ON b.wisata_id = w.wisata_id 
    ORDER BY b.tanggal_post DESC
");
$news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all destinations for dropdown
$stmt = $conn->query("SELECT wisata_id, nama FROM wisata ORDER BY nama");
$destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin</title>
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
                        <a class="nav-link" href="wisata.php">Kelola Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="berita.php">Kelola Berita</a>
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

        <!-- Add New News Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Tambah Berita</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Berita</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>

                    <div class="mb-3">
                        <label for="konten" class="form-label">Konten</label>
                        <textarea class="form-control" id="konten" name="konten" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="wisata_id" class="form-label">Terkait Destinasi</label>
                        <select class="form-select" id="wisata_id" name="wisata_id" required>
                            <option value="">Pilih Destinasi</option>
                            <?php foreach ($destinations as $dest): ?>
                            <option value="<?php echo $dest['wisata_id']; ?>">
                                <?php echo htmlspecialchars($dest['nama']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Tambah Berita</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- News List -->
        <div class="card">
            <div class="card-header">
                <h4>Daftar Berita</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Destinasi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($news_list as $news): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($news['judul']); ?></td>
                                <td><?php echo htmlspecialchars($news['wisata_nama']); ?></td>
                                <td><?php echo date('d M Y', strtotime($news['tanggal_post'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="editNews(<?php echo htmlspecialchars(json_encode($news)); ?>)">
                                        Edit
                                    </button>
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="berita_id" value="<?php echo $news['berita_id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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
                    <h5 class="modal-title">Edit Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="editForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="berita_id" id="edit_berita_id">
                        
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul Berita</label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_konten" class="form-label">Konten</label>
                            <textarea class="form-control" id="edit_konten" name="konten" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_wisata_id" class="form-label">Terkait Destinasi</label>
                            <select class="form-select" id="edit_wisata_id" name="wisata_id" required>
                                <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo $dest['wisata_id']; ?>">
                                    <?php echo htmlspecialchars($dest['nama']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
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
        function editNews(news) {
            document.getElementById('edit_berita_id').value = news.berita_id;
            document.getElementById('edit_judul').value = news.judul;
            document.getElementById('edit_konten').value = news.konten;
            document.getElementById('edit_wisata_id').value = news.wisata_id;
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html> 