<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\MesinModel;
use App\Models\MesinTipeModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Mesin extends BaseController
{
    use CrudTrait;

    protected MesinModel $model;
    protected MesinTipeModel $mesinTipeModel;
    protected $searchable = ['a.nama'];
    protected $orderable  = ['a.id', 'b.nama', 'a.nama', 'created_at', 'updated_at'];

    public function __construct()
    {
        $this->model = new MesinModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('format');
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Data Mesin',
            'navigasi'  => '<a href="/data">Data</a> &nbsp;',
            'menuTipe'  => $this->mesinTipeModel->getDropdown()
        ];
        return view('data/mesin', $data);
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
            $row->nama_mesin,
            $row->print_area ? '<span class="lencana bg-success">Ya</span>' : '<span class="lencana bg-secondary">Tidak</span>',
            (formatDesimal($row->min_lebar) ?? 0) . ' m',
            (formatDesimal($row->max_lebar) ?? 0) . ' m',
            (formatDesimal($row->min_panjang) ?? 0) . ' m',
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
            'print_area'    => $this->request->getPost('print_area'),
            'min_lebar'     => $this->request->getPost('min_lebar'),
            'max_lebar'     => $this->request->getPost('max_lebar'),
            'min_panjang'   => $this->request->getPost('min_panjang')
        ];
    }
}
