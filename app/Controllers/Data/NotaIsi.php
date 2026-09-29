<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\NotaIsiModel;
use App\Models\ProdukModel;
use App\Models\BahanModel;
use App\Models\MesinTipeModel;
use CodeIgniter\Database\BaseBuilder;

class NotaIsi extends BaseController
{
    use CrudTrait;

    protected NotaIsiModel $model;
    private int $noUrut = 0;
    protected ProdukModel $produkModel;
    protected BahanModel $bahanModel;
    protected MesinTipeModel $mesinTipeModel;

    public function __construct()
    {
        $this->model = new NotaIsiModel();
        $this->produkModel = new ProdukModel();
        $this->bahanModel = new BahanModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('format');
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $notaId = $this->request->getPost('nota_id') ?: $this->request->getGet('edit');
        $this->noUrut = (int) $this->request->getPost('start');
        return $this->model->tabel()->where('a.nota_id', (int) $notaId);
    }

    protected function dataTabel(\stdClass $row): array
    {
        $status = ['Draft', 'Antrian', 'Pending', 'Proses', 'Selesai', 'Batal'];
        $aksi = '<i class="table-btn bi bi-pencil" data-toggle="tooltip" type="button" title="' .  lang("App.edit")  . '" onclick="simpan(' . $row->id . ')"></i>';
        $aksi .= '<i class="table-btn bi bi-trash" data-toggle="tooltip" type="button" title="' .  lang("App.delete")  . '" onclick="hapus(' . $row->id . ')"></i>';

        return [
            ++$this->noUrut,
            $row->tema,
            (float) $row->luas == 0 ? '-' : formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m',
            $row->qty,
            $row->harga,
            $row->jumlah,
            $row->produk ?: '-',
            $row->finishing ?: '-',
            $row->keterangan ?: '-',
            $status[(int) $row->status] ?? '-',
            $row->created_at,
            $row->updated_at,
            $aksi,
        ];
    }

    public function loadProduk()
    {
        $id = $this->request->getPost('kategori');
        if ($id === null || $id === '' || !is_numeric($id)) return $this->response->setJSON([]);
        return $this->response->setJSON($this->produkModel->loadProduk((int) $id));
    }

    protected function dataSimpan(): array
    {
        return [
            'id'           => $this->request->getPost('id'),
            'nota_id'      => $this->request->getPost('nota_id'),
            'produk_id'    => $this->request->getPost('produk_id'),
            'finishing_id' => $this->request->getPost('finishing_id'),
            'tema'         => $this->request->getPost('tema'),
            'lebar'        => $this->request->getPost('lebar'),
            'panjang'      => $this->request->getPost('panjang'),
            'luas'         => $this->request->getPost('luas'),
            'qty'          => $this->request->getPost('qty'),
            'harga'        => $this->request->getPost('harga'),
            'harga_min'    => $this->request->getPost('harga_min'),
            'jumlah'       => $this->request->getPost('jumlah'),
            'status'       => $this->request->getPost('status') ?? 0,
            'keterangan'   => $this->request->getPost('keterangan')
        ];
    }

    /**
     * Memindahkan item nota dari Draft ke alur produksi.
     * Kategori 0 yang memakai bahan produksi -> 1 (Antrian cetak).
     * Eksternal, jasa, dan internal tanpa bahan produksi -> 3 (Proses).
     */
    public function produksi()
    {
        if ($res = $this->ajax()) return $res;

        $notaId = $this->request->getPost('nota_id');
        if (!$notaId || !is_numeric($notaId)) return $this->json(false, 'ID nota tidak valid', null, 400);

        $db = db_connect();

        try {
            $db->transBegin();

            $db->query(
                'UPDATE nota_isi a
                 JOIN produk p ON p.id = a.produk_id
                 JOIN bahan_jenis bj ON bj.id = p.bahan_jenis_id
                 SET a.status = 1, a.updated_at = CURRENT_TIMESTAMP
                 WHERE a.nota_id = ? AND a.status = 0 AND p.kategori = 0 AND bj.jenis = 0',
                [(int) $notaId]
            );

            $db->query(
                'UPDATE nota_isi a
                 JOIN produk p ON p.id = a.produk_id
                 SET a.status = 3, a.updated_at = CURRENT_TIMESTAMP
                 WHERE a.nota_id = ? AND a.status = 0',
                [(int) $notaId]
            );

            if ($db->transStatus() === false) throw new \RuntimeException('Transaksi database gagal.');

            $db->transCommit();

            return $this->json(true, 'Status produksi berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Gagal memperbarui status produksi. Silakan periksa log aplikasi.');
        }
    }
}
