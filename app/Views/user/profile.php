<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4">Profil Pengguna</h1>
    
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
            
            <?php if($user['role'] == 'admin'): ?>
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
                        <form action="#" method="post" class="profile-form">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="daerah">Daerah</label>
                                <select id="daerah" name="daerah" class="form-control">
                                    <option value="Aceh" <?= $user['daerah'] == 'Aceh' ? 'selected' : '' ?>>Aceh</option>
                                    <option value="Sumatera Utara" <?= $user['daerah'] == 'Sumatera Utara' ? 'selected' : '' ?>>Sumatera Utara</option>
                                    <option value="Sumatera Barat" <?= $user['daerah'] == 'Sumatera Barat' ? 'selected' : '' ?>>Sumatera Barat</option>
                                    <option value="Riau" <?= $user['daerah'] == 'Riau' ? 'selected' : '' ?>>Riau</option>
                                    <option value="Kepulauan Riau" <?= $user['daerah'] == 'Kepulauan Riau' ? 'selected' : '' ?>>Kepulauan Riau</option>
                                    <option value="Jambi" <?= $user['daerah'] == 'Jambi' ? 'selected' : '' ?>>Jambi</option>
                                    <option value="Sumatera Selatan" <?= $user['daerah'] == 'Sumatera Selatan' ? 'selected' : '' ?>>Sumatera Selatan</option>
                                    <option value="Bengkulu" <?= $user['daerah'] == 'Bengkulu' ? 'selected' : '' ?>>Bengkulu</option>
                                    <option value="Lampung" <?= $user['daerah'] == 'Lampung' ? 'selected' : '' ?>>Lampung</option>
                                    <option value="Bangka Belitung" <?= $user['daerah'] == 'Bangka Belitung' ? 'selected' : '' ?>>Bangka Belitung</option>
                                    <option value="DKI Jakarta" <?= $user['daerah'] == 'DKI Jakarta' ? 'selected' : '' ?>>DKI Jakarta</option>
                                    <option value="Jawa Barat" <?= $user['daerah'] == 'Jawa Barat' ? 'selected' : '' ?>>Jawa Barat</option>
                                    <option value="Banten" <?= $user['daerah'] == 'Banten' ? 'selected' : '' ?>>Banten</option>
                                    <option value="Jawa Tengah" <?= $user['daerah'] == 'Jawa Tengah' ? 'selected' : '' ?>>Jawa Tengah</option>
                                    <option value="Yogyakarta" <?= $user['daerah'] == 'Yogyakarta' ? 'selected' : '' ?>>Yogyakarta</option>
                                    <option value="Jawa Timur" <?= $user['daerah'] == 'Jawa Timur' ? 'selected' : '' ?>>Jawa Timur</option>
                                    <option value="Bali" <?= $user['daerah'] == 'Bali' ? 'selected' : '' ?>>Bali</option>
                                    <option value="Nusa Tenggara Barat" <?= $user['daerah'] == 'Nusa Tenggara Barat' ? 'selected' : '' ?>>Nusa Tenggara Barat</option>
                                    <option value="Nusa Tenggara Timur" <?= $user['daerah'] == 'Nusa Tenggara Timur' ? 'selected' : '' ?>>Nusa Tenggara Timur</option>
                                    <option value="Kalimantan Barat" <?= $user['daerah'] == 'Kalimantan Barat' ? 'selected' : '' ?>>Kalimantan Barat</option>
                                    <option value="Kalimantan Tengah" <?= $user['daerah'] == 'Kalimantan Tengah' ? 'selected' : '' ?>>Kalimantan Tengah</option>
                                    <option value="Kalimantan Selatan" <?= $user['daerah'] == 'Kalimantan Selatan' ? 'selected' : '' ?>>Kalimantan Selatan</option>
                                    <option value="Kalimantan Timur" <?= $user['daerah'] == 'Kalimantan Timur' ? 'selected' : '' ?>>Kalimantan Timur</option>
                                    <option value="Kalimantan Utara" <?= $user['daerah'] == 'Kalimantan Utara' ? 'selected' : '' ?>>Kalimantan Utara</option>
                                    <option value="Sulawesi Utara" <?= $user['daerah'] == 'Sulawesi Utara' ? 'selected' : '' ?>>Sulawesi Utara</option>
                                    <option value="Sulawesi Tengah" <?= $user['daerah'] == 'Sulawesi Tengah' ? 'selected' : '' ?>>Sulawesi Tengah</option>
                                    <option value="Sulawesi Selatan" <?= $user['daerah'] == 'Sulawesi Selatan' ? 'selected' : '' ?>>Sulawesi Selatan</option>
                                    <option value="Sulawesi Tenggara" <?= $user['daerah'] == 'Sulawesi Tenggara' ? 'selected' : '' ?>>Sulawesi Tenggara</option>
                                    <option value="Gorontalo" <?= $user['daerah'] == 'Gorontalo' ? 'selected' : '' ?>>Gorontalo</option>
                                    <option value="Sulawesi Barat" <?= $user['daerah'] == 'Sulawesi Barat' ? 'selected' : '' ?>>Sulawesi Barat</option>
                                    <option value="Maluku" <?= $user['daerah'] == 'Maluku' ? 'selected' : '' ?>>Maluku</option>
                                    <option value="Maluku Utara" <?= $user['daerah'] == 'Maluku Utara' ? 'selected' : '' ?>>Maluku Utara</option>
                                    <option value="Papua" <?= $user['daerah'] == 'Papua' ? 'selected' : '' ?>>Papua</option>
                                    <option value="Papua Barat" <?= $user['daerah'] == 'Papua Barat' ? 'selected' : '' ?>>Papua Barat</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Fitur preferensi wisata sedang dalam pengembangan. Silakan kunjungi kembali nanti.
                        </div>
                        
                        <form action="#" class="profile-form">
                            <div class="form-group">
                                <label>Kategori Wisata Favorit</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="kategori[]" value="Alam"> Wisata Alam
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="kategori[]" value="Pantai"> Pantai
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="kategori[]" value="Gunung"> Gunung
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="kategori[]" value="Budaya"> Wisata Budaya
                                    </label>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="kategori[]" value="Kota"> Wisata Kota
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="budget">Budget Wisata</label>
                                <select id="budget" name="budget" class="form-control">
                                    <option value="low">Low Budget (< Rp 500.000)</option>
                                    <option value="medium">Medium Budget (Rp 500.000 - Rp 2.000.000)</option>
                                    <option value="high">High Budget (> Rp 2.000.000)</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" disabled>Simpan Preferensi</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <form action="#" class="profile-form">
                            <div class="form-group">
                                <label for="current_password">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Password Baru</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1000px;
    margin: 0 auto;
}
.profile-container {
    display: flex;
    gap: 30px;
}
.profile-sidebar {
    width: 240px;
    text-align: center;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.profile-image {
    width: 120px;
    height: 120px;
    margin: 0 auto 15px;
}
.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.profile-sidebar h3 {
    margin-bottom: 10px;
    color: #2d3a4a;
}
.user-info {
    color: #666;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.admin-badge {
    background-color: #4f8cff;
    color: white;
    border-radius: 20px;
    padding: 5px 15px;
    margin-top: 15px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
}
.profile-main {
    flex: 1;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
}
.nav-link {
    padding: 10px 20px;
    border: none;
    color: #555;
    cursor: pointer;
    position: relative;
}
.nav-link.active {
    color: #0066ff;
    font-weight: 600;
}
.nav-link.active:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: #0066ff;
}
.tab-content {
    padding: 20px 0;
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
}
.tab-pane.show {
    display: block;
}
.profile-form {
    max-width: 600px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}
.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.checkbox-item {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}
.form-actions {
    margin-top: 30px;
}
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}
.btn-primary {
    background-color: #0066ff;
    color: white;
    border: none;
}
.btn-primary:hover {
    background-color: #0055d4;
}
.btn-primary:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
    }
    .profile-sidebar {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple tab functionality
    const tabs = document.querySelectorAll('.nav-link');
    const tabContents = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => {
                c.classList.remove('show');
                c.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding content
            const target = this.getAttribute('data-bs-target').substring(1);
            const content = document.getElementById(target);
            content.classList.add('show');
            content.classList.add('active');
        });
    });
});
</script>
<?= $this->endSection() ?>
