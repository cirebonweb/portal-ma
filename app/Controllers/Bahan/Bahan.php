<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Models\BahanModel;
use App\Models\MesinTipeModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Bahan extends BaseController
{
    use CrudTrait;

    protected BahanModel $model;
    protected MesinTipeModel $mesinTipeModel;
    protected $searchable = ['a.nama'];
    protected $orderable  = ['a.id', 'b.nama', 'a.nama', 'a.gsm', 'a.lebar', 'a.panjang', 'a.isi_paket', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new BahanModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('satuan');
        helper('format');
    }

    public function index(): string
    {
        $data = [
            'pageTitle'  => 'Data Bahan',
            'navigasi'   => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuTipe'   => $this->mesinTipeModel->getDropdown(),
            'menuSatuan' => getSatuan()
        ];

        return view('bahan/bahan', $data);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

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
            $row->tipe_mesin,
            $row->kode,
            $row->nama_bahan,
            $row->gsm . ' gsm',
            formatDesimal($row->lebar) . ' m',
            formatDesimal($row->panjang) . ' m',
            '1 ' . $row->satuan_2 . ' = ' .  formatDesimal($row->isi_paket) . ' ' . $row->satuan_1,
            $row->rumus ? 'Perkalian qty' : 'Perkalian luas',
            $row->created_at,
            $row->updated_at,
            $aksi
        ];
    }

    protected function dataSimpan(): array
    {
        return [
            'id'            => $this->request->getPost('id'),
            'mesin_tipe_id' => $this->request->getPost('mesin_tipe_id'),
            'nama'          => $this->request->getPost('nama'),
            'kode'          => $this->request->getPost('kode'),
            'gsm'           => $this->request->getPost('gsm'),
            'lebar'         => $this->request->getPost('lebar'),
            'panjang'       => $this->request->getPost('panjang'),
            'satuan_1'      => $this->request->getPost('satuan_1'),
            'satuan_2'      => $this->request->getPost('satuan_2'),
            'isi_paket'  => $this->request->getPost('isi_paket'),
            'rumus'         => $this->request->getPost('rumus')
        ];
    }
}
