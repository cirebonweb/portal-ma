<?php

namespace App\Models;

use CodeIgniter\Model;

class FinishingModel extends Model
{
    protected $table            = 'finishing';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Finishing',
            'rules' => 'required|string|min_length[3]|max_length[50]|is_unique[finishing.nama,id,{id}]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('finishing')
            ->select('id, nama, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar finishing untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }
}
