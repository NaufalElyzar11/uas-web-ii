<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?></title>
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin') ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>
            <hr class="sidebar-divider my-0">
            <?php 
                $currentUrl = current_url();
                function is_active($url) {
                    return current_url() === base_url($url) ? 'active' : '';
                }
            ?>
            <li class="nav-item <?= is_active('admin/dashboard') || is_active('admin') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item <?= is_active('admin/users') ?>">
                <a class="nav-link" href="<?= base_url('admin/users') ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>User</span></a>
            </li>
            <li class="nav-item <?= is_active('admin/wisata') ?>">
                <a class="nav-link" href="<?= base_url('admin/wisata') ?>">
                    <i class="fas fa-fw fa-mountain"></i>
                    <span>Wisata</span></a>
            </li>
            <li class="nav-item <?= is_active('admin/review') ?>">
                <a class="nav-link" href="<?= base_url('admin/review') ?>">
                    <i class="fas fa-fw fa-star"></i>
                    <span>Review</span></a>
            </li>
            <li class="nav-item <?= is_active('admin/booking') ?>">
                <a class="nav-link" href="<?= base_url('admin/booking') ?>">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Booking</span></a>
            </li>
            <li class="nav-item <?= is_active('admin/berita') ?>">
                <a class="nav-link" href="<?= base_url('admin/berita') ?>">
                    <i class="fas fa-fw fa-newspaper"></i>
                    <span>Berita</span></a>
            </li>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Panel</span>
                </nav>

                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

    <?= $this->renderSection('script') ?>
    
</body>
</html>