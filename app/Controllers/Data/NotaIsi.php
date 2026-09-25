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
            // 'menuTipeMesin' => $this->mesinTipeModel->getTipeMesin(),
            // 'menuProduk'    => $this->produkModel->getDropdown(),
            'menuFinishing' => $this->finishingModel->getDropdown(),
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $notaId = $this->request->getPost('nota_id') ?: $this->request->getGet('edit');

        return $this->model->tabel()
            ->where('a.nota_id', (int) $notaId);
    }

    protected function dataTabel(\stdClass $row): array
    {
        $noUrut = 1;
        $status = ['Draft', 'Antrian', 'Pending', 'Proses', 'Selesai', 'Batal'];
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $noUrut++,
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
}
