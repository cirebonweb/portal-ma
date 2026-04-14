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
        $id = $this->request->getPost('id');

        if ($id && is_numeric($id)) {
            $data = $this->levelHargaModel->find($id);
            if ($data) {
                return $this->response->setJSON(['success' => true, 'data' => $data]);
            }
        }

        return $this->response->setStatusCode(404)->setJSON([
            'success' => false,
            'messages' => 'Data tidak ditemukan'
        ]);
    }

    public function simpan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Akses dilarang']);
        }

        $data = [
            'id'   => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama')
        ];

        try {
            if ($this->levelHargaModel->save($data) === false) {
                return $this->response->setJSON([
                    'success'  => false,
                    'messages' => $this->levelHargaModel->errors()
                ]);
            }

            // Tentukan pesan berdasarkan ada/tidaknya id
            $pesan = empty($data['id']) ? lang("App.insert-success") : lang("App.update-success");

            return $this->response->setJSON([
                'success'  => true,
                'messages' => $pesan
            ]);
        } catch (\Exception $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->response->setJSON([
                'success'  => false,
                'messages' => 'Critical: ' . $e->getMessage()
            ]);
        }
    }

    public function hapus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Akses dilarang']);
        }

        $id = $this->request->getPost('id');

        try {
            if ($this->levelHargaModel->delete($id)) {
                return $this->response->setJSON([
                    'success'  => true,
                    'messages' => lang("App.delete-success")
                ]);
            }

            return $this->response->setJSON([
                'success'  => false,
                'messages' => lang("App.delete-error")
            ]);
        } catch (\Exception $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->response->setJSON([
                'success'  => false,
                'messages' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
