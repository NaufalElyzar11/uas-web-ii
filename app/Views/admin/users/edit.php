<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4 text-gray-800">Edit User</h1>
<form action="<?= base_url('admin/users/update/'.$user['user_id']) ?>" method="post">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama', $user['nama']) ?>" required>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" class="form-control" value="<?= old('email', $user['email']) ?>" required>
    </div>
    <div class="form-group">
        <label>Daerah</label>
        <select name="daerah" class="form-control" required>
            <option value="" disabled>Pilih Daerah</option>
            <?php
            $daerahList = [
                'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
                'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
                'Kotabaru', 'Barito Timur', 'Balangan'
            ];
            foreach ($daerahList as $daerah): ?>
                <option value="<?= $daerah ?>" <?= old('daerah', $user['daerah']) === $daerah ? 'selected' : '' ?>><?= $daerah ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="user" <?= old('role', $user['role']) == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>Admin</option> 
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Batal</a>
</form>
<?= $this->endSection() ?> 