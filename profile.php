<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

$error = '';
$success = '';

// Get user data
$stmt = $conn->prepare("
    SELECT u.*, GROUP_CONCAT(m.nama) as minat_list 
    FROM users u 
    LEFT JOIN user_minat um ON u.user_id = um.user_id 
    LEFT JOIN minat m ON um.minat_id = m.minat_id 
    WHERE u.user_id = ?
    GROUP BY u.user_id
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all minat options
$stmt = $conn->query("SELECT * FROM minat ORDER BY nama");
$minat_options = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's selected minat
$stmt = $conn->prepare("SELECT minat_id FROM user_minat WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$selected_minat = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $nama = sanitize_input($_POST['nama']);
    $email = sanitize_input($_POST['email']);
    $daerah = sanitize_input($_POST['daerah']);
    $jenis_kelamin = sanitize_input($_POST['jenis_kelamin']);
    $umur = (int)sanitize_input($_POST['umur']);
    $minat = isset($_POST['minat']) ? $_POST['minat'] : [];

    try {
        $conn->beginTransaction();

        // Update user data
        $stmt = $conn->prepare("
            UPDATE users 
            SET nama = ?, email = ?, daerah = ?, jenis_kelamin = ?, umur = ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$nama, $email, $daerah, $jenis_kelamin, $umur, $_SESSION['user_id']]);

        // Update minat
        $stmt = $conn->prepare("DELETE FROM user_minat WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        if (!empty($minat)) {
            $stmt = $conn->prepare("INSERT INTO user_minat (user_id, minat_id) VALUES (?, ?)");
            foreach ($minat as $minat_id) {
                $stmt->execute([$_SESSION['user_id'], $minat_id]);
            }
        }

        $conn->commit();
        $success = 'Profil berhasil diperbarui.';

        // Refresh user data
        $stmt = $conn->prepare("
            SELECT u.*, GROUP_CONCAT(m.nama) as minat_list 
            FROM users u 
            LEFT JOIN user_minat um ON u.user_id = um.user_id 
            LEFT JOIN minat m ON um.minat_id = m.minat_id 
            WHERE u.user_id = ?
            GROUP BY u.user_id
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>Profil - Wisata Lokal</title>
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
                        <a class="nav-link active" href="profile.php">Profile</a>
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
                <div class="card">
                    <div class="card-header">
                        <h3>Profil Saya</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                <small class="text-muted">Username tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="daerah" class="form-label">Daerah</label>
                                    <input type="text" class="form-control" id="daerah" name="daerah" value="<?php echo htmlspecialchars($user['daerah']); ?>" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L" <?php echo $user['jenis_kelamin'] === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="P" <?php echo $user['jenis_kelamin'] === 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="umur" class="form-label">Umur</label>
                                    <input type="number" class="form-control" id="umur" name="umur" value="<?php echo $user['umur']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Minat</label>
                                <div class="row">
                                    <?php foreach ($minat_options as $minat): ?>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="minat[]" 
                                                   value="<?php echo $minat['minat_id']; ?>" 
                                                   id="minat_<?php echo $minat['minat_id']; ?>"
                                                   <?php echo in_array($minat['minat_id'], $selected_minat) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="minat_<?php echo $minat['minat_id']; ?>">
                                                <?php echo htmlspecialchars($minat['nama']); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="settings.php" class="btn btn-outline-primary">Pengaturan Akun</a>
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