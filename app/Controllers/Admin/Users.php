<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];
        return view('admin/users/index', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ]);

        return redirect()->to('admin/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('admin/users');

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $daerahList = [
            'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
            'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
            'Kotabaru', 'Barito Timur', 'Balangan'
        ];
        $roleList = ['user', 'admin'];
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'daerah' => 'required|in_list['.implode(',', $daerahList).']',
            'role' => 'required|in_list['.implode(',', $roleList).']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = strip_tags($this->request->getPost('nama'));
        $username = strip_tags($this->request->getPost('username'));
        $email = strip_tags($this->request->getPost('email'));
        $daerah = strip_tags($this->request->getPost('daerah'));
        $role = strip_tags($this->request->getPost('role'));

        $data = [
            'nama' => $nama,
            'username' => $username,
            'email' => $email,
            'daerah' => $daerah,
            'role' => $role
        ];

        $this->userModel->update($id, $data);

        return redirect()->to('admin/users')->with('success', 'User berhasil diupdate');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('admin/users');

        $this->userModel->delete($id);
        return redirect()->to('admin/users')->with('success', 'User berhasil dihapus');
    }
} 