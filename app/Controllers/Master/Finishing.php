<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\FinishingModel;
use App\Controllers\Traits\CrudTrait;

class Finishing extends BaseController
{
    use CrudTrait;

    protected FinishingModel $model;
    protected $searchable = ['nama'];
    protected $orderable  = ['id', 'nama', 'created_at', 'updated_at'];

    public function __construct()
    {
        $this->model = new FinishingModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Finishing',
            'navigasi'  => '<a href="/master">Master</a> &nbsp;',
        ];
        return view('master/mesin_tipe', $data);
    }

    protected function dataTabel(\stdClass $row): array
    {
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
    }

    protected function dataSimpan(): array
    {
        return [
            'id'   => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama'),
        ];
    }
}
