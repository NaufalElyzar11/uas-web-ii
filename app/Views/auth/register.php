<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<head>
    <meta charset="UTF-8">
    <title>Modern Login Form | CodingStella </title>
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap'>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>

<div class="wrapper">
    <div class="register_box">
        <div class="login-header">
            <span>Registrasi</span>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <form action="<?= base_url('auth/doRegister') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-columns">
                    <div class="form-column">                        
                        <div class="input_box">
                            <input type="text" class="input-field" id="nama" name="nama" value="<?= old('nama') ?>" required>
                            <label for="nama" class="label">Nama Lengkap</label>
                            <i class="bx bx-user icon"></i>
                        </div>

                        <div class="input_box">
                            <input type="text" class="input-field" id="username" name="username" value="<?= old('username') ?>" required>
                            <label for="username" class="label">Nama Pengguna</label>
                            <i class="bx bx-user icon"></i>
                        </div>

                        <div class="input_box">
                            <input type="email" class="input-field" id="email" name="email" value="<?= old('email') ?>" required>
                            <label for="email" class="label">Email</label>
                            <i class="bx bxl-gmail icon"></i>
                        </div>

                        <div class="input_box">
                            <select class="form-select input-field" id="daerah" name="daerah" required>
                                <option value="" selected disabled>Pilih Daerah</option>
                                <?php
                                $daerahList = [
                                    'Banjarbaru', 'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
                                    'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
                                    'Kotabaru', 'Balangan'
                                ];
                                foreach ($daerahList as $daerah): ?>
                                    <option value="<?= $daerah ?>" <?= old('daerah') === $daerah ? 'selected' : '' ?>><?= $daerah ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bx bx-location-plus icon"></i>
                        </div>
                    </div>    
                
                    <div class="form-column">
                        <div class="input_box">
                            <input type="password" class="input-field" id="password" name="password" required>
                            <label for="password" class="label">Kata Sandi</label>
                            <i class="bx bx-lock-alt icon"></i>
                        </div>
                        
                        <div class="input_box">
                            <input type="password" class="input-field" id="confirm_password" name="confirm_password" required>
                            <label for="confirm_password" class="label">Konfirmasi Kata Sandi</label>
                            <i class="bx bx-lock-alt icon"></i>
                        </div>

                        <div class="input_box">
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <i class="bx bx-male-female icon"></i>
                        </div>

                        <div class="input_box">
                            <input type="number" class="input-field" id="umur" name="umur" value="<?= old('umur') ?>" required min="1">
                            <label for="umur" class="label">Umur</label>
                            <i class="bx bx-user icon"></i>
                        </div>
                    </div>    
                </div>

                    <div class="input_box">
                        <button type="submit" class="input-submit">Daftar</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="<?= base_url('auth/login') ?>">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var umurInput = document.getElementById('umur');
    if(umurInput) {
        umurInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^\d]/g, '');
        });
    }
});
</script>

<?= $this->endSection() ?>