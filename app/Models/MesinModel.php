<?php

namespace App\Models;

use CodeIgniter\Model;

class MesinModel extends Model
{
    protected $table            = 'mesin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → cetak
    protected $protectFields    = true;
    protected $allowedFields = [
        'mesin_tipe_id',
        'nama',
        'print_area',
        'min_lebar',
        'max_lebar',
        'min_panjang'
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
        'mesin_tipe_id' => [
            'label' => 'Tipe Mesin',
            'rules' => 'required|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Mesin',
            'rules' => 'required|string|min_length[3]|max_length[30]|is_unique[mesin.nama,id,{id}]'
        ],
        'print_area' => [
            'label' => 'Print Area',
            'rules' => 'permit_empty|in_list[0,1]' // 0:Tidak, 1:Ya = min-max wajib diisi
        ],
        'min_lebar' => [
            'label' => 'Minimal Lebar',
            'rules' => 'permit_empty|decimal|max_length[5]'
        ],
        'max_lebar' => [
            'label' => 'Maksimal Lebar',
            'rules' => 'permit_empty|decimal|max_length[5]'
        ],
        'min_panjang' => [
            'label' => 'Minimal Panjang',
            'rules' => 'permit_empty|decimal|max_length[5]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('mesin a')
            ->select('a.id, a.nama as nama_mesin, a.print_area, a.min_lebar, a.max_lebar, a.min_panjang, a.created_at, a.updated_at, b.nama as tipe_mesin')
            ->join('mesin_tipe b', 'b.id = a.mesin_tipe_id', 'left');
    }

    /**
     * Mendapatkan daftar nama mesin untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }
}
