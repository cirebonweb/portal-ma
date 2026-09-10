<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpBahanModel extends Model
{
    protected $table            = 'dp_bahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT dari dp_produk
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama',
        'status'
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
        'nama' => [
            'label' => 'Nama Bahan',
            'rules' => 'required|string|min_length[3]|max_length[30]|is_unique[dp_bahan.nama,id,{id}]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'required|in_list[0,1]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_bahan')
            ->select('id, nama, status, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar nama bahan untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->findAll();
    }
}
