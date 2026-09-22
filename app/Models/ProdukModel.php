<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'mesin_tipe_id',
        'kategori',
        'nama',
        'hpp',
        'harga',
        'promo',
        'promo_awal',
        'promo_akhir',
        'unggulan',
        'status'
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
        'mesin_tipe_id' => [
            'label' => 'Tipe Mesin',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'kategori' => [
            'label' => 'Kategori',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'nama' => [
            'label' => 'Nama Produk',
            'rules' => 'required|string|max_length[100]|is_unique[produk.nama,id,{id}]'
        ],
        'hpp' => [
            'label' => 'HPP',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'promo' => [
            'label' => 'Promo',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'promo_awal' => [
            'label' => 'Promo Awal',
            'rules' => 'permit_empty|valid_date'
        ],
        'promo_akhir' => [
            'label' => 'Promo Akhir',
            'rules' => 'permit_empty|valid_date'
        ],
        'unggulan' => [
            'label' => 'Unggulan',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('produk a')
            ->select('a.id, a.kategori, a.nama, a.hpp, a.harga, a.promo, a.promo_awal, a.promo_akhir, a.unggulan, a.status, a.created_at, a.updated_at, b.nama as mesin')
            ->join('mesin_tipe b', 'b.id = a.mesin_tipe_id', 'left');
    }
}
