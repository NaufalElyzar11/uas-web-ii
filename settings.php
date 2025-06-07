<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

$error = '';
$success = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($current_password, $user['password'])) {
        $error = 'Password saat ini tidak sesuai.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password baru minimal 6 karakter.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Konfirmasi password tidak sesuai.';
    } else {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashed_password, $_SESSION['user_id']]);
            $success = 'Password berhasil diubah.';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Handle notification settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notifications'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $promo_notifications = isset($_POST['promo_notifications']) ? 1 : 0;

    try {
        $stmt = $conn->prepare("
            UPDATE users 
            SET email_notifications = ?, promo_notifications = ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$email_notifications, $promo_notifications, $_SESSION['user_id']]);
        $success = 'Pengaturan notifikasi berhasil diperbarui.';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get current notification settings
$stmt = $conn->prepare("
    SELECT email_notifications, promo_notifications 
    FROM users 
    WHERE user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Wisata Lokal</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Change Password -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Ubah Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="change_password" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="card">
                    <div class="card-header">
                        <h4>Pengaturan Notifikasi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" 
                                           name="email_notifications" <?php echo $settings['email_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_notifications">
                                        Terima notifikasi melalui email
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="promo_notifications" 
                                           name="promo_notifications" <?php echo $settings['promo_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="promo_notifications">
                                        Terima informasi promo dan penawaran khusus
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="update_notifications" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 