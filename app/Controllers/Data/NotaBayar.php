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
    protected $orderable  = ['a.id', 'a.tanggal', 'a.metode', 'a.diterima', 'a.jumlah', 'a.kembalian', 'a.created_at', 'a.updated_at'];

    public function __construct()
    {
        $this->model = new NotaBayarModel();
        helper('format');
    }

    /**
     * Halaman daftar seluruh pembayaran.
     * Pembayaran per nota berada pada halaman detail nota (/nota/isi?edit=).
     */
    public function index()
    {
        $id = $this->request->getGet('edit');

        if ($id !== null && $id !== '' && is_numeric($id)) {
            return redirect()->to('/nota/isi?edit=' . (int) $id);
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
        return $builder->orderBy('a.tanggal', 'DESC')->orderBy('a.id', 'DESC');
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
            $row->no_nota ?: '-',
            $row->tanggal,
            $metode[(int) $row->metode] ?? '-',
            $row->diterima,
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
        $diterima = (int) ($this->request->getPost('diterima') ?: 0);
        $jumlah   = (int) ($this->request->getPost('jumlah') ?: 0);

        $data = [
            'id'        => $id,
            'nota_id'   => $this->request->getPost('nota_id'),
            'tanggal'   => $this->request->getPost('tanggal'),
            'metode'    => $this->request->getPost('metode') ?: 0,
            'diterima'  => $diterima,
            'jumlah'    => $jumlah,
            'kembalian' => max(0, $diterima - $jumlah),
        ];

        // user_id hanya dicatat saat pembayaran pertama dibuat
        if (empty($id)) {
            $data['user_id'] = auth()->user()?->id;
        }

        return $data;
    }
}
