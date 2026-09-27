<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialStokModel extends Model
{
    protected $table            = 'material_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan_jenis
    protected $protectFields    = true;
    protected $allowedFields = [
        'bahan_jenis_id',
        'stok_masuk',
        'stok_pakai',
        'stok_sisa',
        'harga_satuan',
        'kondisi',
        'status',
        'keterangan'
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
            'label' => 'Material',
            'rules' => 'required|is_natural_no_zero'
        ],
        'stok_masuk' => [
            'label' => 'Stok Masuk',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'stok_pakai' => [
            'label' => 'Stok Pakai',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'stok_sisa' => [
            'label' => 'Stok Sisa',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[0]'
        ],
        'harga_satuan' => [
            'label' => 'Harga Satuan',
            'rules' => 'permit_empty|is_natural'
        ],
        'kondisi' => [
            'label' => 'Kondisi',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[100]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('material_stok a')
            ->select('a.id, a.bahan_jenis_id, a.stok_masuk, a.stok_pakai, a.stok_sisa, a.harga_satuan, a.kondisi, a.status, a.keterangan, a.created_at, a.updated_at, b.kode, b.nama, b.gsm, c.nama as tipe_mesin')
            ->join('bahan_jenis b', 'b.id = a.bahan_jenis_id', 'left')
            ->join('mesin_tipe c', 'c.id = b.mesin_tipe_id', 'left');
    }

    /**
     * Custom getId
     * @param mixed $id
     */
    public function getId($id)
    {
        return $this->db->table('material_stok a')
            ->select('a.id, a.bahan_jenis_id, a.stok_masuk, a.stok_pakai, a.stok_sisa, a.harga_satuan, a.kondisi, a.status, a.keterangan, b.kode, b.nama')
            ->join('bahan_jenis b', 'b.id = a.bahan_jenis_id', 'left')
            ->where('a.id', $id)
            ->get()->getRow();
    }

    /**
     * Menambah stok material secara agregat (satu baris per bahan_jenis).
     * Dipakai saat order bahan ditandai sudah masuk stok.
     *
     * @param mixed $bahanJenisId
     * @param mixed $qty
     * @param mixed $hargaSatuan
     */
    public function tambahMasuk($bahanJenisId, $qty, $hargaSatuan = 0)
    {
        $row = $this->db->table('material_stok')
            ->where('bahan_jenis_id', (int) $bahanJenisId)
            ->get()->getRow();

        if ($row === null) {
            return $this->insert([
                'bahan_jenis_id' => (int) $bahanJenisId,
                'stok_masuk'     => $qty,
                'stok_pakai'     => 0,
                'stok_sisa'      => $qty,
                'harga_satuan'   => (int) $hargaSatuan,
                'kondisi'        => 0,
                'status'         => $qty > 0 ? 0 : 2,
            ]);
        }

        $masuk = round((float) $row->stok_masuk + (float) $qty, 2);
        $sisa  = round($masuk - (float) $row->stok_pakai, 2);

        return $this->update((int) $row->id, [
            'stok_masuk'   => $masuk,
            'stok_sisa'    => $sisa,
            'harga_satuan' => (int) $hargaSatuan > 0 ? (int) $hargaSatuan : (int) $row->harga_satuan,
            'status'       => $sisa > 0 ? 0 : 2,
        ]);
    }
}
