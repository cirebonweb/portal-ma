<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\SupplierModel;
use App\Controllers\Traits\CrudTrait;

class Supplier extends BaseController
{
    use CrudTrait;

    protected SupplierModel $model;
    protected $searchable = ['nama', 'perusahaan'];
    protected $orderable  = ['id', 'nama', 'perusahaan', 'alamat', 'kota'];

    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Data Supplier',
            'navigasi'  => '<a href="/data">Data</a> &nbsp;'
        ];
        return view('data/supplier', $data);
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
            $row->perusahaan,
            $row->alamat,
            $row->kota,
            $row->kontak,
            $row->email,
            $row->created_at,
            $row->updated_at,
            $aksi
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id'         => $this->request->getPost('id'),
            'nama'       => $this->request->getPost('nama'),
            'perusahaan' => $this->request->getPost('perusahaan'),
            'alamat'     => $this->request->getPost('alamat'),
            'kota'       => $this->request->getPost('kota'),
            'kontak'     => $this->request->getPost('kontak'),
            'email'      => $this->request->getPost('email')
        ];
    }
}
