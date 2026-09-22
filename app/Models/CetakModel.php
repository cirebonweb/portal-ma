<?php

namespace App\Models;

use CodeIgniter\Model;

class CetakModel extends Model
{
    protected $table            = 'cetak';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'mesin_id',
        'bahan_stok_id',
        'bahan_sisa_id',
        'tanggal',
        'lebar',
        'panjang',
        'luas',
        'lokasi',
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
        'mesin_id' => [
            'label' => 'Mesin',
            'rules' => 'required|is_natural_no_zero'
        ],
        'bahan_stok_id' => [
            'label' => 'Bahan Stok',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'bahan_sisa_id' => [
            'label' => 'Bahan Sisa',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date'
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
        'lokasi' => [
            'label' => 'Lokasi',
            'rules' => 'permit_empty|string|max_length[255]'
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
        return $this->db->table('cetak a')
            ->select('a.id, a.mesin_id, a.bahan_stok_id, a.bahan_sisa_id, a.tanggal, a.lebar, a.panjang, a.luas, a.lokasi, a.keterangan, a.user_id, a.created_at, a.updated_at, b.nama as mesin, c.kode_bahan as bahan_stok, d.luas as bahan_sisa_luas, e.username as user_nama')
            ->join('mesin b', 'b.id = a.mesin_id', 'left')
            ->join('bahan_stok c', 'c.id = a.bahan_stok_id', 'left')
            ->join('bahan_sisa d', 'd.id = a.bahan_sisa_id', 'left')
            ->join('users e', 'e.id = a.user_id', 'left');
    }
}
