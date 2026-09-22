<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanOrderIsiModel extends Model
{
    protected $table            = 'bahan_order_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'bahan_order_id',
        'bahan_id',
        'harga_satuan',
        'harga_paket',
        'qty',
        'jumlah',
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
        'bahan_order_id' => [
            'label' => 'ID Bahan Order',
            'rules' => 'required|is_natural_no_zero'
        ],
        'bahan_id' => [
            'label' => 'ID Bahan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'harga_satuan' => [
            'label' => 'Harga Satuan',
            'rules' => 'required|integer|greater_than[0]'
        ],
        'harga_paket' => [
            'label' => 'Ongkir',
            'rules' => 'required|integer|greater_than[0]'
        ],
        'qty' => [
            'label' => 'Qty Paket',
            'rules' => 'required|integer|greater_than[0]'
        ],
        'jumlah' => [
            'label' => 'Jumlah',
            'rules' => 'required|integer|greater_than[0]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('bahan_order_isi a')
            ->select('a.id, a.harga_satuan, a.harga_paket, a.qty, a.jumlah, a.keterangan, a.created_at, a.updated_at, b.nama as nama_bahan, b.satuan_1, b.satuan_2, c.status_stok')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('bahan_order c', 'c.id = a.bahan_order_id', 'left');
    }

    /**
     * @param mixed $id
     */
    public function getId($id)
    {
        return $this->db->table('bahan_order_isi a')
            ->select('a.id, a.bahan_id, a.harga_satuan, a.harga_paket, a.qty, a.jumlah, c.id as id_tipe_mesin')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('mesin_tipe c', 'c.id = b.mesin_tipe_id', 'left')
            ->where('a.id', $id)
            ->get()->getRow();
    }

    /**
     * Mendapatkan item order beserta aturan konversi stok dari bahan.
     */
    public function getForStok(int $orderId): array
    {
        return $this->db->table('bahan_order_isi a')
            ->select('a.id, a.bahan_id, a.qty, b.kode, b.lebar, b.panjang, b.isi_paket, b.rumus')
            ->join('bahan b', 'b.id = a.bahan_id', 'inner')
            ->where('a.bahan_order_id', $orderId)
            ->orderBy('a.id', 'ASC')
            ->get()->getResult();
    }
}
