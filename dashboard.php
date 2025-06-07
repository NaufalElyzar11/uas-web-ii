<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

// Get user's interests
$stmt = $conn->prepare("
    SELECT m.minat_id, m.nama 
    FROM minat m 
    JOIN user_minat um ON m.minat_id = um.minat_id 
    WHERE um.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user_interests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get promotions
$stmt = $conn->query("
    SELECT * FROM wisata 
    WHERE harga < (SELECT AVG(harga) FROM wisata) 
    LIMIT 6
");
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get trending destinations
$stmt = $conn->query("
    SELECT * FROM wisata 
    ORDER BY trending_score DESC 
    LIMIT 6
");
$trending = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get destinations based on user interests
$interest_destinations = [];
if (!empty($user_interests)) {
    $interest_ids = array_column($user_interests, 'minat_id');
    $placeholders = str_repeat('?,', count($interest_ids) - 1) . '?';
    $stmt = $conn->prepare("
        SELECT DISTINCT w.* 
        FROM wisata w 
        WHERE w.kategori IN (
            SELECT nama FROM minat WHERE minat_id IN ($placeholders)
        )
        LIMIT 6
    ");
    $stmt->execute($interest_ids);
    $interest_destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get latest news
$stmt = $conn->query("
    SELECT b.*, w.nama as wisata_nama 
    FROM berita b 
    JOIN wisata w ON b.wisata_id = w.wisata_id 
    ORDER BY b.tanggal_post DESC 
    LIMIT 5
");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Wisata Lokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wishlist.php">Wishlist</a>
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
        <!-- Promotions Section -->
        <section class="mb-5">
            <h2>Promosi</h2>
            <div class="row">
                <?php foreach ($promotions as $promo): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($promo['nama']); ?></h5>
                            <p class="card-text">
                                <strong>Lokasi:</strong> <?php echo htmlspecialchars($promo['lokasi']); ?><br>
                                <strong>Harga:</strong> Rp <?php echo number_format($promo['harga'], 0, ',', '.'); ?>
                            </p>
                            <a href="detail_wisata.php?id=<?php echo $promo['wisata_id']; ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Trending Section -->
        <section class="mb-5">
            <h2>Wisata Trending</h2>
            <div class="row">
                <?php foreach ($trending as $trend): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($trend['nama']); ?></h5>
                            <p class="card-text">
                                <strong>Lokasi:</strong> <?php echo htmlspecialchars($trend['lokasi']); ?><br>
                                <strong>Harga:</strong> Rp <?php echo number_format($trend['harga'], 0, ',', '.'); ?>
                            </p>
                            <a href="detail_wisata.php?id=<?php echo $trend['wisata_id']; ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Interest-based Section -->
        <?php if (!empty($interest_destinations)): ?>
        <section class="mb-5">
            <h2>Rekomendasi Berdasarkan Minat Anda</h2>
            <div class="row">
                <?php foreach ($interest_destinations as $dest): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($dest['nama']); ?></h5>
                            <p class="card-text">
                                <strong>Lokasi:</strong> <?php echo htmlspecialchars($dest['lokasi']); ?><br>
                                <strong>Harga:</strong> Rp <?php echo number_format($dest['harga'], 0, ',', '.'); ?>
                            </p>
                            <a href="detail_wisata.php?id=<?php echo $dest['wisata_id']; ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- News Section -->
        <section class="mb-5">
            <h2>Berita Terbaru</h2>
            <div class="list-group">
                <?php foreach ($news as $item): ?>
                <a href="detail_berita.php?id=<?php echo $item['berita_id']; ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($item['judul']); ?></h5>
                        <small><?php echo date('d M Y', strtotime($item['tanggal_post'])); ?></small>
                    </div>
                    <p class="mb-1"><?php echo htmlspecialchars(substr($item['konten'], 0, 150)) . '...'; ?></p>
                    <small>Terkait: <?php echo htmlspecialchars($item['wisata_nama']); ?></small>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 