<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpMesinModel extends Model
{
    protected $table            = 'dp_mesin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // master data, dilindungi FK RESTRICT dari dp_produk

    protected $allowedFields = [
        'kategori',
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
        'kategori' => [
            'label' => 'Kategori Mesin',
            'rules' => 'required|string|max_length[30]'
        ],
        'nama' => [
            'label' => 'Nama Mesin',
            'rules' => 'required|string|max_length[100]|is_unique[dp_mesin.nama,id,{id}]'
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
        return $this->db->table('dp_mesin a')
            ->select('a.id, a.kategori, a.nama, a.created_at, a.updated_at');
    }

    /**
     * Cek apakah mesin masih digunakan oleh produk.
     * Tabel ini tidak soft delete, jadi FK RESTRICT akan menolak delete
     * jika masih direferensikan dp_produk.
     */
    public function isUsed(int $id): bool
    {
        return $this->db->table('dp_produk')
            ->where('dp_mesin_id', $id)
            ->countAllResults() > 0;
    }

    /**
     * Mendapatkan daftar nama mesin untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->findAll();
    }
}
