<?php

namespace App\Models;

use CodeIgniter\Model;

class MesinTipeModel extends Model
{
    protected $table            = 'mesin_tipe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → mesin, bahan
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
            'label' => 'Tipe Mesin',
            'rules' => 'required|string|min_length[3]|max_length[100]|is_unique[mesin_tipe.nama,id,{id}]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('mesin_tipe')
            ->select('id, nama, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar tipe mesin untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Mendapatkan list mesin_tipe berdasarkan data yang dimiliki pada tabel bahan.
     * Selanjutnya diteruskan 'BahanModel' → 'getBahanMesin($id)'
     */
    public function getTipeMesin()
    {
        return $this->select('mesin_tipe.id, mesin_tipe.nama')
            ->join('bahan_jenis', 'bahan_jenis.mesin_tipe_id = mesin_tipe.id')
            ->groupBy('mesin_tipe.id')
            ->orderBy('mesin_tipe.nama', 'ASC')
            ->findAll();
    }
}
