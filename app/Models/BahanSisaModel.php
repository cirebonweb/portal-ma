<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanSisaModel extends Model
{
    protected $table            = 'bahan_sisa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan_limbah, cetak
    protected $protectFields    = true;
    protected $allowedFields = [
        'bahan_id',
        'cetak_id',
        'lebar',
        'panjang',
        'luas',
        'status',
        'keterangan'
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
        'bahan_id' => [
            'label' => 'Bahan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'cetak_id' => [
            'label' => 'Cetak',
            'rules' => 'required|is_natural_no_zero'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'luas' => [
            'label' => 'Luas',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('bahan_sisa a')
            ->select('a.id, a.bahan_id, a.cetak_id, a.lebar, a.panjang, a.luas, a.status, a.keterangan, a.created_at, a.updated_at, b.nama as bahan, c.id as cetak_id_ref')
            ->join('bahan b', 'b.id = a.bahan_id', 'left')
            ->join('cetak c', 'c.id = a.cetak_id', 'left');
    }

    /**
     * Mendapatkan daftar sisa bahan untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, luas')->orderBy('created_at', 'DESC')->findAll();
    }
}
