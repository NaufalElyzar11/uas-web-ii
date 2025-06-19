<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BeritaModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\LocationModel;
use App\Models\WisataModel;

class Berita extends BaseController
{
    protected $beritaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Berita',
            'berita' => $this->beritaModel->findAll()
        ];
        return view('admin/berita/index', $data);
    }

    public function create()
    {
        $wisataModel = new WisataModel();
        $data = [
            'title' => 'Tambah Berita',
            'wisataList' => $wisataModel->findAll()
        ];
        return view('admin/berita/create', $data);
    }

    public function store()
    {
        $rules = [
            'judul' => 'required|min_length[5]|max_length[255]',
            'konten' => 'required|min_length[10]',
            'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $gambar = $this->request->getFile('gambar');
        $namaGambar = $gambar->getRandomName();
        $gambar->move(FCPATH . 'uploads/berita', $namaGambar);

        $data = [
            'judul' => $this->request->getPost('judul'),
            'konten' => $this->request->getPost('konten'),
            'gambar' => $namaGambar,
            'status' => $this->request->getPost('status') ?? 'published',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->beritaModel->insert($data);
        return redirect()->to('admin/berita')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id)
    {
        $berita = $this->beritaModel->find($id);
        if (!$berita) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }
        $wisataModel = new WisataModel();
        $data = [
            'title' => 'Edit Berita',
            'berita' => $berita,
            'wisataList' => $wisataModel->findAll()
        ];
        return view('admin/berita/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'judul' => 'required|min_length[5]|max_length[255]',
            'konten' => 'required|min_length[10]',
            'gambar' => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul' => $this->request->getPost('judul'),
            'konten' => $this->request->getPost('konten'),
            'status' => $this->request->getPost('status') ?? 'published',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle gambar upload if a new one is provided
        $gambar = $this->request->getFile('gambar');
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $oldBerita = $this->beritaModel->find($id);
            if ($oldBerita && $oldBerita['gambar']) {
                $oldGambarPath = FCPATH . 'uploads/berita/' . $oldBerita['gambar'];
                if (file_exists($oldGambarPath)) {
                    unlink($oldGambarPath);
                }
            }
            
            $namaGambar = $gambar->getRandomName();
            $gambar->move(FCPATH . 'uploads/berita', $namaGambar);
            $data['gambar'] = $namaGambar;
        }

        $this->beritaModel->update($id, $data);
        return redirect()->to('admin/berita')->with('success', 'Berita berhasil diperbarui');
    }

    public function delete($id)
    {
        $berita = $this->beritaModel->find($id);
        if (!$berita) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Delete gambar file if exists
        if ($berita['gambar']) {
            $gambarPath = FCPATH . 'uploads/berita/' . $berita['gambar'];
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

        $this->beritaModel->delete($id);
        return redirect()->to('admin/berita')->with('success', 'Berita berhasil dihapus');
    }

    public function import()
    {
        $rules = [
            'excel_file' => 'uploaded[excel_file]|max_size[excel_file,5120]|ext_in[excel_file,xlsx,xls]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('excel_file');
        $spreadsheet = IOFactory::load($file->getTempName());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $dataToInsert = [];
        foreach (array_slice($rows, 1) as $row) {
            $dataToInsert[] = [
                'judul'        => trim($row[0] ?? ''),
                'konten'       => trim($row[1] ?? ''),
                'wisata_id'    => trim($row[2] ?? null),
                'link_berita'  => trim($row[3] ?? ''),
                'gambar'       => trim($row[4] ?? ''),
                'tanggal_post' => trim($row[5] ?? null),
            ];
        }

        if (empty($dataToInsert)) {
            return redirect()->back()->with('error', 'Tidak ada data valid untuk diimpor.');
        }

        $locationModel = new LocationModel();
        $locationModel->ignore(true)->insertBatch($dataToInsert);
        
        $count = $locationModel->db->affectedRows();
        return redirect()->back()->with('success', "{$count} data berita baru berhasil diimpor.");
    }
} 