<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\HargaTipeModel;
use App\Models\KonsumenTipeModel;
use App\Models\ProdukModel;
use CodeIgniter\Database\BaseBuilder;

class HargaTipe extends BaseController
{
    use CrudTrait;

    protected HargaTipeModel $model;
    protected KonsumenTipeModel $konsumenTipeModel;
    protected ProdukModel $produkModel;
    protected $searchable = ['b.nama', 'c.nama'];
    protected $orderable = ['a.id', 'b.nama', 'c.nama', 'a.harga', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new HargaTipeModel();
        $this->konsumenTipeModel = new KonsumenTipeModel();
        $this->produkModel = new ProdukModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Harga Tipe',
            'navigasi' => '<a href="/master">Master</a> &nbsp;',
            'menuKonsumenTipe' => $this->konsumenTipeModel->getDropdown(),
            'menuProduk' => $this->produkModel
                ->select('id, nama')
                ->orderBy('nama', 'ASC')
                ->findAll(),
        ];

        return view('master/harga_tipe', $data);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();
        $filterKonsumenTipe = $this->request->getPost('filter_konsumen_tipe');
        $filterProduk = $this->request->getPost('filter_produk');

        if ($filterKonsumenTipe !== null && $filterKonsumenTipe !== '') {
            $builder->where('a.konsumen_tipe_id', $filterKonsumenTipe);
        }

        if ($filterProduk !== null && $filterProduk !== '') {
            $builder->where('a.produk_id', $filterProduk);
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
            $row->tipe_konsumen ?: '-',
            $row->produk ?: '-',
            'Rp ' . number_format((float) $row->harga, 0, ',', '.'),
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id' => $this->request->getPost('id'),
            'konsumen_tipe_id' => $this->request->getPost('konsumen_tipe_id'),
            'produk_id' => $this->request->getPost('produk_id'),
            'harga' => $this->request->getPost('harga'),
        ];
    }
}
