<?php

namespace App\Controllers\Umum;

use App\Controllers\BaseController;
use App\Models\Umum\KonsumenModel;
use App\Models\Umum\KategoriKonsumenModel;
use App\Libraries\TabelLibrari;

class Konsumen extends BaseController
{
    /**
     * @var KonsumenModel
     */
    protected $konsumenModel;

    /**
     * @var KategoriKonsumenModel
     */
    protected $kategoriKonsumenModel;

    public function __construct()
    {
        $this->konsumenModel = new KonsumenModel();
        $this->kategoriKonsumenModel = new KategoriKonsumenModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'  => 'Data Konsumen',
            'navigasi'   => '<a href="/umum">Umum</a> &nbsp;',
            'kategori' => $this->kategoriKonsumenModel->getDropdown()
        ];
        return view('umum/konsumen', $data);
    }

    public function tabel()
    {
        $builder = $this->konsumenModel->tabel();

        // Ajax filter kategori_konsumen_id
        $filterKonsumen = $this->request->getPost('filter_konsumen');
        if ($filterKonsumen !== null && $filterKonsumen !== '') {
            $builder->where('kategori_konsumen_id', $filterKonsumen);
        }

        // Ajax filter divisi
        $filterDivisi = $this->request->getPost('filter_divisi');
        if ($filterDivisi !== null && $filterDivisi !== '') {
            $builder->where('divisi', $filterDivisi);
        }

        // Ajax filter status
        $filterStatus = $this->request->getPost('filter_status');
        if ($filterStatus !== null && $filterStatus !== '') {
            $builder->where('a.status', $filterStatus);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        $dataTable->setSearchable(['a.nama', 'perusahaan', 'kota']);

        $dataTable->setRowCallback(function ($row) {

            $divisi = match ((int) $row->divisi) {
                0 => 'Umum',
                1 => 'Printing',
                2 => 'Advertising',
                default => 'Tidak Diketahui'
            };

            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">del</button>';
            $aksi .= '</div>';

            return [
                $row->id,
                $row->kategori,
                $divisi,
                $row->nama,
                $row->perusahaan,
                $row->kota,
                $row->alamat,
                $row->whatsapp,
                $row->telegram_id,
                $row->email,
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
            'user_id' => auth()->user()?->id,
            'kategori_konsumen_id' => $this->request->getPost('kategori_konsumen_id'),
            'nama' => $this->request->getPost('nama'),
            'perusahaan' => $this->request->getPost('perusahaan'),
            'alamat' => $this->request->getPost('alamat'),
            'kota' => $this->request->getPost('kota'),
            'whatsapp' => $this->request->getPost('whatsapp'),
            'telegram_id' => $this->request->getPost('telegram_id'),
            'email' => $this->request->getPost('email'),
            'divisi' => $this->request->getPost('divisi'),
            'status' => $this->request->getPost('status')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
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
