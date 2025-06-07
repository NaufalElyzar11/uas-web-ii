<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Lokal - <?= $title ?? 'Dashboard' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 0.5rem 1rem;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <div class="p-3 text-white">
                        <h5>Wisata Lokal</h5>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link <?= $title == 'Dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a class="nav-link <?= $title == 'Destinasi' ? 'active' : '' ?>" href="<?= base_url('destinasi') ?>">
                            <i class="fas fa-map-marker-alt me-2"></i> Destinasi
                        </a>
                        <a class="nav-link <?= $title == 'Wishlist' ? 'active' : '' ?>" href="<?= base_url('wishlist') ?>">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </a>
                        <a class="nav-link <?= $title == 'Riwayat' ? 'active' : '' ?>" href="<?= base_url('riwayat') ?>">
                            <i class="fas fa-history me-2"></i> Riwayat
                        </a>
                        <a class="nav-link <?= $title == 'Profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand"><?= $title ?? 'Dashboard' ?></span>
                        <div class="navbar-text">
                            Welcome, <?= isset($user['nama']) ? esc($user['nama']) : 'User' ?>
                            <?php if(isset($user['role']) && $user['role'] == 'admin'): ?>
                                <span class="badge bg-primary ms-1">Admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <!-- Content Section -->
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 