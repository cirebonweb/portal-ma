<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanModel extends Model
{
    protected $table            = 'bahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // bahan_order_isi, bahan_stok, bahan_sisa, bahan_limbah
    protected $protectFields    = true;
    protected $allowedFields = [
        'bahan_jenis_id',
        'kode',
        'nama',
        'lebar',
        'panjang',
        'isi_paket',
        'satuan_1',
        'satuan_2'
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
        'bahan_jenis_id' => [
            'label' => 'Jenis Bahan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'kode' => [
            'label' => 'Kode Bahan',
            'rules' => 'required|string|min_length[2]|max_length[30]'
        ],
        'nama' => [
            'label' => 'Nama Bahan',
            'rules' => 'required|string|min_length[3]|max_length[100]|is_unique[bahan.nama,id,{id}]'
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
            ->select('a.id, a.kode, a.nama as nama_bahan, a.lebar, a.panjang, a.isi_paket, a.satuan_1, a.satuan_2, a.created_at, a.updated_at, b.gsm, b.rumus, c.nama as tipe_mesin')
            ->join('bahan_jenis b', 'b.id = a.bahan_jenis_id', 'left')
            ->join('mesin_tipe c', 'c.id = b.mesin_tipe_id', 'left');
    }

    /**
     * Custom getId untuk mendapatkan mesin_tipe_id.
     * @param mixed $id
     */
    public function getId($id)
    {
        return $this
            ->select('bahan.*, bahan_jenis.mesin_tipe_id')
            ->join('bahan_jenis', 'bahan_jenis.id = bahan.bahan_jenis_id', 'left')
            ->find($id);
    }

    /**
     * Mendapatkan daftar nama bahan untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama, satuan_2')->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Mendapatkan data bahan berdasarkan filter mesin_tipe_id (lewat bahan_jenis).
     * Hanya kategori 0 (bahan produksi) yang dibutuhkan operator/purchasing.
     * @param mixed $id
     */
    public function getBahanMesin($id)
    {
        return $this
            ->select('bahan.id, bahan.kode, bahan.nama, bahan.lebar, bahan.panjang, bahan.isi_paket, bahan.satuan_1, bahan.satuan_2, bahan_jenis.rumus')
            ->join('bahan_jenis', 'bahan_jenis.id = bahan.bahan_jenis_id', 'inner')
            ->where('bahan_jenis.mesin_tipe_id', $id)
            ->where('bahan_jenis.kategori', 0)
            ->orderBy('bahan.nama', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan seluruh material (bahan_jenis.kategori = 1) untuk order bahan.
     */
    public function getMaterial()
    {
        return $this
            ->select('bahan.id, bahan.kode, bahan.nama, bahan.lebar, bahan.panjang, bahan.isi_paket, bahan.satuan_1, bahan.satuan_2, bahan_jenis.rumus')
            ->join('bahan_jenis', 'bahan_jenis.id = bahan.bahan_jenis_id', 'inner')
            ->where('bahan_jenis.kategori', 1)
            ->orderBy('bahan.nama', 'ASC')
            ->findAll();
    }
}
