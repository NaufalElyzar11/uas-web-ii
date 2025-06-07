<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$wisata_id = (int)$_GET['id'];

// Get wisata details
$stmt = $conn->prepare("SELECT * FROM wisata WHERE wisata_id = ?");
$stmt->execute([$wisata_id]);
$wisata = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$wisata) {
    header('Location: dashboard.php');
    exit();
}

// Get visitor statistics
$stats = [];
$periods = ['day' => 'hari', 'week' => 'minggu', 'month' => 'bulan', 'year' => 'tahun'];
foreach ($periods as $period => $label) {
    $sql = "SELECT SUM(jumlah_pengunjung) as total FROM statistik_kunjungan 
            WHERE wisata_id = ? AND tanggal >= DATE_SUB(CURRENT_DATE, INTERVAL 1 " . strtoupper($period) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$wisata_id]);
    $stats[$period] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

// Get reviews
$stmt = $conn->prepare("
    SELECT r.*, u.nama as user_nama 
    FROM review r 
    JOIN users u ON r.user_id = u.user_id 
    WHERE r.wisata_id = ? 
    ORDER BY r.tanggal_review DESC
");
$stmt->execute([$wisata_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$avg_rating = 0;
if (!empty($reviews)) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($total_rating / count($reviews), 1);
}

// Check if user has already reviewed
$stmt = $conn->prepare("SELECT COUNT(*) FROM review WHERE user_id = ? AND wisata_id = ?");
$stmt->execute([$_SESSION['user_id'], $wisata_id]);
$has_reviewed = $stmt->fetchColumn() > 0;

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $jumlah_orang = (int)sanitize_input($_POST['jumlah_orang']);
    $total_harga = $wisata['harga'] * $jumlah_orang;

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            INSERT INTO pemesanan (user_id, wisata_id, jumlah_orang, total_harga, tanggal_pemesanan, status) 
            VALUES (?, ?, ?, ?, CURRENT_DATE, 'menunggu')
        ");
        $stmt->execute([$_SESSION['user_id'], $wisata_id, $jumlah_orang, $total_harga]);
        $pemesanan_id = $conn->lastInsertId();

        $conn->commit();
        header("Location: pembayaran.php?id=" . $pemesanan_id);
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($wisata['nama']); ?> - Wisata Lokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Wisata Lokal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wishlist.php">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">Riwayat</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Destination Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($wisata['nama']); ?></h1>
                        <p class="text-muted">
                            <strong>Lokasi:</strong> <?php echo htmlspecialchars($wisata['lokasi']); ?><br>
                            <strong>Kategori:</strong> <?php echo htmlspecialchars($wisata['kategori']); ?><br>
                            <strong>Harga:</strong> Rp <?php echo number_format($wisata['harga'], 0, ',', '.'); ?>
                        </p>
                        <div class="mb-4">
                            <h4>Deskripsi</h4>
                            <p><?php echo nl2br(htmlspecialchars($wisata['deskripsi'])); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Statistik Pengunjung</h4>
                        <div class="row">
                            <?php foreach ($stats as $period => $total): ?>
                            <div class="col-md-3 text-center mb-3">
                                <h5><?php echo number_format($total); ?></h5>
                                <small class="text-muted">Per <?php echo $periods[$period]; ?></small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="card">
                    <div class="card-body">
                        <h4>Review (<?php echo count($reviews); ?>)</h4>
                        <div class="mb-3">
                            <h5>Rating Rata-rata: <?php echo $avg_rating; ?> / 5</h5>
                            <div class="text-warning">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?php echo $i <= $avg_rating ? '-fill' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="list-group">
                            <?php foreach ($reviews as $review): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <h6><?php echo htmlspecialchars($review['user_nama']); ?></h6>
                                    <small><?php echo date('d M Y', strtotime($review['tanggal_review'])); ?></small>
                                </div>
                                <div class="text-warning mb-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($review['komentar']); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Booking Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Pesan Tiket</h4>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="mb-3">
                                <label for="jumlah_orang" class="form-label">Jumlah Orang</label>
                                <input type="number" class="form-control" id="jumlah_orang" name="jumlah_orang" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Harga</label>
                                <div class="form-control" id="total_harga">Rp 0</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="book" class="btn btn-primary">Pesan Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add to Wishlist -->
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="add_wishlist.php">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="wisata_id" value="<?php echo $wisata_id; ?>">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-heart"></i> Tambah ke Wishlist
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calculate total price
        document.getElementById('jumlah_orang').addEventListener('input', function() {
            const harga = <?php echo $wisata['harga']; ?>;
            const jumlah = this.value || 0;
            const total = harga * jumlah;
            document.getElementById('total_harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
        });
    </script>
</body>
</html> 