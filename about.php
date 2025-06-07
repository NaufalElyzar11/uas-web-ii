<?php
require_once 'config/database.php';
require_once 'utils/security.php';

regenerate_session();

// Get some statistics
$stats = [
    'destinations' => $conn->query("SELECT COUNT(*) FROM wisata")->fetchColumn(),
    'users' => $conn->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'reviews' => $conn->query("SELECT COUNT(*) FROM review")->fetchColumn(),
    'bookings' => $conn->query("SELECT COUNT(*) FROM pemesanan")->fetchColumn()
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Wisata Lokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .feature-icon {
            font-size: 2rem;
            height: 4rem;
            width: 4rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .stat-card {
            text-align: center;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
    </style>
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
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Wisata Lokal</h1>
                    <p class="lead">Platform booking wisata lokal terpercaya yang membantu Anda menemukan destinasi wisata menarik di sekitar Anda.</p>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/hero.jpg" alt="Wisata Lokal Hero" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card bg-primary bg-opacity-10">
                    <div class="stat-number text-primary"><?php echo number_format($stats['destinations']); ?></div>
                    <div class="stat-label">Destinasi Wisata</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-success bg-opacity-10">
                    <div class="stat-number text-success"><?php echo number_format($stats['users']); ?></div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-info bg-opacity-10">
                    <div class="stat-number text-info"><?php echo number_format($stats['reviews']); ?></div>
                    <div class="stat-label">Review</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-warning bg-opacity-10">
                    <div class="stat-number text-warning"><?php echo number_format($stats['bookings']); ?></div>
                    <div class="stat-label">Pemesanan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>Pencarian Cerdas</h3>
                    <p>Temukan destinasi wisata berdasarkan lokasi, minat, dan preferensi Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h3>Booking Mudah</h3>
                    <p>Proses pemesanan tiket yang cepat dan aman dengan berbagai metode pembayaran.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-star"></i>
                    </div>
                    <h3>Review & Rating</h3>
                    <p>Baca review dari pengunjung lain dan bagikan pengalaman Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Mengapa Memilih Kami?</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="bi bi-shield-check text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4>Terpercaya</h4>
                        <p>Sistem pembayaran yang aman dan terpercaya dengan berbagai pilihan metode pembayaran.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="bi bi-graph-up text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4>Rekomendasi Pintar</h4>
                        <p>Dapatkan rekomendasi destinasi wisata berdasarkan minat dan preferensi Anda.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="bi bi-headset text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4>Dukungan 24/7</h4>
                        <p>Tim dukungan pelanggan kami siap membantu Anda 24 jam sehari, 7 hari seminggu.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <i class="bi bi-percent text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4>Promo Menarik</h4>
                        <p>Dapatkan berbagai promo dan penawaran khusus untuk setiap pemesanan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="mb-4">Hubungi Kami</h2>
                    <p class="mb-4">Ada pertanyaan atau masukan? Jangan ragu untuk menghubungi kami.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="bi bi-envelope-fill text-primary fs-3"></i>
                                <h5>Email</h5>
                                <p>info@wisatalokal.com</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="bi bi-telephone-fill text-primary fs-3"></i>
                                <h5>Telepon</h5>
                                <p>+62 123 4567 890</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="bi bi-geo-alt-fill text-primary fs-3"></i>
                                <h5>Alamat</h5>
                                <p>Jl. Wisata No. 123, Jakarta</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Wisata Lokal</h5>
                    <p>Platform booking wisata lokal terpercaya yang membantu Anda menemukan destinasi wisata menarik di sekitar Anda.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="dashboard.php" class="text-light">Home</a></li>
                        <li><a href="about.php" class="text-light">Tentang Kami</a></li>
                        <li><a href="#" class="text-light">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-light">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3 fs-4">
                        <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small>&copy; <?php echo date('Y'); ?> Wisata Lokal. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 