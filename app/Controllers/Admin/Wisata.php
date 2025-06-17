<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WisataModel;

class Wisata extends BaseController
{
    protected $wisataModel;

    public function __construct()
    {
        $this->wisataModel = new WisataModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Wisata',
            'wisata' => $this->wisataModel->findAll()
        ];
        return view('admin/wisata/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Wisata'
        ];
        return view('admin/wisata/create', $data);
    }
    public function store()
    {
        $rules = [
            'nama' => 'required',
            'daerah' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|mime_in[gambar,image/png,image/jpg,image/jpeg]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->wisataModel->insert([
            'nama' => $this->request->getPost('nama'),
            'daerah' => $this->request->getPost('daerah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'kategori' => $this->request->getPost('kategori'),
            'trending_score' => 0
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
            'wisata' => $wisata
        ];
        return view('admin/wisata/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required',
            'daerah' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'daerah' => $this->request->getPost('daerah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'kategori' => $this->request->getPost('kategori'),
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
