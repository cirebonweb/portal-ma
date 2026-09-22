<?php

namespace App\Models;

use CodeIgniter\Model;

class NotaIsiModel extends Model
{
    protected $table            = 'nota_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nota_id',
        'produk_id',
        'finishing_id',
        'tema',
        'lebar',
        'panjang',
        'luas',
        'qty',
        'harga',
        'jumlah',
        'status',
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
        'nota_id' => [
            'label' => 'Nota',
            'rules' => 'required|is_natural_no_zero'
        ],
        'produk_id' => [
            'label' => 'Produk',
            'rules' => 'required|is_natural_no_zero'
        ],
        'finishing_id' => [
            'label' => 'Finishing',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'tema' => [
            'label' => 'Tema',
            'rules' => 'required|string|max_length[100]'
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
        'qty' => [
            'label' => 'Qty',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'jumlah' => [
            'label' => 'Jumlah',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1,2,3,4]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[100]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function tabel()
    {
        return $this->db->table('nota_isi a')
            ->select('a.id, a.nota_id, a.produk_id, a.finishing_id, a.tema, a.lebar, a.panjang, a.luas, a.qty, a.harga, a.jumlah, a.status, a.keterangan, a.created_at, a.updated_at, b.no_nota, c.nama as produk, d.nama as finishing')
            ->join('nota b', 'b.id = a.nota_id', 'left')
            ->join('produk c', 'c.id = a.produk_id', 'left')
            ->join('finishing d', 'd.id = a.finishing_id', 'left');
    }
}
