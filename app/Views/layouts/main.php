<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= base_url('css/nav.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <input type="checkbox" id="check" checked>
    <label for="check" class="toggle-btn">
        <i class="fas fa-chevron-left" id="sidebar-icon"></i>
    </label>
    <div class="sidebar">
        <header>BanuaTour</header>
        <ul>
            <li><a href="<?= base_url('/') ?>"><i class="fas fa-home"></i>Beranda</a></li>
            <li><a href="<?= base_url('destinasi') ?>"><i class="fas fa-map-marked-alt"></i>Destinasi</a></li>
            <?php if (session()->get('isLoggedIn')): ?>
                <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                <li><a href="<?= base_url('riwayat') ?>"><i class="fas fa-history"></i>Riwayat</a></li>
                <li><a href="<?= base_url('wishlist') ?>"><i class="fas fa-heart"></i>Keinginan</a></li>
                <li><a href="<?= base_url('profile') ?>"><i class="fas fa-user"></i>Profil</a></li>
                <li><a href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i>Keluar</a></li>
            <?php else: ?>
                <li><a href="<?= base_url('auth/login') ?>"><i class="fas fa-sign-in-alt"></i>Masuk</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <section class="main-content">
        <?= $this->renderSection('content') ?>
    </section>

    <script>
        const checkbox = document.getElementById('check');
        const icon = document.getElementById('sidebar-icon');

        if (checkbox.checked) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
        }

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        });
    </script>
</body>
</html>