<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">

    <header>
        <h1>Profil Pengguna</h1>
    </header>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-image">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['nama']) ?>&background=random" alt="<?= esc($user['nama']) ?>">
            </div>
            <h3><?= esc($user['nama']) ?></h3>
            <p class="user-info"><i class="fas fa-envelope"></i> <?= esc($user['email']) ?></p>
            <p class="user-info"><i class="fas fa-map-marker-alt"></i> <?= esc($user['daerah']) ?></p>

            <?php if ($user['role'] == 'admin'): ?>
                <div class="admin-badge">
                    <i class="fas fa-shield-alt"></i> Admin
                </div>
            <?php endif; ?>
        </div>

        <div class="profile-main">
            <div class="profile-tabs">
                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">Akun</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">Preferensi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Keamanan</button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabContent">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <form action="/profile/update" method="POST" class="profile-form">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="username">Nama Pengguna</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="daerah">Daerah</label>
                                <select id="daerah" name="daerah" class="form-control">
                                    <option value="Banjarmasin" <?= $user['daerah'] == 'Banjarmasin' ? 'selected' : '' ?>>Banjarmasin</option>
                                    <option value="Banjar" <?= $user['daerah'] == 'Banjar' ? 'selected' : '' ?>>Banjar</option>
                                    <option value="Barito Kuala" <?= $user['daerah'] == 'Barito Kuala' ? 'selected' : '' ?>>Barito Kuala</option>
                                    <option value="Tapin" <?= $user['daerah'] == 'Tapin' ? 'selected' : '' ?>>Tapin</option>
                                    <option value="Hulu Sungai Selatan" <?= $user['daerah'] == 'Hulu Sungai Selatan' ? 'selected' : '' ?>>Hulu Sungai Selatan</option>
                                    <option value="Hulu Sungai Tengah" <?= $user['daerah'] == 'Hulu Sungai Tengah' ? 'selected' : '' ?>>Hulu Sungai Tengah</option>
                                    <option value="Hulu Sungai Utara" <?= $user['daerah'] == 'Hulu Sungai Utara' ? 'selected' : '' ?>>Hulu Sungai Utara</option>
                                    <option value="Tanah Laut" <?= $user['daerah'] == 'Tanah Laut' ? 'selected' : '' ?>>Tanah Laut</option>
                                    <option value="Tanah Bumbu" <?= $user['daerah'] == 'Tanah Bumbu' ? 'selected' : '' ?>>Tanah Bumbu</option>
                                    <option value="Kotabaru" <?= $user['daerah'] == 'Kotabaru' ? 'selected' : '' ?>>Kotabaru</option>
                                    <option value="Barito Timur" <?= $user['daerah'] == 'Barito Timur' ? 'selected' : '' ?>>Barito Timur</option>
                                    <option value="Balangan" <?= $user['daerah'] == 'Balangan' ? 'selected' : '' ?>>Balangan</option>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                        <form action="/profile/updatePreferences" method="POST" class="profile-form">
                            <div class="form-group">
                                <label>Kategori Destinasi Wisata Favorit</label>
                                <div class="checkbox-group">
                                    <?php foreach ($allCategories as $category) : ?>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="kategori_ids[]" value="<?= $category['kategori_id'] ?>" <?= in_array($category['kategori_id'], $userPreferences) ? 'checked' : '' ?>>
                                            <?= esc($category['nama_kategori']) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Simpan Preferensi</button>
                            </div>
                        </form>
                    </div>

                    
                    </form>
                </div>

                <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                    <form action="profile/change-password" method="POST" class="profile-form">
                        <div class="form-group">
                            <label for="current_password">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Kata Sandi Baru</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Ubah Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-link');
        const tabContents = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => {
                    c.classList.remove('show');
                    c.classList.remove('active');
                });

                this.classList.add('active');

                const target = this.getAttribute('data-bs-target').substring(1);
                const content = document.getElementById(target);
                content.classList.add('show');
                content.classList.add('active');
            });
        });
    });
</script>
<?= $this->endSection() ?>F