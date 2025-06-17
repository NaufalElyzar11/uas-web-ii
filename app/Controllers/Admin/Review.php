<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class Review extends BaseController
{
    protected $reviewModel;
    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Review',
            'reviews' => $this->reviewModel->findAll()
        ];
        return view('admin/review/index', $data);
    }

    public function delete($id)
    {
        $review = $this->reviewModel->find($id);
        if (!$review) {
            return redirect()->to('admin/review')->with('error', 'Review tidak ditemukan');
        }

        $this->reviewModel->delete($id);
        return redirect()->to('admin/review')->with('success', 'Review berhasil dihapus');
    }
} 