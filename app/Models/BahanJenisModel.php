<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanJenisModel extends Model
{
    protected $table            = 'bahan_jenis';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan, produk, material_stok
    protected $protectFields    = true;
    protected $allowedFields = [
        'mesin_tipe_id',
        'kategori',
        'kode',
        'nama',
        'gsm',
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
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'kategori' => [
            'label' => 'Kategori',
            'rules' => 'required|in_list[0,1]'
        ],
        'kode' => [
            'label' => 'Kode',
            'rules' => 'required|string|min_length[2]|max_length[20]'
        ],
        'nama' => [
            'label' => 'Nama Bahan',
            'rules' => 'required|string|min_length[3]|max_length[100]|is_unique[bahan_jenis.nama,id,{id}]'
        ],
        'gsm' => [
            'label' => 'Gramasi',
            'rules' => 'permit_empty|is_natural|less_than_equal_to[9999]'
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
        return $this->db->table('bahan_jenis a')
            ->select('a.id, a.mesin_tipe_id, a.kategori, a.kode, a.nama, a.gsm, a.rumus, a.created_at, a.updated_at, b.nama as tipe_mesin')
            ->join('mesin_tipe b', 'b.id = a.mesin_tipe_id', 'left');
    }

    /**
     * Mendapatkan daftar kategori bahan/material untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama, kode, rumus')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Dropdown bahan produksi saja (kategori = 0), dipakai master produk dan form bahan.
     */
    public function getBahanProduksi()
    {
        return $this->select('id, kode, nama, gsm, rumus')
            ->where('kategori', 0)
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Dropdown material saja (kategori = 1), dipakai master produk (paket harga).
     */
    public function getMaterial()
    {
        return $this->select('id, kode, nama')
            ->where('kategori', 1)
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan jenis bahan produksi berdasarkan filter mesin_tipe_id.
     * Material tidak diikutkan karena tidak terikat mesin.
     * @param mixed $id
     */
    public function getBahanMesin($id)
    {
        return $this
            ->select('id, kode, nama, gsm, rumus')
            ->where('mesin_tipe_id', $id)
            ->where('kategori', 0)
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan jenis bahan cetak berdasarkan tipe mesin atau seluruh jenis material.
     */
    public function getForBahan(int $kategori, ?int $mesinTipeId = null): array
    {
        if (!in_array($kategori, [0, 1], true) || ($kategori === 0 && $mesinTipeId === null)) {
            return [];
        }

        $builder = $this
            ->select('id, kode, nama, rumus')
            ->where('kategori', $kategori);

        if ($kategori === 0) {
            $builder->where('mesin_tipe_id', $mesinTipeId);
        } else {
            $builder->where('mesin_tipe_id', null);
        }

        return $builder->orderBy('nama', 'ASC')->findAll();
    }
}
