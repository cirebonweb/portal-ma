<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\MesinTipeModel;
use App\Models\ProdukModel;
use CodeIgniter\Database\BaseBuilder;

class Produk extends BaseController
{
    use CrudTrait;

    protected ProdukModel $model;
    protected MesinTipeModel $mesinTipeModel;
    protected $searchable = ['a.nama', 'b.nama'];
    protected $orderable = ['a.id', 'a.nama', 'b.nama', 'a.kategori', 'a.hpp', 'a.harga', 'a.promo', 'a.promo_awal', 'a.promo_akhir', 'a.unggulan', 'a.status', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new ProdukModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('format');
    }

    public function index(): string
    {
        return view('data/produk', [
            'pageTitle'     => 'Data Produk',
            'navigasi'      => '<a href="/produk">Produk</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getDropdown(),
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();
        $filterKategori = $this->request->getPost('filter_kategori');
        $filterStatus = $this->request->getPost('filter_status');

        if ($filterKategori !== null && $filterKategori !== '') {
            $builder->where('a.kategori', $filterKategori);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $builder->where('a.status', $filterStatus);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $kategori = ['Internal', 'Eksternal', 'Jasa/Layanan'];
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $kategori[(int) $row->kategori] ?? '-',
            $row->mesin ?: '-',
            $row->nama,
            formatRupiah($row->hpp),
            formatRupiah($row->harga),
            $row->promo === null ? '-' : formatRupiah($row->promo),
            $row->promo_awal ?: '-',
            $row->promo_akhir ?: '-',
            (int) $row->unggulan === 1 ? '<span class="lencana bg-success">Ya</span>' : '<span class="lencana bg-secondary">Tidak</span>',
            (int) $row->status === 1 ? '<span class="lencana bg-success">Aktif</span>' : '<span class="lencana bg-secondary">Nonaktif</span>',
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id'            => $this->request->getPost('id'),
            'mesin_tipe_id' => $this->request->getPost('mesin_tipe_id'),
            'kategori'      => $this->request->getPost('kategori'),
            'nama'          => $this->request->getPost('nama'),
            'hpp'           => $this->request->getPost('hpp'),
            'harga'         => $this->request->getPost('harga'),
            'promo'         => $this->request->getPost('promo'),
            'promo_awal'    => $this->request->getPost('promo_awal'),
            'promo_akhir'   => $this->request->getPost('promo_akhir'),
            'unggulan'      => $this->request->getPost('unggulan') ?? 0,
            'status'        => $this->request->getPost('status') ?? 1,
        ];
    }
}
