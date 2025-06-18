<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WisataModel;
use App\Models\KategoriModel;

class Wisata extends BaseController
{
    protected $wisataModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Wisata',
            'wisata' => $this->wisataModel->getWisataWithKategori()
        ];
        return view('admin/wisata/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Wisata',
            'kategoriList' => $this->kategoriModel->findAll()
        ];
        return view('admin/wisata/create', $data);
    }
    public function store()
    {
        $daerahList = [
            'Banjarbaru', 'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
            'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
            'Kotabaru', 'Barito Timur', 'Balangan'
        ];
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'daerah' => 'required|in_list[' . implode(',', $daerahList) . ']',
            'deskripsi' => 'required|min_length[10]',
            'harga' => 'required|numeric|greater_than[0]',
            'kategori_id' => 'required|is_not_unique[kategori.kategori_id]',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'gambar.*' => 'max_size[gambar,2048]|mime_in[gambar,image/png,image/jpg,image/jpeg]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = strip_tags($this->request->getPost('nama'));
        $daerah = strip_tags($this->request->getPost('daerah'));
        $deskripsi = strip_tags($this->request->getPost('deskripsi'));
        $harga = (int) $this->request->getPost('harga');
        $kategori_id = (int) $this->request->getPost('kategori_id');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        $id = $this->wisataModel->insert([
            'nama' => $nama,
            'daerah' => $daerah,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'kategori_id' => $kategori_id,
            'latitude' => $latitude,
            'longitude' => $longitude
        ], true);

        $files = $this->request->getFileMultiple('gambar');
        $folder = 'uploads/wisata/gallery/' . $id;
        if (!is_dir(FCPATH . $folder)) {
            mkdir(FCPATH . $folder, 0777, true);
        }

        $i = 1;
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                if ($i > 7) break;
                $namaBaru = 'gallery-' . $i . '.' . $file->getExtension();
                $file->move(FCPATH . $folder, $namaBaru);
                $i++;
            }
        }

        return redirect()->to('admin/wisata')->with('success', 'Wisata berhasil ditambahkan');
    }

    public function edit($id)
    {
        $wisata = $this->wisataModel->find($id);
        if (!$wisata) return redirect()->to('admin/wisata');

        $data = [
            'title' => 'Edit Wisata',
            'wisata' => $wisata,
            'kategoriList' => $this->kategoriModel->findAll()
        ];
        return view('admin/wisata/edit', $data);
    }

    public function update($id)
    {
        $daerahList = [
            'Banjarbaru', 'Banjarmasin', 'Banjar', 'Barito Kuala', 'Tapin', 'Hulu Sungai Selatan',
            'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Tanah Laut', 'Tanah Bumbu',
            'Kotabaru', 'Barito Timur', 'Balangan',
        ];
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'daerah' => 'required|in_list[' . implode(',', $daerahList) . ']',
            'deskripsi' => 'required|min_length[10]',
            'harga' => 'required|numeric|greater_than[0]',
            'kategori_id' => 'required|is_not_unique[kategori.kategori_id]',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'gambar.*' => 'max_size[gambar,2048]|mime_in[gambar,image/png,image/jpg,image/jpeg]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = strip_tags($this->request->getPost('nama'));
        $daerah = strip_tags($this->request->getPost('daerah'));
        $deskripsi = strip_tags($this->request->getPost('deskripsi'));
        $harga = (int) $this->request->getPost('harga');
        $kategori_id = (int) $this->request->getPost('kategori_id');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        $data = [
            'nama' => $nama,
            'daerah' => $daerah,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'kategori_id' => $kategori_id,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        $this->wisataModel->update($id, $data);

        $files = $this->request->getFileMultiple('gambar');
        if ($files) {
            $folder = 'uploads/wisata/gallery/' . $id;
            if (!is_dir(FCPATH . $folder)) {
                mkdir(FCPATH . $folder, 0777, true);
            }

            $i = 1;
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    if ($i > 7) break;
                    $namaBaru = 'gallery-' . $i . '.' . $file->getExtension();
                    $file->move(FCPATH . $folder, $namaBaru);
                    $i++;
                }
            }
        }

        return redirect()->to('admin/wisata')->with('success', 'Wisata berhasil diupdate');
    }

    public function delete($id)
    {
        $this->wisataModel->delete($id);
        return redirect()->to('admin/wisata')->with('success', 'Wisata berhasil dihapus');
    }

    public function deleteImage($wisataId, $filename)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $filePath = FCPATH . 'uploads/wisata/gallery/' . $wisataId . '/' . $filename;
        
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Gambar berhasil dihapus']);
            }
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus gambar']);
    }
}
