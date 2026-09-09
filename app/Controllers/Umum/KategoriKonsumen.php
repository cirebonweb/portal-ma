<?php

namespace App\Controllers\Umum;

use App\Controllers\BaseController;
use App\Models\Umum\KategoriKonsumenModel;
use App\Libraries\TabelLibrari;

class KategoriKonsumen extends BaseController
{
    /**
     * @var KategoriKonsumenModel
     */
    protected $kategoriKonsumenModel;

    public function __construct()
    {
        $this->kategoriKonsumenModel = new KategoriKonsumenModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'  => 'Kategori Konsumen',
            'navigasi'   => '<a href="/umum">Umum</a> &nbsp;'
        ];
        return view('umum/kategori_konsumen', $data);
    }

    public function tabel()
    {
        $builder = $this->kategoriKonsumenModel->tabel();

        // Ajax filter status
        $filterStatus = $this->request->getPost('filter_status');

        if ($filterStatus !== null && $filterStatus !== '') {
            $builder->where('status', $filterStatus);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        $dataTable->setSearchable(['nama']);

        $dataTable->setRowCallback(function ($row) {
            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
            $aksi .= '</div>';

            return [
                $row->id,
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

        $data = $this->kategoriKonsumenModel->find($id);
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
            // save() sudah include validation
            if (! $this->kategoriKonsumenModel->save($data)) {
                return $this->json(false, $this->kategoriKonsumenModel->errors());
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
            if (! $this->kategoriKonsumenModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->kategoriKonsumenModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
