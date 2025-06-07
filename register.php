<?php
require_once 'config/database.php';
require_once 'utils/security.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

// Fetch available minat options
$stmt = $conn->query("SELECT * FROM minat");
$minat_options = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $nama = sanitize_input($_POST['nama']);
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $daerah = sanitize_input($_POST['daerah']);
    $jenis_kelamin = sanitize_input($_POST['jenis_kelamin']);
    $umur = (int)sanitize_input($_POST['umur']);
    $minat = isset($_POST['minat']) ? $_POST['minat'] : [];

    // Validate input
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        try {
            $conn->beginTransaction();

            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Username or email already exists');
            }

            // Insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nama, username, email, password, daerah, jenis_kelamin, umur) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $username, $email, $hashed_password, $daerah, $jenis_kelamin, $umur]);
            $user_id = $conn->lastInsertId();

            // Insert user minat
            if (!empty($minat)) {
                $stmt = $conn->prepare("INSERT INTO user_minat (user_id, minat_id) VALUES (?, ?)");
                foreach ($minat as $minat_id) {
                    $stmt->execute([$user_id, $minat_id]);
                }
            }

            $conn->commit();
            $success = 'Registration successful! You can now login.';
        } catch (Exception $e) {
            $conn->rollBack();
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Wisata Lokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Register</h3>
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
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="daerah" class="form-label">Daerah</label>
                                    <input type="text" class="form-control" id="daerah" name="daerah" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="umur" class="form-label">Umur</label>
                                    <input type="number" class="form-control" id="umur" name="umur" required>
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
                                                   id="minat_<?php echo $minat['minat_id']; ?>">
                                            <label class="form-check-label" for="minat_<?php echo $minat['minat_id']; ?>">
                                                <?php echo htmlspecialchars($minat['nama']); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 