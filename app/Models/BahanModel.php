<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanModel extends Model
{
    protected $table            = 'bahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'mesin_tipe_id',
        'nama',
        'kode',
        'gsm',
        'lebar',
        'panjang',
        'isi_paket',
        'satuan_1',
        'satuan_2',
        'rumus'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi server-side
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'mesin_tipe_id' => [
            'label' => 'Tipe Mesin',
            'rules' => 'required|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Bahan',
            'rules' => 'required|string|min_length[3]|max_length[30]|is_unique[bahan.nama,id,{id}]'
        ],
        'kode' => [
            'label' => 'Kode Bahan',
            'rules' => 'required|string|min_length[2]|max_length[5]'
        ],
        'gsm' => [
            'label' => 'Gramasi',
            'rules' => 'permit_empty|integer|max_length[3]'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'permit_empty|decimal|max_length[5]'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'permit_empty|decimal|max_length[5]'
        ],
        'isi_paket' => [
            'label' => 'Isi Paket',
            'rules' => 'permit_empty|decimal|max_length[7]'
        ],
        'satuan_1' => [
            'label' => 'Satuan Kecil',
            'rules' => 'permit_empty|string|max_length[10]'
        ],
        'satuan_2' => [
            'label' => 'Satuan Besar',
            'rules' => 'permit_empty|string|max_length[10]'
        ],
        'rumus' => [
            'label' => 'Rumus',
            'rules' => 'permit_empty|in_list[0,1]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('bahan a')
            ->select('a.id, a.nama as nama_bahan, a.kode, a.gsm, a.lebar, a.panjang, a.isi_paket, a.satuan_1, a.satuan_2, a.rumus, a.created_at, a.updated_at, b.nama as tipe_mesin')
            ->join('mesin_tipe b', 'b.id = a.mesin_tipe_id', 'left');
    }

    /**
     * Mendapatkan daftar nama bahan untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama, satuan_2')->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Mendapatkan data bahan berdasarkan filter mesin_tipe_id.
     * @param mixed $id
     */
    public function getBahanMesin($id)
    {
        return $this
        ->select('id, nama, kode, gsm, lebar, panjang, isi_paket, satuan_1, satuan_2, rumus')
        ->where('mesin_tipe_id', $id)
        ->orderBy('nama', 'ASC')
        ->findAll();
    }
}
