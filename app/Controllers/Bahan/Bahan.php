<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Models\BahanModel;
use App\Models\BahanJenisModel;
use App\Models\MesinTipeModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Bahan extends BaseController
{
    use CrudTrait;

    protected BahanModel $model;
    protected BahanJenisModel $bahanJenisModel;
    protected MesinTipeModel $mesinTipeModel;
    protected $searchable = ['a.kode', 'a.nama'];
    protected $orderable  = ['a.id', 'c.nama', 'a.kode', 'a.nama'];

    public function __construct()
    {
        $this->model = new BahanModel();
        $this->bahanJenisModel = new BahanJenisModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('format');
    }

    public function index(): string
    {
        return view('bahan/bahan', [
            'pageTitle'     => 'Data Bahan',
            'navigasi'      => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getTipeMesin(),
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterTipe = $this->request->getPost('filter_tipe');
        if (!empty($filterTipe)) {
            $builder->where('b.mesin_tipe_id', $filterTipe);
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
            $row->tipe_mesin,
            $row->kode,
            $row->nama_bahan,
            (int) $row->gsm > 0 ? $row->gsm . ' gsm' : '-',
            formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m',
            '1 ' . $row->satuan_2 . ' = ' .  formatDesimal($row->isi_paket) . ' ' . $row->satuan_1,
            (int) $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
            $aksi,
            $row->created_at,
            $row->updated_at
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
        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function getBahanJenis()
    {
        $id = $this->request->getPost('mesinTipeId');
        if (!$id || !is_numeric($id)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->bahanJenisModel->getBahanMesin((int) $id));
    }

    protected function dataSimpan(): array
    {
        return [
            'id'             => $this->request->getPost('id'),
            'bahan_jenis_id' => $this->request->getPost('bahan_jenis_id'),
            'kode'           => $this->request->getPost('kode'),
            'nama'           => $this->request->getPost('nama'),
            'lebar'          => $this->request->getPost('lebar'),
            'panjang'        => $this->request->getPost('panjang'),
            'satuan_1'       => $this->request->getPost('satuan_1'),
            'satuan_2'       => $this->request->getPost('satuan_2'),
            'isi_paket'      => $this->request->getPost('isi_paket')
        ];
    }
}
