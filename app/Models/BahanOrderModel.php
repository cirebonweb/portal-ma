<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanOrderModel extends Model
{
    protected $table            = 'bahan_order';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan_stok, bahan_order_isi
    protected $protectFields    = true;
    protected $allowedFields    = [
        'supplier_id',
        'tgl_order',
        'no_order',
        'total_qty',
        'subtotal',
        'ongkir',
        'total',
        'status_stok',
        'keterangan',
        'user_buat',
        'user_ubah'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi server-side
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'supplier_id' => [
            'label' => 'Nama Supplier',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tgl_order' => [
            'label' => 'Tanggal Order',
            'rules' => 'required|valid_date'
        ],
        'no_order' => [
            'label' => 'Nomor Order',
            'rules' => 'permit_empty|string|max_length[50]'
        ],
        'total_qty' => [
            'label' => 'Total Item',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'subtotal' => [
            'label' => 'Subtotal',
            'rules' => 'permit_empty|integer|greater_than[0]'
        ],
        'ongkir' => [
            'label' => 'Ongkir',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'total' => [
            'label' => 'Total',
            'rules' => 'permit_empty|integer|greater_than[0]'
        ],
        'status_stok' => [
            'label' => 'Status Stok',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'user_buat' => [
            'label' => 'User Buat',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'user_ubah' => [
            'label' => 'User Ubah',
            'rules' => 'permit_empty|is_natural_no_zero'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('bahan_order a')
            ->select('a.id, a.supplier_id, a.tgl_order, a.no_order, a.total_qty, a.subtotal, a.ongkir, a.total, a.status_stok, a.keterangan, a.user_buat, a.user_ubah, a.created_at, a.updated_at, b.nama as supplier')
            ->join('supplier b', 'b.id = a.supplier_id', 'left');
    }

    /**
     * Mendapatkan detail bahan_order untuk html lain.
     * @param mixed $id
     */
    public function getDataOrder($id)
    {
        return $this->db->table('bahan_order a')
            ->select('a.id, a.tgl_order, a.no_order, a.status_stok, b.nama as supplier, b.perusahaan, b.alamat, b.kota, b.kontak, b.email')
            ->join('supplier b', 'b.id = a.supplier_id', 'left')
            ->where('a.id', $id)
            ->get()->getRow();
    }

    /**
     * @param mixed $id
     * Mendapatkan informasi total
     */
    public function getTotal($id)
    {
        return $this->select('subtotal, ongkir, total')
            ->where('id', $id)
            ->first();
    }
}
