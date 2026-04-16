<?php

namespace App\Controllers\Umum;

use App\Controllers\BaseController;
use App\Models\Umum\LevelHargaModel;
use App\Libraries\TabelLibrari;

class LevelHarga extends BaseController
{
    protected $levelHargaModel;

    public function __construct()
    {
        $this->levelHargaModel = new LevelHargaModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Level Harga',
            'navigasi'  => '<a href="/umum">Umum</a> &nbsp;'
        ];
        return view('umum/level_harga', $data);
    }

    public function tabel()
    {
        $builder = $this->levelHargaModel->tabel();

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

        $data = $this->levelHargaModel->find($id);

        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = [
            'id'   => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
            // save() sudah include validation
            if (! $this->levelHargaModel->save($data)) {
                return $this->json(false, $this->levelHargaModel->errors());
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
            if (! $this->levelHargaModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->levelHargaModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
