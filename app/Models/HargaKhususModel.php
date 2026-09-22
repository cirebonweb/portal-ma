<?php

namespace App\Models;

use CodeIgniter\Model;

class HargaKhususModel extends Model
{
    protected $table            = 'harga_khusus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'konsumen_id',
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
        'konsumen_id' => [
            'label' => 'Konsumen',
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
        return $this->db->table('harga_khusus a')
            ->select('a.id, a.konsumen_id, a.produk_id, a.harga, a.created_at, a.updated_at, b.nama as konsumen, c.nama as produk')
            ->join('konsumen b', 'b.id = a.konsumen_id', 'left')
            ->join('produk c', 'c.id = a.produk_id', 'left');
    }
}
