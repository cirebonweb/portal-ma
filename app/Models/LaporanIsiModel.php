<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanIsiModel extends Model
{
    protected $table            = 'laporan_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'laporan_id',
        'nota_id',
        'no_faktur',
        'nama',
        'qty',
        'satuan',
        'harga',
        'pemasukan',
        'pengeluaran',
        'keterangan'
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
        'nota_id' => [
            'label' => 'Nota',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'no_faktur' => [
            'label' => 'No Faktur',
            'rules' => 'permit_empty|string|max_length[30]'
        ],
        'nama' => [
            'label' => 'Nama',
            'rules' => 'required|string|max_length[100]'
        ],
        'qty' => [
            'label' => 'Qty',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'satuan' => [
            'label' => 'Satuan',
            'rules' => 'permit_empty|string|max_length[10]'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'pemasukan' => [
            'label' => 'Pemasukan',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'pengeluaran' => [
            'label' => 'Pengeluaran',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('laporan_isi a')
            ->select('a.id, a.laporan_id, a.nota_id, a.no_faktur, a.nama, a.qty, a.satuan, a.harga, a.pemasukan, a.pengeluaran, a.keterangan, a.created_at, a.updated_at, b.tanggal as laporan_tanggal, c.no_nota as nota_no')
            ->join('laporan b', 'b.id = a.laporan_id', 'left')
            ->join('nota c', 'c.id = a.nota_id', 'left');
    }
}
