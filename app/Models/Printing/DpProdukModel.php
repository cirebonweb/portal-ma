<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpProdukModel extends Model
{
    protected $table            = 'dp_produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'dp_mesin_id',
        'dp_bahan_id',
        'nama',
        'lebar',
        'panjang',
        'hpp',
        'harga',
        'promo',
        'promo_awal',
        'promo_akhir',
        'promo_aktif',
        'rumus',
        'unggulan'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = false;

    // Validation
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'dp_mesin_id' => [
            'label' => 'Kategori Mesin',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_bahan_id' => [
            'label' => 'Kategori Bahan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Produk',
            'rules' => 'required|max_length[100]|is_unique[dp_produk.nama,id,{id}]'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'decimal|greater_than_equal_to[0]'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'decimal|greater_than_equal_to[0]'
        ],
        'hpp' => [
            'label' => 'HPP',
            'rules' => 'integer|greater_than_equal_to[0]'
        ],
        'harga' => [
            'label' => 'Harga Jual',
            'rules' => 'integer|greater_than_equal_to[0]'
        ],
        'promo' => [
            'label' => 'Harga Promo',
            'rules' => 'permit_empty|integer'
        ],
        'promo_awal' => [
            'label' => 'Tgl. Promo Awal',
            'rules' => 'permit_empty|valid_date'
        ],
        'promo_akhir' => [
            'label' => 'Tgl. Promo Akhir',
            'rules' => 'permit_empty|valid_date'
        ],
        'rumus' => [
            'label' => 'Rumus',
            'rules' => 'in_list[0,1]'
        ],
        'unggulan' => [
            'label' => 'Unggulan',
            'rules' => 'permit_empty|in_list[0,1]'
        ]
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Query dasar untuk server-side dataTabel.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_produk a')
            ->select('a.id, a.nama, a.lebar, a.panjang, a.hpp, a.harga, 
            a.promo, a.promo_awal, a.promo_akhir, a.rumus, a.created_at, a.updated_at, 
            b.nama mesin, c.nama bahan')
            ->join('dp_mesin b', 'b.id = a.dp_mesin_id', 'left')
            ->join('dp_bahan c', 'c.id = a.dp_bahan_id', 'left');
    }
}
