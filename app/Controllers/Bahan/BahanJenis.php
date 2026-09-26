<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\BahanJenisModel;
use App\Models\MesinTipeModel;
use CodeIgniter\Database\BaseBuilder;

class BahanJenis extends BaseController
{
    use CrudTrait;

    protected BahanJenisModel $model;
    protected MesinTipeModel $mesinTipeModel;

    protected $searchable = ['a.kode', 'a.nama', 'b.nama'];
    protected $orderable  = ['a.id', 'a.jenis', 'a.kode', 'b.nama', 'a.nama', 'a.gsm', 'a.rumus'];

    public function __construct()
    {
        $this->model = new BahanJenisModel();
        $this->mesinTipeModel = new MesinTipeModel();
    }

    public function index(): string
    {
        return view('bahan/bahan_jenis', [
            'pageTitle'     => 'Jenis Bahan',
            'navigasi'      => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getDropdown()
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterJenis = $this->request->getPost('filter_jenis');
        if ($filterJenis !== null && $filterJenis !== '') {
            $builder->where('a.jenis', $filterJenis);
        }

        $filterTipe = $this->request->getPost('filter_tipe');
        if (!empty($filterTipe)) {
            $builder->where('a.mesin_tipe_id', $filterTipe);
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
            (int) $row->jenis ? 'Material' : 'Bahan',
            $row->kode,
            $row->tipe_mesin ?: '-',
            $row->nama,
            (int) $row->gsm > 0 ? $row->gsm . ' gsm' : '-',
            (int) $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
            $aksi,
            $row->created_at,
            $row->updated_at
        ];
    }

    protected function dataSimpan(): array
    {
        $jenis = (int) ($this->request->getPost('jenis') ?? 0);

        return [
            'id'            => $this->request->getPost('id'),
            'mesin_tipe_id' => $jenis === 1 ? null : $this->request->getPost('mesin_tipe_id'),
            'jenis'         => $jenis,
            'kode'          => $this->request->getPost('kode'),
            'nama'          => $this->request->getPost('nama'),
            'gsm'           => $this->request->getPost('gsm') ?: 0,
            'rumus'         => $this->request->getPost('rumus') ?: 0,
        ];
    }
}
