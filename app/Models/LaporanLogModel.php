<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanLogModel extends Model
{
    protected $table            = 'laporan_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'laporan_id',
        'status',
        'keterangan',
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
        'laporan_id' => [
            'label' => 'Laporan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'required|in_list[0,1,2]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'user_id' => [
            'label' => 'User',
            'rules' => 'required|is_natural_no_zero'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('laporan_log a')
            ->select('a.id, a.laporan_id, a.status, a.keterangan, a.user_id, a.created_at, b.tanggal as laporan_tanggal, c.username as user_nama')
            ->join('laporan b', 'b.id = a.laporan_id', 'left')
            ->join('users c', 'c.id = a.user_id', 'left');
    }
}
