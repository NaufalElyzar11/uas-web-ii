<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

$error = '';
$success = '';

// Handle wishlist removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_wishlist'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $wisata_id = (int)$_POST['wisata_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND wisata_id = ?");
        $stmt->execute([$_SESSION['user_id'], $wisata_id]);
        $success = 'Destinasi berhasil dihapus dari wishlist.';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get user's wishlist
$stmt = $conn->prepare("
    SELECT w.*, wl.tanggal_tambah,
           (SELECT AVG(rating) FROM review WHERE wisata_id = w.wisata_id) as avg_rating,
           (SELECT COUNT(*) FROM review WHERE wisata_id = w.wisata_id) as review_count
    FROM wishlist wl 
    JOIN wisata w ON wl.wisata_id = w.wisata_id 
    WHERE wl.user_id = ?
    ORDER BY wl.tanggal_tambah DESC
");
$stmt->execute([$_SESSION['user_id']]);
$wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Wisata Lokal</title>
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
                        <a class="nav-link active" href="wishlist.php">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">Riwayat</a>
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

        <h2 class="mb-4">Wishlist Saya</h2>

        <?php if (empty($wishlist)): ?>
            <div class="alert alert-info">
                Wishlist Anda masih kosong. 
                <a href="dashboard.php" class="alert-link">Jelajahi destinasi wisata</a> untuk menambahkan ke wishlist.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($wishlist as $item): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['nama']); ?></h5>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="wisata_id" value="<?php echo $item['wisata_id']; ?>">
                                    <button type="submit" name="remove_wishlist" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Hapus dari wishlist?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <p class="text-muted">
                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($item['lokasi']); ?>
                            </p>

                            <div class="mb-3">
                                <div class="text-warning d-inline-block">
                                    <?php 
                                    $avg_rating = round($item['avg_rating'], 1);
                                    for($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <i class="bi bi-star<?php echo $i <= $avg_rating ? '-fill' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">
                                    (<?php echo $item['review_count']; ?> review)
                                </small>
                            </div>

                            <p class="card-text">
                                <?php echo substr(htmlspecialchars($item['deskripsi']), 0, 150) . '...'; ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-primary">
                                    <strong>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></strong>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        Ditambahkan: <?php echo date('d M Y', strtotime($item['tanggal_tambah'])); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="detail_wisata.php?id=<?php echo $item['wisata_id']; ?>" class="btn btn-primary">
                                    Lihat Detail
                                </a>
                            </div>
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