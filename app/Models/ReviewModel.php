<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'review_id';
    protected $allowedFields = ['user_id', 'wisata_id', 'rating', 'komentar'];
    protected $useTimestamps = true;
    protected $createdField = 'tanggal_review';
    protected $updatedField = '';

    public function getReviewsByWisataId($wisataId)
    {
        return $this->select('review.*, users.nama as nama_user')
                    ->join('users', 'users.user_id = review.user_id')
                    ->where('wisata_id', $wisataId)
                    ->orderBy('tanggal_review', 'DESC')
                    ->findAll();
    }

    public function getAverageRating($wisataId)
    {
        $result = $this->selectAvg('rating')
                       ->where('wisata_id', $wisataId)
                       ->first();
        return $result['rating'] ?? 0;
    }
} 