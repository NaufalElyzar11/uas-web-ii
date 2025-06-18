<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KategoriModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $kategoriModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }

        $this->userModel = new UserModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $userData = $this->userModel->find($userId);

        $userPreferences = $this->userModel->db->table('minat_user')
            ->select('kategori.kategori_id, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = minat_user.kategori_id')
            ->where('minat_user.user_id', $userId)
            ->get()
            ->getResultArray();

        $allCategories = $this->kategoriModel->findAll();

        $data = [
            'title' => 'Profil Pengguna',
            'user' => [
                'user_id' => $userId,
                'nama' => $userData['nama'] ?? session()->get('nama'),
                'email' => $userData['email'] ?? session()->get('email'),
                'username' => $userData['username'] ?? session()->get('username'),
                'role' => $userData['role'] ?? session()->get('role'),
                'daerah' => $userData['daerah'] ?? 'Indonesia'
            ],
            'userPreferences' => array_column($userPreferences, 'kategori_id'),
            'userPreferencesData' => $userPreferences,
            'allCategories' => $allCategories,
            'userData' => $userData
        ];

        return view('user/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        $rules = [
            'nama' => 'required',
            'email' => 'required|valid_email',
            'daerah' => 'required',
            'jenis_kelamin' => 'permit_empty',
            'umur' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data profil tidak valid. Periksa kembali input Anda.');
        }

        $updateData = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'daerah' => $this->request->getPost('daerah'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'umur' => $this->request->getPost('umur')
        ];

        try {
            $this->userModel->update($userId, $updateData);

            $newSessionData = [
                'nama' => $updateData['nama'],
                'email' => $updateData['email'],
                'daerah' => $updateData['daerah']
            ];
            session()->set($newSessionData);

            return redirect()->to('profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            log_message('error', 'Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function updatePreferences()
    {
        $userId = session()->get('user_id');

        $kategori_ids = $this->request->getPost('kategori_ids');

        if (empty($kategori_ids)) {
            $this->userModel->db->table('minat_user')->where('user_id', $userId)->delete();
            return redirect()->to('profile')->with('success', 'Preferensi wisata berhasil diperbarui.');
        }

        $this->userModel->db->table('minat_user')->where('user_id', $userId)->delete();

        foreach ($kategori_ids as $kategori_id) {
            $this->userModel->db->table('minat_user')->insert([
                'user_id' => $userId,
                'kategori_id' => $kategori_id
            ]);
        }

        return redirect()->to('profile')->with('success', 'Preferensi wisata berhasil diperbarui.');
    }

    public function changePassword()
    {
        $userId = session()->get('user_id');

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Password tidak valid. Periksa kembali input Anda.');
        }

        $user = $this->userModel->find($userId);
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak cocok.');
        }

        try {
            $this->userModel->update($userId, [
                'password' => $this->request->getPost('new_password')
            ]);

            return redirect()->to('profile')->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            log_message('error', 'Error changing password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah password.');
        }
    }
}
