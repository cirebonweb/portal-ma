<?php

namespace App\Controllers\Printing;

use App\Controllers\BaseController;
use App\Models\Umum\LevelHargaModel;
use App\Models\Printing\DpHargaLevelModel;
use App\Models\Printing\DpProdukModel;
use App\Libraries\TabelLibrari;

class DpHargaLevel extends BaseController
{
    /**
     * @var LevelHargaModel
     */
    protected $levelHargaModel;

    /**
     * @var DpHargaLevelModel
     */
    protected $dpHargaLevelModel;

    /**
     * @var DpProdukModel
     */
    protected $dpProdukModel;

    public function __construct()
    {
        $this->levelHargaModel = new LevelHargaModel();
        $this->dpHargaLevelModel = new DpHargaLevelModel();
        $this->dpProdukModel = new DpProdukModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle'  => 'Harga Level',
            'navigasi'   => '<a href="/printing">Harga Level</a> &nbsp;',
            'menuLevel'  => $this->levelHargaModel->getDropdown(),
            'menuProduk' => $this->dpProdukModel->getDropdown()
        ];
        return view('printing/dp_harga_level', $data);
    }

    public function tabel()
    {
        $builder = $this->dpHargaLevelModel->tabel();

        // Ajax filter level_harga_id
        $filterMesin = $this->request->getPost('filter_level');

        if ($filterMesin !== null && $filterMesin !== '') {
            $builder->where('level_harga_id', $filterMesin);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        // $dataTable->setSearchable(['nama']);

        $dataTable->setRowCallback(function ($row) {
            // aksi tombol
            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
            $aksi .= '</div>';

            return [
                $row->id,
                $row->level,
                $row->produk,
                $row->harga_produk,
                $row->harga_level,
                $row->created_at,
                $row->updated_at,
                $aksi,
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

        $data = $this->dpHargaLevelModel->find($id);

        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = [
            'id'          => $this->request->getPost('id'),
            'level_harga_id' => $this->request->getPost('level_harga_id'),
            'dp_produk_id' => $this->request->getPost('dp_produk_id'),
            'harga'       => $this->request->getPost('harga')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
            // save() sudah include validation
            if (! $this->dpHargaLevelModel->save($data)) {
                return $this->json(false, $this->dpHargaLevelModel->errors());
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
            if (! $this->dpHargaLevelModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->dpHargaLevelModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
