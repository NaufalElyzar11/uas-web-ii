<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function __construct()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('auth/login'));
            exit();
        }
    }

    public function index()
    {
        // Debug session data
        log_message('debug', 'Session data: ' . json_encode(session()->get()));
        
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'user_id' => session()->get('user_id'),
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'username' => session()->get('username'),
                'role' => session()->get('role')
            ]
        ];
        
        // Debug data yang akan dikirim ke view
        log_message('debug', 'Data being passed to view: ' . json_encode($data));
        
        return view('dashboard/index', $data);
    }
} 