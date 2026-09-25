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
        'bahan_id',
        'kategori',
        'nama',
        'lebar',
        'panjang',
        'rumus',
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
        'bahan_id' => [
            'label' => 'Bahan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'kategori' => [
            'label' => 'Kategori',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'rumus' => [
            'label' => 'Rumus',
            'rules' => 'permit_empty|in_list[0,1]'
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
            ->select('a.id, a.bahan_id, a.kategori, a.nama, a.lebar, a.panjang, a.rumus, a.hpp, a.harga, a.promo, a.promo_awal, a.promo_akhir, a.unggulan, a.status, a.created_at, a.updated_at, b.nama as bahan, c.nama as mesin')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('mesin_tipe c', 'c.id = b.mesin_tipe_id', 'left');
    }

    // public function getDropdown()
    // {
    //     return $this
    //         ->select('id, nama, lebar, panjang, rumus, harga')
    //         ->orderBy('produk.nama', 'ASC')
    //         ->findAll();
    // }

    /**
     * @param mixed $id
     * Mendapatkan list produk berdasarkan kategori produk
     * digunakan: /nota/isi?edit=
     */
    public function loadProduk($id)
    {
        return $this
            ->select('id, nama, lebar, panjang, rumus, harga')
            ->where('kategori', $id)
            ->orderBy('produk.nama', 'ASC')
            ->findAll();
    }
}
