<?php

namespace App\Models;

use CodeIgniter\Model;

class NotaBayarModel extends Model
{
    protected $table            = 'nota_bayar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nota_id',
        'metode_bayar',
        'tgl_bayar',
        'uang_terima',
        'uang_bayar',
        'uang_kembali',
        'bukti_transfer',
        'user_id'
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
        'nota_id' => [
            'label' => 'Nota',
            'rules' => 'required|is_natural_no_zero'
        ],
        'metode_bayar' => [
            'label' => 'Metode Bayar',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'tgl_bayar' => [
            'label' => 'Tanggal Bayar',
            'rules' => 'required|valid_date'
        ],
        'uang_terima' => [
            'label' => 'Uang Terima',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'uang_bayar' => [
            'label' => 'Uang Bayar',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'kembalian' => [
            'label' => 'Uang Kembali',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'bukti_transfer' => [
            'label' => 'Bukti Transfer',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'user_id' => [
            'label' => 'User',
            'rules' => 'permit_empty|is_natural_no_zero'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('nota_bayar a')
            ->select('a.id, a.metode_bayar, a.tgl_bayar, a.uang_terima, a.uang_bayar, a.uang_kembali, a.bukti_transfer, a.created_at, a.updated_at, b.no_nota, c.username as user_nama')
            ->join('nota b', 'b.id = a.nota_id', 'left')
            ->join('users c', 'c.id = a.user_id', 'left');
    }
}
