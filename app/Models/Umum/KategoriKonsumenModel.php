<?php

namespace App\Models\Umum;

use CodeIgniter\Model;

class KategoriKonsumenModel extends Model
{
    protected $table            = 'kategori_konsumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
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
            'label' => 'Kategori Konsumen',
            'rules' => 'required|string|max_length[20]|is_unique[kategori_konsumen.nama,id,{id}]'
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
        return $this->db->table('kategori_konsumen')
            ->select('id, nama, status, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar nama kategori konsumen untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->findAll();
    }
}
