<?php
require_once 'config/database.php';
require_once 'utils/security.php';

check_auth();
regenerate_session();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$pemesanan_id = (int)$_GET['id'];

// Get order details
$stmt = $conn->prepare("
    SELECT p.*, w.nama as wisata_nama, w.lokasi 
    FROM pemesanan p 
    JOIN wisata w ON p.wisata_id = w.wisata_id 
    WHERE p.pemesanan_id = ? AND p.user_id = ?
");
$stmt->execute([$pemesanan_id, $_SESSION['user_id']]);
$pemesanan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pemesanan) {
    header('Location: dashboard.php');
    exit();
}

// Available payment methods
$payment_methods = [
    'transfer_bank' => [
        'name' => 'Transfer Bank',
        'banks' => [
            'bca' => 'BCA - 1234567890',
            'mandiri' => 'Mandiri - 0987654321',
            'bni' => 'BNI - 1122334455'
        ]
    ],
    'ewallet' => [
        'name' => 'E-Wallet',
        'providers' => [
            'ovo' => 'OVO',
            'gopay' => 'GoPay',
            'dana' => 'DANA'
        ]
    ],
    'qris' => [
        'name' => 'QRIS',
        'code' => 'QRIS12345'
    ]
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $metode = sanitize_input($_POST['metode']);
    
    try {
        $conn->beginTransaction();

        // Update pemesanan status
        $stmt = $conn->prepare("UPDATE pemesanan SET status = 'dibayar' WHERE pemesanan_id = ?");
        $stmt->execute([$pemesanan_id]);

        // Create payment record
        $stmt = $conn->prepare("
            INSERT INTO pembayaran (pemesanan_id, metode, status) 
            VALUES (?, ?, 'berhasil')
        ");
        $stmt->execute([$pemesanan_id, $metode]);

        $conn->commit();
        $success = 'Pembayaran berhasil! Silakan cek riwayat pemesanan Anda.';
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
    <title>Pembayaran - Wisata Lokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Detail Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                            <div class="text-center">
                                <a href="riwayat.php" class="btn btn-primary">Lihat Riwayat Pemesanan</a>
                            </div>
                        <?php else: ?>

                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h4>Ringkasan Pemesanan</h4>
                            <table class="table">
                                <tr>
                                    <td>Destinasi</td>
                                    <td><?php echo htmlspecialchars($pemesanan['wisata_nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>Lokasi</td>
                                    <td><?php echo htmlspecialchars($pemesanan['lokasi']); ?></td>
                                </tr>
                                <tr>
                                    <td>Jumlah Orang</td>
                                    <td><?php echo $pemesanan['jumlah_orang']; ?> orang</td>
                                </tr>
                                <tr>
                                    <td>Total Pembayaran</td>
                                    <td><strong>Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></strong></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Payment Method Selection -->
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="mb-4">
                                <h4>Pilih Metode Pembayaran</h4>
                                
                                <!-- Bank Transfer -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5><?php echo $payment_methods['transfer_bank']['name']; ?></h5>
                                        <?php foreach ($payment_methods['transfer_bank']['banks'] as $code => $account): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode" 
                                                   value="<?php echo $code; ?>" id="<?php echo $code; ?>" required>
                                            <label class="form-check-label" for="<?php echo $code; ?>">
                                                <?php echo htmlspecialchars($account); ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- E-Wallet -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5><?php echo $payment_methods['ewallet']['name']; ?></h5>
                                        <?php foreach ($payment_methods['ewallet']['providers'] as $code => $name): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode" 
                                                   value="<?php echo $code; ?>" id="<?php echo $code; ?>">
                                            <label class="form-check-label" for="<?php echo $code; ?>">
                                                <?php echo htmlspecialchars($name); ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- QRIS -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5><?php echo $payment_methods['qris']['name']; ?></h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode" 
                                                   value="qris" id="qris">
                                            <label class="form-check-label" for="qris">
                                                Scan QRIS Code: <?php echo htmlspecialchars($payment_methods['qris']['code']); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 