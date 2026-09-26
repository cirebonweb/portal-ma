<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\KonsumenModel;
use App\Models\KonsumenTipeModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Konsumen extends BaseController
{
    use CrudTrait;

    protected KonsumenModel $model;
    protected KonsumenTipeModel $konsumenTipeModel;
    protected $searchable = ['a.nama', 'a.perusahaan', 'b.nama'];
    protected $orderable  = ['a.id', 'b.nama', 'a.nama', 'a.perusahaan'];

    public function __construct()
    {
        $this->model = new KonsumenModel();
        $this->konsumenTipeModel = new KonsumenTipeModel();
    }

    public function index(): string
    {
        return view('data/konsumen', [
            'pageTitle' => 'Data Konsumen',
            'navigasi'  => '<a href="/data">Data</a> &nbsp;',
            'menuTipe'  => $this->konsumenTipeModel->getDropdown()
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterTipe = $this->request->getPost('filter_tipe');
        if (!empty($filterTipe)) {
            $builder->where('a.konsumen_tipe_id', $filterTipe);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->tipe_konsumen,
            $row->nama_konsumen,
            $row->perusahaan,
            $row->alamat,
            $row->kota,
            $row->whatsapp,
            $row->email,
            $row->created_at,
            $row->updated_at,
            $aksi
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id'               => $this->request->getPost('id'),
            'konsumen_tipe_id' => $this->request->getPost('konsumen_tipe_id') ?? 1,
            // 'user_id'       => auth()->user()?->id,
            'nama'             => $this->request->getPost('nama'),
            'perusahaan'       => $this->request->getPost('perusahaan'),
            'alamat'           => $this->request->getPost('alamat'),
            'kota'             => $this->request->getPost('kota') ?? 'Cirebon',
            'whatsapp'         => $this->request->getPost('whatsapp'),
            'email'            => $this->request->getPost('email')
        ];
    }
}
