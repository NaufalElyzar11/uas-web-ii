<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * This method is called before a controller is executed.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah session 'isLoggedIn' tidak ada atau bernilai false
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, arahkan ke halaman login
            return redirect()->to('/auth/login')->with('error', 'Silahkan login terlebih dahulu untuk mengakses halaman ini.');
        }
    }

    /**
     * This method is called after a controller is executed.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa
    }
}