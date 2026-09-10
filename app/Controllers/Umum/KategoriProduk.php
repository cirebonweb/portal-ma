<?php

namespace App\Controllers\Umum;

use App\Controllers\BaseController;
use App\Models\Umum\KategoriProdukModel;
use App\Libraries\TabelLibrari;

class KategoriProduk extends BaseController
{
    /**
     * @var KategoriProdukModel
     */
    protected $kategoriProdukModel;

    public function __construct()
    {
        $this->kategoriProdukModel = new KategoriProdukModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Kategori Produk',
            'navigasi'  => '<a href="/umum">Umum</a> &nbsp;',
        ];
        return view('umum/kategori_produk', $data);
    }

    public function tabel()
    {
        $builder = $this->kategoriProdukModel->tabel();

        // Ajax filter divisi
        $filterDivisi = $this->request->getPost('filter_divisi');
        if ($filterDivisi !== null && $filterDivisi !== '') {
            $builder->where('divisi', $filterDivisi);
        }

        // Ajax filter status
        $filterStatus = $this->request->getPost('filter_status');
        if ($filterStatus !== null && $filterStatus !== '') {
            $builder->where('status', $filterStatus);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        $dataTable->setSearchable(['nama']);

        $dataTable->setRowCallback(function ($row) {

            $divisi = match ((int) $row->divisi) {
                0 => 'Umum',
                1 => 'Printing',
                2 => 'Advertising',
                3 => 'Partner',
                default => 'Tidak Diketahui'
            };

            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
            $aksi .= '</div>';

            return [
                $row->id,
                $divisi,
                $row->nama,
                $row->status == 1 ? '<span class="lencana bg-primary">Aktif</span>' : '<span class="lencana bg-merah">Nonaktif</span>',
                $row->created_at,
                $row->updated_at,
                $aksi
            ];
        });

        return $this->response->setJSON($dataTable->getResult());
    }

    public function getId()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid', null, 400);
        }

        $data = $this->kategoriProdukModel->find($id);
        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = [
            'id'     => $this->request->getPost('id'),
            'divisi' => $this->request->getPost('divisi'),
            'nama'   => $this->request->getPost('nama'),
            'status' => $this->request->getPost('status')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
            if (! $this->kategoriProdukModel->save($data)) {
                return $this->json(false, $this->kategoriProdukModel->errors());
            }

            $pesan = empty($data['id']) ? lang("App.insert-success") : lang("App.update-success");

            return $this->json(true, $pesan);
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }

    public function hapus()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');

        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid');
        }

        try {
            if (! $this->kategoriProdukModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->kategoriProdukModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
