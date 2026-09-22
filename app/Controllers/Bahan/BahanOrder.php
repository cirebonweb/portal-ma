<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Controllers\Traits\CrudTrait;
use App\Models\BahanOrderIsiModel;
use App\Models\BahanStokModel;
use App\Models\BahanOrderModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\BaseBuilder;

class BahanOrder extends BaseController
{
    use CrudTrait;

    protected BahanOrderModel $model;
    protected BahanOrderIsiModel $bahanOrderIsiModel;
    protected BahanStokModel $bahanStokModel;
    protected SupplierModel $supplierModel;

    protected $searchable = ['a.tgl_order', 'a.no_order', 'b.nama'];
    protected $orderable = ['a.id', 'a.tgl_order', 'a.no_order', 'b.nama'];

    public function __construct()
    {
        $this->model = new BahanOrderModel();
        $this->bahanOrderIsiModel = new BahanOrderIsiModel();
        $this->bahanStokModel = new BahanStokModel();
        $this->supplierModel = new SupplierModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle'    => 'Order Bahan',
            'navigasi'     => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuSupplier' => $this->supplierModel->getDropdown(),
        ];
        return view('bahan/bahan_order', $data);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterSupplier = $this->request->getPost('filter_supplier');
        if (!empty($filterSupplier)) {
            $builder->where('a.supplier_id', $filterSupplier);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<a href="/bahan-order/isi?edit=' . $row->id . '" class="btn btn-sm btn-primary">detail</a>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->tgl_order,
            $row->no_order ?: '-',
            $row->supplier ?: '-',
            $row->total_qty . ' item',
            $row->subtotal,
            $row->ongkir,
            $row->total,
            $row->status_stok ? 'Stok bahan sudah ditambahkan' : 'Stok bahan belum ditambahkan',
            $row->keterangan ?: '-',
            $row->user_buat,
            $row->user_ubah,
            $row->updated_at,
            $row->updated_at,
            $aksi
        ];
    }

    protected function dataSimpan(): array
    {
        $userId = auth()->user()?->id;

        return [
            'id'          => $this->request->getPost('id'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'tgl_order'   => $this->request->getPost('tgl_order'),
            'no_order'    => $this->request->getPost('no_order'),
            'subtotal'    => $this->request->getPost('subtotal'),
            'ongkir'      => $this->request->getPost('ongkir'),
            'total'       => $this->request->getPost('total'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'user_buat'   => $userId,
            'user_ubah'   => $userId
        ];
    }

    public function tambahStok()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID order bahan tidak valid', null, 400);
        }

        $db = db_connect();

        try {
            $db->transBegin();

            $order = $db->query(
                'SELECT id, status_stok FROM bahan_order WHERE id = ? FOR UPDATE',
                [(int) $id]
            )->getRow();

            if (!$order) {
                $db->transRollback();
                return $this->json(false, 'Order bahan tidak ditemukan', null, 404);
            }

            if ((int) $order->status_stok !== 0) {
                $db->transRollback();
                return $this->json(false, 'Stok bahan pada order ini sudah ditambahkan.');
            }

            $items = $this->bahanOrderIsiModel->getForStok((int) $id);
            if ($items === []) {
                $db->transRollback();
                return $this->json(false, 'Order bahan belum memiliki item.');
            }

            $sequence = $this->bahanStokModel->countAllResults() + 1;
            foreach ($items as $item) {
                $stokPerPaket = (float) $item->isi_paket;

                if ((int) $item->qty < 1 || $stokPerPaket <= 0) {
                    throw new \RuntimeException('Isi paket atau qty item order tidak valid.');
                }

                for ($paket = 0; $paket < (int) $item->qty; $paket++) {
                    do {
                        $kode = $item->kode . '-' . $item->id . '-' . str_pad((string) $sequence++, 3, '0', STR_PAD_LEFT);
                        $kodeAda = $db->table('bahan_stok')->where('kode_bahan', $kode)->countAllResults() > 0;
                    } while ($kodeAda);

                    $stok = round($stokPerPaket, 2);
                    if (!$this->bahanStokModel->insert([
                        'bahan_id'       => $item->bahan_id,
                        'bahan_order_id' => $id,
                        'kode_bahan'     => $kode,
                        'stok_masuk'     => $stok,
                        'stok_pakai'     => 0,
                        'stok_sisa'      => $stok,
                        'kondisi'        => 0,
                        'status'         => 0,
                    ])) {
                        throw new \RuntimeException(implode('; ', $this->bahanStokModel->errors()));
                    }
                }
            }

            if (!$this->model->update((int) $id, ['status_stok' => 1])) {
                throw new \RuntimeException(implode('; ', $this->model->errors()));
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();
            return $this->json(true, 'Stok bahan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Gagal menambahkan stok bahan. Silakan periksa log aplikasi.');
        }
    }

    protected function dataSimpanResponse(bool $isInsert, array $data): ?array
    {
        if (!$isInsert) {
            return null;
        }
        return ['id' => $this->model->getInsertID()];
    }

    public function getTotal()
    {
        $id = $this->request->getPost('id');
        $data = $this->model->getTotal($id);

        return $this->response->setJSON([
            'success' => $data !== null,
            'data'    => $data,
        ]);
    }
}
