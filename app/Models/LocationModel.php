<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'berita_id';
    protected $allowedFields = [
        'judul',
        'konten',
        'wisata_id',
        'link_berita',
        'gambar',
        'tanggal_post'
    ];
}
