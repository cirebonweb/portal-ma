<?php

namespace App\Models;

use CodeIgniter\Model;

class NotaModel extends Model
{
    protected $table            = 'nota';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'konsumen_id',
        'tgl_nota',
        'no_nota',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'nettotal',
        'bayar',
        'sisa',
        'status_nota',
        'status_barang',
        'tgl_ambil',
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
        'konsumen_id' => [
            'label' => 'Konsumen',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tgl_nota' => [
            'label' => 'Tanggal Nota',
            'rules' => 'required|valid_date'
        ],
        'no_nota' => [
            'label' => 'Nomor Nota',
            'rules' => 'required|string|max_length[30]|is_unique[nota.no_nota,id,{id}]'
        ],
        'subtotal' => [
            'label' => 'Subtotal',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'diskon_persen' => [
            'label' => 'Diskon Persen',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'diskon_nominal' => [
            'label' => 'Diskon Nominal',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'nettotal' => [
            'label' => 'Nettotal',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'bayar' => [
            'label' => 'Bayar',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'sisa' => [
            'label' => 'Sisa',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'status_nota' => [
            'label' => 'Status Nota',
            'rules' => 'permit_empty|in_list[0,1,2,3]'
        ],
        'status_barang' => [
            'label' => 'Status Barang',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'tgl_ambil' => [
            'label' => 'Tanggal Ambil',
            'rules' => 'permit_empty|valid_date'
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
        return $this->db->table('nota a')
            ->select('a.id, a.konsumen_id, a.tgl_nota, a.no_nota, a.subtotal, a.diskon_persen, a.diskon_nominal, a.nettotal, a.bayar, a.sisa, a.status_nota, a.status_barang, a.tgl_ambil, a.keterangan, a.user_buat, a.user_ubah, a.created_at, a.updated_at, b.nama as konsumen, c.username as user_buat_nama, d.username as user_ubah_nama')
            ->join('konsumen b', 'b.id = a.konsumen_id', 'left')
            ->join('users c', 'c.id = a.user_buat', 'left')
            ->join('users d', 'd.id = a.user_ubah', 'left');
    }
}
