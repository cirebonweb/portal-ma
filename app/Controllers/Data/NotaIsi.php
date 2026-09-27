<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\NotaIsiModel;
use App\Models\NotaModel;
use App\Models\ProdukModel;
use App\Models\BahanModel;
use App\Models\MesinTipeModel;
use App\Models\FinishingModel;
use CodeIgniter\Database\BaseBuilder;

class NotaIsi extends BaseController
{
    use CrudTrait;

    protected NotaIsiModel $model;
    private int $noUrut = 0;
    protected NotaModel $notaModel;
    protected ProdukModel $produkModel;
    protected BahanModel $bahanModel;
    protected MesinTipeModel $mesinTipeModel;
    protected FinishingModel $finishingModel;

    public function __construct()
    {
        $this->model = new NotaIsiModel();
        $this->notaModel = new NotaModel();
        $this->produkModel = new ProdukModel();
        $this->bahanModel = new BahanModel();
        $this->mesinTipeModel = new MesinTipeModel();
        $this->finishingModel = new FinishingModel();
    }

    public function index()
    {
        $id = $this->request->getGet('edit');
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/nota');
        }

        $dataNota = $this->notaModel->tabel()
            ->where('a.id', (int) $id)
            ->get()
            ->getRow();

        if (!$dataNota) {
            return redirect()->to('/nota');
        }

        return view('data/nota_isi', [
            'pageTitle'     => 'Edit Detail Nota',
            'navTitle'      => 'Detail Nota',
            'navigasi'      => '<a href="/data">Data</a> &nbsp; / &nbsp; <a href="/nota">Nota</a> &nbsp;',
            'id'            => (int) $id,
            'dataNota'      => $dataNota,
            'menuFinishing' => $this->finishingModel->getDropdown(),
            'jumlahDraft'   => (int) $this->model->where('nota_id', (int) $id)->where('status', 0)->countAllResults(),
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $notaId = $this->request->getPost('nota_id') ?: $this->request->getGet('edit');

        // Nomor urut mengikuti posisi baris pada halaman DataTables
        $this->noUrut = (int) $this->request->getPost('start');

        return $this->model->tabel()
            ->where('a.nota_id', (int) $notaId);
    }

    protected function dataTabel(\stdClass $row): array
    {
        $status = ['Draft', 'Antrian', 'Pending', 'Proses', 'Selesai', 'Batal'];
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            ++$this->noUrut,
            $row->tema,
            $row->lebar . ' x ' . $row->panjang . ' m',
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

    // public function getId()
    // {
    //     if ($res = $this->ajax()) {
    //         return $res;
    //     }

    //     $id = $this->request->getPost('id');
    //     if (!$id || !is_numeric($id)) {
    //         return $this->json(false, 'ID tidak valid', null, 400);
    //     }

    //     $data = $this->model->find($id);
    //     if (!$data) {
    //         return $this->json(false, 'Data tidak ditemukan', null, 404);
    //     }

    //     return $this->json(true, null, $data);
    // }

    public function loadProduk()
    {
        $id = $this->request->getPost('kategori');
        if ($id === null || $id === '' || !is_numeric($id)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->produkModel->loadProduk((int) $id));
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

        $data = $this->model
            ->select('nota_isi.*, produk.kategori')
            ->join('produk', 'produk.id = nota_isi.produk_id', 'left')
            ->find((int) $id);

        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    protected function dataSimpan(): array
    {
        $produkId = $this->request->getPost('produk_id');
        // $produk = $this->produkModel->find($produkId);
        // if (!$produk) {
        //     throw new \RuntimeException('Produk tidak ditemukan.');
        // }

        $lebar = (float) ($this->request->getPost('lebar') ?: 0);
        $panjang = (float) ($this->request->getPost('panjang') ?: 0);
        $qty = (int) ($this->request->getPost('qty') ?: 0);
        $harga = (int) ($this->request->getPost('harga') ?: 0);
        $luas = round($lebar * $panjang, 2);
        // $jumlah = (int) round((int) $produk->rumus === 0
        //     ? $luas * $qty * $harga
        //     : $qty * $harga);

        // if ((int) $produk->rumus !== 0) {
        //     $lebar = 0;
        //     $panjang = 0;
        //     $luas = 0;
        // }

        return [
            'id'           => $this->request->getPost('id'),
            'nota_id'      => $this->request->getPost('nota_id'),
            'produk_id'    => $this->request->getPost('produk_id'),
            'finishing_id' => $this->request->getPost('finishing_id'),
            'tema'         => $this->request->getPost('tema'),
            'lebar'        => $lebar,
            'panjang'      => $panjang,
            'luas'         => $luas,
            'qty'          => $qty,
            'harga'        => $harga,
            'jumlah'       => $this->request->getPost('jumlah'),
            'status'       => $this->request->getPost('status') ?: 0,
            'keterangan'   => $this->request->getPost('keterangan'),
        ];
    }

    /**
     * Memindahkan item nota dari Draft ke alur produksi.
     * Kategori 0 yang memakai bahan produksi -> 1 (Antrian cetak).
     * Eksternal, jasa, dan internal tanpa bahan produksi -> 3 (Proses).
     */
    public function produksi()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $notaId = $this->request->getPost('nota_id');
        if (!$notaId || !is_numeric($notaId)) {
            return $this->json(false, 'ID nota tidak valid', null, 400);
        }

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

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

            return $this->json(true, 'Status produksi berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Gagal memperbarui status produksi. Silakan periksa log aplikasi.');
        }
    }
}
