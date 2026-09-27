<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\MesinTipeModel;
use App\Models\BahanJenisModel;
use App\Models\ProdukModel;
use CodeIgniter\Database\BaseBuilder;

class Produk extends BaseController
{
    use CrudTrait;

    protected ProdukModel $model;
    protected MesinTipeModel $mesinTipeModel;
    protected BahanJenisModel $bahanJenisModel;
    protected $searchable = ['a.nama', 'b.nama'];
    protected $orderable = ['a.id', 'a.nama', 'b.nama', 'a.kategori', 'a.hpp', 'a.harga', 'a.promo', 'a.promo_awal', 'a.promo_akhir', 'a.unggulan', 'a.status', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new ProdukModel();
        $this->mesinTipeModel = new MesinTipeModel();
        $this->bahanJenisModel = new BahanJenisModel();
        // helper('format');
    }

    public function index(): string
    {
        return view('data/produk', [
            'pageTitle'     => 'Data Produk',
            'navigasi'      => '<a href="/produk">Produk</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getTipeMesin(),
            'menuMaterial'  => $this->bahanJenisModel->getMaterial()
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
            // $row->bahan ?: '-',
            // $row->material ?: '-',
            $row->nama,
            // formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m',
            $row->hpp,
            $row->harga,
            $row->promo === null ? '0' : $row->promo,
            $row->promo_awal,
            $row->promo_akhir,
            $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
            (int) $row->unggulan === 1 ? '<span class="lencana bg-success">Ya</span>' : '<span class="lencana bg-secondary">Tidak</span>',
            (int) $row->status === 1 ? '<span class="lencana bg-success">Aktif</span>' : '<span class="lencana bg-secondary">Nonaktif</span>',
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    protected function dataSimpan(): array
    {
        // $bahanJenisId = $this->request->getPost('bahan_jenis_id') ?: null;
        // $rumus = $this->request->getPost('rumus') ?? 0;

        // if ($bahanJenisId !== null && is_numeric($bahanJenisId)) {
        //     $bahanJenis = $this->bahanJenisModel->find((int) $bahanJenisId);
        //     if ($bahanJenis) {
        //         $rumus = $bahanJenis->rumus;
        //     }
        // }

        return [
            'id'                => $this->request->getPost('id'),
            // 'bahan_jenis_id'    => $bahanJenisId,
            'bahan_jenis_id'    => $this->request->getPost('bahan_jenis_id') ?: null,
            'material_jenis_id' => $this->request->getPost('material_jenis_id') ?: null,
            'kategori'          => $this->request->getPost('kategori'),
            'nama'              => $this->request->getPost('nama'),
            'lebar'             => $this->request->getPost('lebar') ?? 0.00,
            'panjang'           => $this->request->getPost('panjang') ?? 0.00,
            // 'rumus'             => $rumus,
            'rumus'             => $this->request->getPost('rumus'),
            'hpp'               => $this->request->getPost('hpp'),
            'harga'             => $this->request->getPost('harga'),
            'promo'             => $this->request->getPost('promo'),
            'promo_awal'        => $this->request->getPost('promo_awal'),
            'promo_akhir'       => $this->request->getPost('promo_akhir'),
            'unggulan'          => $this->request->getPost('unggulan') ?? 0,
            'status'            => $this->request->getPost('status') ?? 1,
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

        $data = $this->model->getId($id);
        // $data = $this->model->select('produk.*, bahan_jenis.mesin_tipe_id')
        //     ->join('bahan_jenis', 'bahan_jenis.id = produk.bahan_jenis_id', 'left')
        //     ->find($id);
        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function getBahanJenis()
    {
        $id = $this->request->getPost('mesin_id');
        if (!$id || !is_numeric($id)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->bahanJenisModel->getBahanMesin((int) $id));
    }
}
