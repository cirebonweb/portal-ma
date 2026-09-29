<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\NotaBayarModel;
use CodeIgniter\Database\BaseBuilder;

class NotaBayar extends BaseController
{
    use CrudTrait;

    protected NotaBayarModel $model;

    protected $searchable = ['b.no_nota'];
    protected $orderable  = ['a.id', 'a.metode_bayar', 'a.tgl_bayar', 'a.uang_terima', 'a.uang_bayar', 'a.uang_kembali', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new NotaBayarModel();
        helper('format');
    }

    /**
     * Halaman daftar seluruh pembayaran.
     * Pembayaran per nota berada pada halaman detail nota (/nota/detail/{id}).
     */
    public function index()
    {
        $id = $this->request->getGet('edit');

        if ($id !== null && $id !== '' && is_numeric($id)) {
            return redirect()->to('/nota/detail/' . (int) $id);
        }

        return view('data/nota_bayar', [
            'pageTitle' => 'Data Pembayaran',
            'navTitle'  => 'Pembayaran Nota',
            'navigasi'  => '<a href="/data">Data</a> &nbsp; / &nbsp; <a href="/nota">Nota</a> &nbsp;',
            'id'        => 0,
            'dataNota'  => null,
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $notaId = $this->request->getPost('nota_id');
        if ($notaId !== null && $notaId !== '' && is_numeric($notaId) && (int) $notaId > 0) {
            $builder->where('a.nota_id', (int) $notaId);
        }

        // Tabel sedikit data: urutan ditetapkan server, bukan dari DataTables
        return $builder->orderBy('a.tgl_bayar', 'DESC')->orderBy('a.id', 'DESC');
    }

    protected function dataTabel(\stdClass $row): array
    {
        $metode = ['Tunai', 'Transfer'];

        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $metode[(int) $row->metode_bayar] ?? '-',
            $row->no_nota ?: '-',
            $row->tgl_bayar,
            $row->uang_terima,
            $row->jumlah,
            $row->kembalian,
            $row->user_nama ?: '-',
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    protected function dataSimpan(): array
    {
        $id       = $this->request->getPost('id');

        $data = [
            'id'           => $id,
            'nota_id'      => $this->request->getPost('nota_id'),
            'metode_bayar' => $this->request->getPost('metode_bayar') ?: 0,
            'tgl_bayar'    => $this->request->getPost('tgl_bayar'),
            'uang_terima'  => $this->request->getPost('uang_terima'),
            'uang_bayar'   => $this->request->getPost('uang_bayar'),
            'uang_kembali' => $this->request->getPost('uang_kembali'),
        ];

        // user_id hanya dicatat saat pembayaran pertama dibuat
        if (empty($id)) {
            $data['user_id'] = auth()->user()?->id;
        }

        return $data;
    }
}
