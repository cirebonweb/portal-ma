<?php

namespace App\Models\Umum;

use CodeIgniter\Model;

class KategoriProdukModel extends Model
{
    protected $table            = 'kategori_produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT dari dp_produk
    protected $protectFields    = true;
    protected $allowedFields = [
        'divisi',
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
        'divisi' => [
            'label' => 'Divisi',
            'rules' => 'required|in_list[0,1,2,3]'
        ],
        'nama' => [
            'label' => 'Kategori Produk',
            'rules' => 'required|string|min_length[3]|max_length[50]|is_unique[kategori_produk.nama,id,{id}]'
        ],
        'status' => [
            'label' => 'Status Produk',
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
        return $this->db->table('kategori_produk')
            ->select('id, divisi, nama, status, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar nama kategori untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }
}
