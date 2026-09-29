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
        'bahan_jenis_id',
        'material_jenis_id',
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
        'bahan_jenis_id' => [
            'label' => 'Bahan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'material_jenis_id' => [
            'label' => 'Material',
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
            ->select('a.id, a.bahan_jenis_id, a.material_jenis_id, a.kategori, a.nama, a.lebar, a.panjang, a.rumus, a.hpp, a.harga, a.promo, a.promo_awal, a.promo_akhir, a.unggulan, a.status, a.created_at, a.updated_at, b.nama as bahan, d.nama as material, c.nama as mesin')
            ->join('bahan_jenis b', 'b.id = a.bahan_jenis_id', 'left')
            ->join('bahan_jenis d', 'd.id = a.material_jenis_id', 'left')
            ->join('mesin_tipe c', 'c.id = b.mesin_tipe_id', 'left');
    }

    /**
     * Custom getId untuk mendapatkan mesin_tipe_id.
     * @param mixed $id
     */
    public function getId($id)
    {
        return $this
            ->select('produk.*, bahan_jenis.mesin_tipe_id')
            ->join('bahan_jenis', 'bahan_jenis.id = produk.bahan_jenis_id', 'left')
            ->find($id);
    }

    /**
     * Mendapatkan daftar produk untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this
            ->select('id, nama, lebar, panjang, rumus, harga')
            ->orderBy('produk.nama', 'ASC')
            ->findAll();
    }

    /**
     * @param mixed $id
     * Mendapatkan list produk berdasarkan kategori produk
     * digunakan pada halaman /nota/detail/{id}
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
