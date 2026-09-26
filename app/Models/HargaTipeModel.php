<?php

namespace App\Models;

use CodeIgniter\Model;

class HargaTipeModel extends Model
{
    protected $table            = 'harga_tipe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'konsumen_tipe_id',
        'produk_id',
        'harga'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'konsumen_tipe_id' => [
            'label' => 'Tipe Konsumen',
            'rules' => 'required|is_natural_no_zero'
        ],
        'produk_id' => [
            'label' => 'Produk',
            'rules' => 'required|is_natural_no_zero'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'required|integer|greater_than_equal_to[0]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('harga_tipe a')
            ->select('a.id, a.konsumen_tipe_id, a.produk_id, a.harga, a.created_at, a.updated_at, b.nama as tipe, c.nama as produk, c.harga as harga_produk')
            ->join('konsumen_tipe b', 'b.id = a.konsumen_tipe_id', 'left')
            ->join('produk c', 'c.id = a.produk_id', 'left');
    }
}
