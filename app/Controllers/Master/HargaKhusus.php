<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\HargaKhususModel;
use App\Models\KonsumenModel;
use App\Models\ProdukModel;
use CodeIgniter\Database\BaseBuilder;

class HargaKhusus extends BaseController
{
    use CrudTrait;

    protected HargaKhususModel $model;
    protected KonsumenModel $konsumenModel;
    protected ProdukModel $produkModel;
    protected $searchable = ['b.nama', 'b.perusahaan', 'c.nama'];
    protected $orderable = ['a.id', 'b.nama', 'b.perusahaan', 'c.nama', 'c.harga', 'a.harga'];

    public function __construct()
    {
        $this->model = new HargaKhususModel();
        $this->konsumenModel = new KonsumenModel();
        $this->produkModel = new ProdukModel();
    }

    public function index(): string
    {
        return view('master/harga_khusus', [
            'pageTitle'    => 'Harga Khusus',
            'navigasi'     => '<a href="/master">Master</a> &nbsp;',
            'menuKonsumen' => $this->konsumenModel->getDropdown(),
            'menuProduk'   => $this->produkModel->getDropdown()
        ]);
    }

    protected function filterTabel(): BaseBuilder
    {
        return $this->model->tabel();
    }

    protected function dataTabel(\stdClass $row): array
    {
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->konsumen,
            $row->produk,
            $row->harga_produk,
            $row->harga,
            $aksi,
            $row->created_at,
            $row->updated_at
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id'          => $this->request->getPost('id'),
            'konsumen_id' => $this->request->getPost('konsumen_id'),
            'produk_id'   => $this->request->getPost('produk_id'),
            'harga'       => $this->request->getPost('harga'),
        ];
    }
}
