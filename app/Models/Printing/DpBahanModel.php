<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpBahanModel extends Model
{
    protected $table            = 'dp_bahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // master data, dilindungi FK RESTRICT dari dp_produk

    protected $allowedFields = [
        'nama',
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
            'rules' => 'required|string|max_length[30]|is_unique[dp_bahan.nama,id,{id}]'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_bahan a')
            ->select('a.id, a.nama, a.created_at, a.updated_at');
    }

    /**
     * Cek apakah bahan masih digunakan oleh produk.
     * Tabel ini tidak soft delete, jadi FK RESTRICT akan menolak delete
     * jika masih direferensikan dp_produk.
     */
    public function isUsed(int $id): bool
    {
        return $this->db->table('dp_produk')
            ->where('dp_bahan_id', $id)
            ->countAllResults() > 0;
    }

    /**
     * Mendapatkan daftar nama bahan untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->findAll();
    }
}
