<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\BahanModel;
use App\Models\BahanStokModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\BaseBuilder;

class BahanStok extends BaseController
{
    use CrudTrait;

    protected BahanStokModel $model;
    protected BahanModel $bahanModel;
    protected SupplierModel $supplierModel;

    protected $searchable = ['a.kode_bahan', 'b.nama', 'a.keterangan'];
    protected $orderable = ['a.id', 'a.kode_bahan', 'b.nama', 'a.stok_masuk', 'a.stok_pakai', 'a.stok_sisa', 'a.kondisi', 'a.status', 'a.keterangan', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new BahanStokModel();
        $this->bahanModel = new BahanModel();
        $this->supplierModel = new SupplierModel();
        helper('format');
    }

    public function index(): string
    {
        return view('bahan/bahan_stok', [
            'pageTitle' => 'Stok Bahan',
            'navigasi'  => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuBahan' => $this->bahanModel->getDropdown()
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();
        $filterKondisi = $this->request->getPost('filter_kondisi');
        $filterStatus = $this->request->getPost('filter_status');

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
            '<span class="lencana bg-hijau">Aktif</span>',
            '<span class="lencana bg-secondary">Nonaktif</span>',
            '<span class="lencana bg-danger">Habis</span>'
        ];

        $kondisi = [
            '<span class="lencana bg-hijau">Baik</span>',
            '<span class="lencana bg-danger">Rusak</span>',
            '<span class="lencana bg-danger">Cacat</span>'
        ];

        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->kode_bahan,
            $row->nama_bahan,
            $row->gsm . ' gsm',
            formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m' ?: '-',
            formatDesimal($row->stok_masuk) . ' ' . $row->satuan_1,
            formatDesimal($row->stok_pakai) . ' ' . $row->satuan_1,
            formatDesimal($row->stok_sisa) . ' ' . $row->satuan_1,
            $kondisi[(int) $row->kondisi],
            $status[(int) $row->status],
            $row->nama_supplier ?: '-',
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
        $id = $this->request->getPost('id');

        return [
            'id' => $id,
            'kode_bahan' => $this->request->getPost('kode_bahan'),
            'kondisi'    => $this->request->getPost('kondisi'),
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan')
        ];
    }

    public function hapus()
    {
        return $this->json(false, 'Stok bahan tidak dapat dihapus.');
    }
}
