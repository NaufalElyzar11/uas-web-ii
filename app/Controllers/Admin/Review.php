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
} 