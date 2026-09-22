<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanStokModel extends Model
{
    protected $table            = 'bahan_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan_sisa, bahan_limbah, cetak
    protected $protectFields    = true;
    protected $allowedFields = [
        'bahan_id',
        'bahan_order_id',
        'kode_bahan',
        'stok_masuk',
        'stok_pakai',
        'stok_sisa',
        'kondisi',
        'status',
        'keterangan'
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
        'bahan_id' => [
            'label' => 'Bahan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'kode_bahan' => [
            'label' => 'Kode Stok',
            'rules' => 'permit_empty|string|max_length[20]'
        ],
        'stok_masuk' => [
            'label' => 'Stok Masuk',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ],
        'stok_pakai' => [
            'label' => 'Stok Pakai',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'stok_sisa' => [
            'label' => 'Stok Sisa',
            'rules' => 'required|decimal|greater_than_equal_to[0]'
        ],
        'kondisi' => [
            'label' => 'Kondisi',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[100]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('bahan_stok a')
            ->select('a.id, a.bahan_id, a.bahan_order_id, a.kode_bahan, a.stok_masuk, a.stok_pakai, a.stok_sisa, a.kondisi, a.status, a.keterangan, a.created_at, a.updated_at, b.nama as nama_bahan, b.gsm, b.lebar, b.panjang, b.satuan_1, c.nama as nama_supplier')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('bahan_order d', 'd.id = a.bahan_order_id', 'left')
            ->join('supplier c', 'c.id = d.supplier_id', 'left');
    }

    /**
     * Custom getId
     * @param mixed $id
     */
    public function getId($id)
    {
        return $this->db->table('bahan_stok a')
            ->select('a.id, a.kode_bahan, a.kondisi, a.status, a.keterangan, b.nama as nama_bahan, b.gsm, c.nama as nama_supplier')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('bahan_order d', 'd.id = a.bahan_order_id', 'left')
            ->join('supplier c', 'c.id = d.supplier_id', 'left')
            ->where('a.id', $id)
            ->get()->getRow();
    }
}
