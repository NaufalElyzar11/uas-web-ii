<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

$error = '';
$success = '';

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $wisata_id = (int)$_POST['wisata_id'];
    $rating = (int)sanitize_input($_POST['rating']);
    $komentar = sanitize_input($_POST['komentar']);

    try {
        // Check if user has already reviewed this destination
        $stmt = $conn->prepare("SELECT review_id FROM review WHERE user_id = ? AND wisata_id = ?");
        $stmt->execute([$_SESSION['user_id'], $wisata_id]);
        
        if ($stmt->fetch()) {
            $error = 'Anda sudah memberikan review untuk destinasi ini.';
        } else {
            $stmt = $conn->prepare("
                INSERT INTO review (user_id, wisata_id, rating, komentar, tanggal_review) 
                VALUES (?, ?, ?, ?, CURRENT_DATE)
            ");
            $stmt->execute([$_SESSION['user_id'], $wisata_id, $rating, $komentar]);
            $success = 'Review berhasil ditambahkan.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get user's bookings with destination details
$stmt = $conn->prepare("
    SELECT p.*, w.nama as wisata_nama, w.wisata_id,
           pb.status as status_pembayaran,
           r.rating, r.komentar
    FROM pemesanan p 
    JOIN wisata w ON p.wisata_id = w.wisata_id 
    LEFT JOIN pembayaran pb ON p.pemesanan_id = pb.pemesanan_id
    LEFT JOIN review r ON p.wisata_id = r.wisata_id AND r.user_id = p.user_id
    WHERE p.user_id = ?
    ORDER BY p.tanggal_pemesanan DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Wisata Lokal</title>
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
                        <a class="nav-link active" href="riwayat.php">Riwayat</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
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

        <h2 class="mb-4">Riwayat Pemesanan</h2>

        <?php if (empty($bookings)): ?>
            <div class="alert alert-info">Anda belum memiliki riwayat pemesanan.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($bookings as $booking): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($booking['wisata_nama']); ?></h5>
                            
                            <div class="mb-3">
                                <strong>Tanggal Pemesanan:</strong> 
                                <?php echo date('d M Y', strtotime($booking['tanggal_pemesanan'])); ?>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Jumlah Orang:</strong> <?php echo $booking['jumlah_orang']; ?><br>
                                <strong>Total Harga:</strong> 
                                Rp <?php echo number_format($booking['total_harga'], 0, ',', '.'); ?>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <?php if ($booking['status_pembayaran'] === 'berhasil'): ?>
                                    <span class="badge bg-success">Pembayaran Berhasil</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Menunggu Pembayaran</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($booking['status_pembayaran'] === 'berhasil'): ?>
                                <?php if ($booking['rating']): ?>
                                    <!-- Show existing review -->
                                    <div class="mb-3">
                                        <strong>Review Anda:</strong><br>
                                        <div class="text-warning">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= $booking['rating'] ? '-fill' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="mt-2"><?php echo htmlspecialchars($booking['komentar']); ?></p>
                                    </div>
                                <?php else: ?>
                                    <!-- Review form -->
                                    <form method="POST" action="" class="mt-3">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="wisata_id" value="<?php echo $booking['wisata_id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <div class="rating">
                                                <?php for($i = 5; $i >= 1; $i--): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" 
                                                           id="rating<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                                    <label class="form-check-label" for="rating<?php echo $i; ?>">
                                                        <?php echo $i; ?> <i class="bi bi-star-fill text-warning"></i>
                                                    </label>
                                                </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="komentar" class="form-label">Komentar</label>
                                            <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                                        </div>

                                        <button type="submit" name="submit_review" class="btn btn-primary">
                                            Kirim Review
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 