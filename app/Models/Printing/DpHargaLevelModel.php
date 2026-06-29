<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpHargaLevelModel extends Model
{
    protected $table            = 'dp_harga_level';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'level_harga_id',
        'dp_produk_id',
        'harga'
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
        'level_harga_id' => [
            'label' => 'Level Harga',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_produk_id' => [
            'label' => 'Nama Produk',
            'rules' => 'required|is_natural_no_zero'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'integer|greater_than_equal_to[0]'
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
        return $this->db->table('dp_harga_level a')
            ->select('a.id, a.harga harga_level, a.created_at, a.updated_at, b.nama level, c.nama produk, c.harga harga_produk')
            ->join('level_harga b', 'b.id = a.level_harga_id', 'left')
            ->join('dp_produk c', 'c.id = a.dp_produk_id', 'left');
    }
}
