<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Insert admin user
        $this->db->table('users')->insert([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@wisatalokal.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'daerah' => 'Jakarta',
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Insert regular user
        $this->db->table('users')->insert([
            'nama' => 'User Demo',
            'username' => 'user',
            'email' => 'user@wisatalokal.com',
            'password' => password_hash('user123', PASSWORD_DEFAULT),
            'daerah' => 'Bandung',
            'jenis_kelamin' => 'Laki-laki',
            'umur' => 25,
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Insert wisata data
        $wisataData = [
            [
                'nama' => 'Pantai Kuta',
                'daerah' => 'Bali',
                'deskripsi' => 'Pantai Kuta adalah salah satu pantai paling populer di Bali. Terkenal dengan sunsetnya yang indah dan ombaknya yang cocok untuk berselancar bagi pemula.',
                'harga' => 25000,
                'kategori' => 'Pantai',
                'trending_score' => 98,
                'gambar_wisata' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Gunung Bromo',
                'daerah' => 'Jawa Timur',
                'deskripsi' => 'Gunung Bromo adalah salah satu gunung berapi aktif di Indonesia yang terkenal dengan pemandangan matahari terbitnya yang menakjubkan.',
                'harga' => 35000,
                'kategori' => 'Gunung',
                'trending_score' => 95,
                'gambar_wisata' => 'https://images.unsplash.com/photo-1589551545938-ae785f489608',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Taman Mini Indonesia Indah',
                'daerah' => 'Jakarta',
                'deskripsi' => 'Taman Mini Indonesia Indah adalah taman rekreasi yang menampilkan kebudayaan dan arsitektur dari berbagai daerah di Indonesia.',
                'harga' => 20000,
                'kategori' => 'Budaya',
                'trending_score' => 85,
                'gambar_wisata' => 'https://www.tamanmini.com/assets/upload/foto_berita/tmii.jpg',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Danau Toba',
                'daerah' => 'Sumatera Utara',
                'deskripsi' => 'Danau Toba adalah danau vulkanik terbesar di dunia. Di tengah danau terdapat Pulau Samosir yang memiliki pemandian air panas dan desa-desa tradisional.',
                'harga' => 30000,
                'kategori' => 'Danau',
                'trending_score' => 92,
                'gambar_wisata' => 'https://images.unsplash.com/photo-1600094288338-28b99e819dae',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Malioboro',
                'daerah' => 'Yogyakarta',
                'deskripsi' => 'Jalan Malioboro adalah jalan paling terkenal di Yogyakarta, terkenal dengan kuliner dan pusat perbelanjaannya yang menjual berbagai kerajinan tangan.',
                'harga' => 0,
                'kategori' => 'Belanja',
                'trending_score' => 90,
                'gambar_wisata' => 'https://images.unsplash.com/photo-1584810359583-96fc3448beaa',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama' => 'Kawah Putih',
                'daerah' => 'Jawa Barat',
                'deskripsi' => 'Kawah Putih adalah sebuah danau kawah vulkanik yang terletak di Gunung Patuha. Airnya yang berwarna putih kehijauan membuat tempat ini unik dan instagramable.',
                'harga' => 40000,
                'kategori' => 'Gunung',
                'trending_score' => 88,
                'gambar_wisata' => 'https://images.unsplash.com/photo-1576878534030-1a e2594c4a8d',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];
        
        foreach ($wisataData as $data) {
            $this->db->table('wisata')->insert($data);
        }
        
        // Insert berita data
        $beritaData = [
            [
                'judul' => 'Pembukaan Kembali Objek Wisata Pasca Pandemi',
                'isi' => 'Setelah hampir dua tahun ditutup karena pandemi, berbagai objek wisata di Indonesia mulai dibuka kembali dengan protokol kesehatan yang ketat.',
                'gambar' => 'berita1.jpg',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'judul' => 'Festival Budaya Nusantara 2023',
                'isi' => 'Festival Budaya Nusantara 2023 akan diselenggarakan di Jakarta pada bulan Agustus mendatang. Festival ini akan menampilkan berbagai kesenian dan kuliner tradisional Indonesia.',
                'gambar' => 'berita2.jpg',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'judul' => 'Penemuan Situs Sejarah Baru di Sulawesi',
                'isi' => 'Tim arkeolog menemukan situs sejarah baru di Sulawesi yang diperkirakan berusia lebih dari 5.000 tahun. Penemuan ini diharapkan dapat menarik minat wisatawan sejarah.',
                'gambar' => 'berita3.jpg',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];
        
        foreach ($beritaData as $data) {
            $this->db->table('berita')->insert($data);
        }
    }
}
