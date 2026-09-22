<?php

namespace App\Models;

use CodeIgniter\Model;

class CetakIsiModel extends Model
{
    protected $table            = 'cetak_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'cetak_id',
        'nota_isi_id'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'cetak_id' => [
            'label' => 'Cetak',
            'rules' => 'required|is_natural_no_zero'
        ],
        'nota_isi_id' => [
            'label' => 'Nota Isi',
            'rules' => 'required|is_natural_no_zero'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('cetak_isi a')
            ->select('a.id, a.cetak_id, a.nota_isi_id, a.created_at, b.id as cetak_ref, c.id as nota_isi_ref')
            ->join('cetak b', 'b.id = a.cetak_id', 'left')
            ->join('nota_isi c', 'c.id = a.nota_isi_id', 'left');
    }
}
