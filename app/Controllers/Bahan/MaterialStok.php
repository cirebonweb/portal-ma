<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\MaterialStokModel;
use CodeIgniter\Database\BaseBuilder;

class MaterialStok extends BaseController
{
    use CrudTrait;

    protected MaterialStokModel $model;

    protected $searchable = ['b.kode', 'b.nama', 'a.keterangan'];
    protected $orderable  = ['a.id', 'b.nama', 'a.stok_masuk', 'a.stok_pakai', 'a.stok_sisa', 'a.harga_satuan', 'a.kondisi', 'a.status', 'a.keterangan', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new MaterialStokModel();
        helper('format');
    }

    public function index(): string
    {
        return view('bahan/material_stok', [
            'pageTitle' => 'Stok Material',
            'navigasi'  => '<a href="/bahan">Bahan</a> &nbsp;',
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterKondisi = $this->request->getPost('filter_kondisi');
        $filterStatus  = $this->request->getPost('filter_status');

        if ($filterKondisi !== null && $filterKondisi !== '') {
            $builder->where('a.kondisi', $filterKondisi);
        }

        if ($filterStatus !== null && $filterStatus !== '') {
            $builder->where('a.status', $filterStatus);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $status = [
            '<span class="lencana bg-success">Aktif</span>',
            '<span class="lencana bg-secondary">Nonaktif</span>',
            '<span class="lencana bg-danger">Habis</span>'
        ];

        $kondisi = [
            '<span class="lencana bg-success">Baik</span>',
            '<span class="lencana bg-danger">Rusak</span>',
            '<span class="lencana bg-danger">Cacat</span>'
        ];

        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->kode,
            $row->nama,
            formatDesimal($row->stok_masuk) . ' pcs',
            formatDesimal($row->stok_pakai) . ' pcs',
            formatDesimal($row->stok_sisa) . ' pcs',
            'Rp ' . number_format((int) $row->harga_satuan, 0, ',', '.'),
            $kondisi[(int) $row->kondisi] ?? '-',
            $status[(int) $row->status] ?? '-',
            $row->keterangan ?: '-',
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    public function getId()
    {
        if ($res = $this->ajax()) return $res;

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

    protected function dataSimpan(): array
    {
        return [
            'id'         => $this->request->getPost('id'),
            'kondisi'    => $this->request->getPost('kondisi'),
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
    }

    public function hapus()
    {
        return $this->json(false, 'Stok material tidak dapat dihapus.');
    }
}
