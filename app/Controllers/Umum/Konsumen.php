<?php

namespace App\Controllers\Umum;

use App\Controllers\BaseController;
use App\Models\Umum\KonsumenModel;
use App\Models\Umum\LevelHargaModel;
use App\Libraries\TabelLibrari;

class Konsumen extends BaseController
{
    protected $konsumenModel;
    protected $levelHargaModel;

    public function __construct()
    {
        $this->konsumenModel = new KonsumenModel();
        $this->levelHargaModel = new LevelHargaModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'  => 'Konsumen',
            'navigasi'   => '<a href="/umum">Umum</a> &nbsp;',
            'levelHarga' => $this->levelHargaModel->getDropdown()
        ];
        return view('umum/konsumen', $data);
    }

    public function tabel()
    {
        $builder = $this->konsumenModel->tabel();

        // Ajax filter level harga
        $filterLevel = $this->request->getPost('filter_level');

        if ($filterLevel !== '') {
            if ($filterLevel === 'Retail') {
                $builder->where('level_harga_id IS NULL', null, false);
            } else {
                $builder->where('level_harga_id', $filterLevel);
            }
        }

        // Ajax filter divisi
        $filterDivisi = $this->request->getPost('filter_divisi');

        if ($filterDivisi !== null && $filterDivisi !== '') {
            $builder->where('divisi', $filterDivisi);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        $dataTable->setSearchable(['nama', 'perusahaan']);

        $dataTable->setRowCallback(function ($row) {
            $mapDivisi = [
                0 => 'Umum',
                1 => 'Printing',
                2 => 'Advertising'
            ];

            $divisi = $mapDivisi[(int) $row->divisi] ?? 'Umum';

            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">del</button>';
            $aksi .= '</div>';

            return [
                $row->id,
                $row->nama,
                $row->nama_level ?? 'Retail',
                $divisi,
                $row->perusahaan,
                $row->alamat,
                $row->kota,
                $row->whatsapp,
                $row->telegram,
                $row->email,
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

        $data = $this->konsumenModel->find($id);

        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = [
            'id' => $this->request->getPost('id'),
            'level_harga_id' => $this->request->getPost('level_harga_id'),
            'nama' => $this->request->getPost('nama'),
            'perusahaan' => $this->request->getPost('perusahaan'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'whatsapp' => $this->request->getPost('whatsapp'),
            'telegram' => $this->request->getPost('telegram'),
            'email' => $this->request->getPost('email'),
            'divisi' => $this->request->getPost('divisi')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
            // save() sudah include validation
            if (! $this->konsumenModel->save($data)) {
                return $this->json(false, $this->konsumenModel->errors());
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
            if (! $this->konsumenModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->konsumenModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
