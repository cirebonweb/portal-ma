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
        'tanggal',
        'metode',
        'file',
        'diterima',
        'jumlah',
        'kembalian',
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
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date'
        ],
        'metode' => [
            'label' => 'Metode',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'file' => [
            'label' => 'File',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'diterima' => [
            'label' => 'Diterima',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'jumlah' => [
            'label' => 'Jumlah',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'kembalian' => [
            'label' => 'Kembalian',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
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
            ->select('a.id, a.nota_id, a.tanggal, a.metode, a.file, a.diterima, a.jumlah, a.kembalian, a.user_id, a.created_at, a.updated_at, b.no_nota, c.username as user_nama')
            ->join('nota b', 'b.id = a.nota_id', 'left')
            ->join('users c', 'c.id = a.user_id', 'left');
    }
}
