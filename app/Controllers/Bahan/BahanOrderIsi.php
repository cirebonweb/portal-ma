<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\BahanOrderIsiModel;
use App\Models\BahanOrderModel;
use App\Models\BahanModel;
use App\Models\MesinTipeModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\BaseBuilder;

class BahanOrderIsi extends BaseController
{
    use CrudTrait;
    private int $noUrut = 0;

    protected BahanOrderIsiModel $model;
    protected BahanOrderModel $bahanOrderModel;
    protected BahanModel $bahanModel;
    protected MesinTipeModel $mesinTipeModel;
    protected SupplierModel $supplierModel;

    // protected $searchable = ['b.nama'];
    protected $orderable = ['a.id', 'b.nama'];

    public function __construct()
    {
        $this->model = new BahanOrderIsiModel();
        $this->bahanOrderModel = new BahanOrderModel();
        $this->bahanModel = new BahanModel();
        $this->mesinTipeModel = new MesinTipeModel();
        $this->supplierModel = new SupplierModel();
        // helper('master');
    }

    public function index()
    {
        $add  = $this->request->getGet('add');
        $edit = $this->request->getGet('edit');

        if ($add !== null) {
            $item = 'add';
            $id   = null;
        } elseif ($edit !== null) {
            $item = 'edit';
            $id   = $edit;
        } else {
            return redirect()->to('/bahan-order');
        }

        $data = [
            // 'pageTitle'     => $item === 'edit' ? 'Edit Item Order Bahan' : 'Tambah Item Order Bahan',
            'pageTitle'     => 'Edit Detail Item Order Bahan',
            'navTitle'      => 'Detail Order Bahan',
            'navigasi'      => '<a href="/bahan">Bahan</a> &nbsp; / &nbsp; <a href="/bahan-order">Order Bahan</a> &nbsp;',
            'item'          => $item,
            'id'            => $id,
            'dataOrder'     => $this->bahanOrderModel->getDataOrder($id),
            'menuTipeMesin' => $this->mesinTipeModel->getTipeMesin(),
            'menuBahan'     => $this->bahanModel->getDropdown()
        ];

        return view('bahan/bahan_order_isi', $data);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $bahanOrderId = $this->request->getPost('bahanOrderId') ?: $this->request->getGet('edit');

        // Nomor urut mengikuti posisi baris pada halaman DataTables
        $this->noUrut = (int) $this->request->getPost('start');

        return $this->model->tabel()->where('a.bahan_order_id', (int) $bahanOrderId);
    }

    /**
     * @param mixed $row
     */
    protected function dataTabel($row)
    {
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            ++$this->noUrut,
            $row->nama_bahan,
            'Rp ' . number_format($row->harga_satuan, 0, ',', '.') . ' /' . $row->satuan_1,
            'Rp ' . number_format($row->harga_paket, 0, ',', '.') . ' /' . $row->satuan_2,
            $row->qty . ' ' . $row->satuan_2,
            'Rp ' . number_format($row->jumlah, 0, ',', '.'),
            $row->keterangan,
            $row->created_at,
            $row->updated_at,
            $aksi
        ];
    }

    /**
     * Mengambil daftar bahan (kategori = 0) berdasarkan tipe mesin,
     * atau seluruh material (kategori = 1) bila kategori = 1 dipilih.
     */
    public function getBahan()
    {
        if ((int) $this->request->getPost('kategori') === 1) {
            return $this->response->setJSON($this->bahanModel->getMaterial());
        }

        $mesinTipeId = $this->request->getPost('mesin_tipe_id');
        if (empty($mesinTipeId) || !is_numeric($mesinTipeId)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->bahanModel->getBahanMesin((int) $mesinTipeId));
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
            'id'             => $this->request->getPost('id'),
            'bahan_order_id' => $this->request->getPost('bahan_order_id'),
            'bahan_id'       => $this->request->getPost('bahan_id'),
            'qty'            => $this->request->getPost('qty'),
            'harga_satuan'   => $this->request->getPost('harga_satuan'),
            'harga_paket'    => $this->request->getPost('harga_paket'),
            'jumlah'         => $this->request->getPost('jumlah')
        ];
    }
}
