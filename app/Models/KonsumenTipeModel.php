<?php

namespace App\Models;

use CodeIgniter\Model;

class KonsumenTipeModel extends Model
{
    protected $table            = 'konsumen_tipe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → konsumen, harga_tipe
    protected $protectFields    = true;
    protected $allowedFields    = ['nama'];

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
            'label' => 'Tipe Konsumen',
            'rules' => 'required|string|min_length[3]|max_length[20]|is_unique[konsumen_tipe.nama,id,{id}]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('konsumen_tipe')
            ->select('id, nama, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar tipe konsumen untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }
}
