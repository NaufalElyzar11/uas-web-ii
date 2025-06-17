<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required',
                'password' => 'required'
            ];

            if ($this->validate($rules)) {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');

                $user = $this->userModel->where('username', $username)->first();

                if ($user && password_verify($password, $user['password'])) {
                    $sessionData = [
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'nama' => $user['nama'],
                        'role' => $user['role'],
                        'isLoggedIn' => true
                    ];

                    session()->set($sessionData);

                    if ($user['role'] === 'admin') {
                        return redirect()->to('/admin/dashboard');
                    }

                    return redirect()->to('/');
                }

                return redirect()->back()->with('error', 'Username atau password salah');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('auth/login');
    }

    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $userModel = new \App\Models\UserModel();
        
        log_message('debug', 'Login attempt with email/username: ' . $email);
        
        $user = $userModel->where('email', $email)
                         ->orWhere('username', $email)
                         ->first();
        
        if ($user) {
            log_message('debug', 'User found: ' . json_encode($user));
            
            if (password_verify($password, $user['password'])) {
                log_message('debug', 'Password verified successfully');
                session()->set([
                    'user_id' => $user['user_id'],
                    'nama' => $user['nama'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ]);
                
                return redirect()->to('/dashboard');
            } else {
                log_message('debug', 'Password verification failed');
            }
        } else {
            log_message('debug', 'No user found with email/username: ' . $email);
        }
        
        session()->setFlashdata('error', 'Email/Username atau password salah');
        return redirect()->back()->withInput();
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/register');
    }

    public function doRegister()
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'daerah' => 'required',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'umur' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new \App\Models\UserModel();
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'daerah' => $this->request->getPost('daerah'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'umur' => $this->request->getPost('umur'),
            'role' => 'user'
        ];
        
        $userModel->insert($data);
        
        session()->setFlashdata('success', 'Registrasi berhasil. Silakan login.');
        return redirect()->to('/auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
} 