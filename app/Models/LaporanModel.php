<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'tanggal',
        'pemasukan',
        'pengeluaran',
        'pendapatan',
        'tunai',
        'transfer',
        'diserahkan_id',
        'diterima_id',
        'diketahui_id',
        'status',
        'locked_by',
        'locked_at',
        'keterangan',
        'user_buat',
        'user_ubah'
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
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date|is_unique[laporan.tanggal,id,{id}]'
        ],
        'pemasukan' => [
            'label' => 'Pemasukan',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'pengeluaran' => [
            'label' => 'Pengeluaran',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'pendapatan' => [
            'label' => 'Pendapatan',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'tunai' => [
            'label' => 'Tunai',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'transfer' => [
            'label' => 'Transfer',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'diserahkan_id' => [
            'label' => 'Diserahkan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'diterima_id' => [
            'label' => 'Diterima',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'diketahui_id' => [
            'label' => 'Diketahui',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'locked_by' => [
            'label' => 'Locked By',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'locked_at' => [
            'label' => 'Locked At',
            'rules' => 'permit_empty|valid_datetime'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'user_buat' => [
            'label' => 'User Buat',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'user_ubah' => [
            'label' => 'User Ubah',
            'rules' => 'permit_empty|is_natural_no_zero'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('laporan a')
            ->select('a.id, a.tanggal, a.pemasukan, a.pengeluaran, a.pendapatan, a.tunai, a.transfer, a.diserahkan_id, a.diterima_id, a.diketahui_id, a.status, a.locked_by, a.locked_at, a.keterangan, a.user_buat, a.user_ubah, a.created_at, a.updated_at, b.username as diserahkan, c.username as diterima, d.username as diketahui, e.username as locker, f.username as user_buat_nama, g.username as user_ubah_nama')
            ->join('users b', 'b.id = a.diserahkan_id', 'left')
            ->join('users c', 'c.id = a.diterima_id', 'left')
            ->join('users d', 'd.id = a.diketahui_id', 'left')
            ->join('users e', 'e.id = a.locked_by', 'left')
            ->join('users f', 'f.id = a.user_buat', 'left')
            ->join('users g', 'g.id = a.user_ubah', 'left');
    }
}
