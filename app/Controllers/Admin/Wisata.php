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
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->wisataModel->save([
            'nama' => $this->request->getPost('nama'),
            'daerah' => $this->request->getPost('daerah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'kategori' => $this->request->getPost('kategori'),
            'trending_score' => 0
        ]);
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
        $this->wisataModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'daerah' => $this->request->getPost('daerah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'kategori' => $this->request->getPost('kategori')
        ]);
        return redirect()->to('admin/wisata')->with('success', 'Wisata berhasil diupdate');
    }

    public function delete($id)
    {
        $this->wisataModel->delete($id);
        return redirect()->to('admin/wisata')->with('success', 'Wisata berhasil dihapus');
    }
} 