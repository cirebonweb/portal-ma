<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\MesinTipeModel;
use App\Models\BahanModel;
use App\Models\ProdukModel;
use CodeIgniter\Database\BaseBuilder;

class Produk extends BaseController
{
    use CrudTrait;

    protected ProdukModel $model;
    protected MesinTipeModel $mesinTipeModel;
    protected BahanModel $bahanModel;
    protected $searchable = ['a.nama', 'b.nama'];
    protected $orderable = ['a.id', 'a.nama', 'b.nama', 'a.kategori', 'a.hpp', 'a.harga', 'a.promo', 'a.promo_awal', 'a.promo_akhir', 'a.unggulan', 'a.status', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new ProdukModel();
        $this->mesinTipeModel = new MesinTipeModel();
        $this->bahanModel = new BahanModel();
        helper('format');
    }

    public function index(): string
    {
        return view('data/produk', [
            'pageTitle'     => 'Data Produk',
            'navigasi'      => '<a href="/produk">Produk</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getTipeMesin(),
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
            $row->bahan ?: '-',
            $row->nama,
            formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m',
            $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
            $row->hpp,
            $row->harga,
            $row->promo === null ? '0' : $row->promo,
            $row->promo_awal,
            $row->promo_akhir,
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
            'id'          => $this->request->getPost('id'),
            'bahan_id'    => $this->request->getPost('bahan_id') ?: null,
            'kategori'    => $this->request->getPost('kategori'),
            'nama'        => $this->request->getPost('nama'),
            'lebar'       => $this->request->getPost('lebar') ?? 0.00,
            'panjang'     => $this->request->getPost('panjang') ?? 0.00,
            'rumus'       => $this->request->getPost('rumus') ?? 0,
            'hpp'         => $this->request->getPost('hpp'),
            'harga'       => $this->request->getPost('harga'),
            'promo'       => $this->request->getPost('promo'),
            'promo_awal'  => $this->request->getPost('promo_awal'),
            'promo_akhir' => $this->request->getPost('promo_akhir'),
            'unggulan'    => $this->request->getPost('unggulan') ?? 0,
            'status'      => $this->request->getPost('status') ?? 1,
        ];
    }

    public function getId()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid', null, 400);
        }

        $data = $this->model->select('produk.*, bahan.mesin_tipe_id')
            ->join('bahan', 'bahan.id = produk.bahan_id', 'left')
            ->find($id);
        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function getBahanMesin()
    {
        $id = $this->request->getPost('mesin_id');
        return $this->response->setJSON($id ? $this->bahanModel->getBahanMesin($id) : []);
    }
}
