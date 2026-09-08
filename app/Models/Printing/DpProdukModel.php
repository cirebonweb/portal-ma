<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpProdukModel extends Model
{
    protected $table            = 'dp_produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // histori harga penting, produk discontinued tetap dipakai nota lama

    protected $allowedFields = [
        'dp_mesin_id',
        'dp_bahan_id',
        'nama',
        'lebar',
        'panjang',
        'satuan',
        'hpp',
        'harga',
        'promo',
        'promo_awal',
        'promo_akhir',
        'rumus',
        'unggulan',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validasi server-side
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'dp_mesin_id' => [
            'label' => 'Nama Mesin',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_bahan_id' => [
            'label' => 'Nama Bahan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Produk',
            'rules' => 'required|string|max_length[100]|is_unique[dp_produk.nama,id,{id}]'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'permit_empty|decimal'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'permit_empty|decimal'
        ],
        'satuan' => [
            'label' => 'Satuan',
            'rules' => 'permit_empty|string|max_length[10]'
        ],
        'hpp' => [
            'label' => 'HPP',
            'rules' => 'permit_empty|is_natural'
        ],
        'harga' => [
            'label' => 'Harga Dasar',
            'rules' => 'permit_empty|is_natural'
        ],
        'promo' => [
            'label' => 'Harga Promo',
            'rules' => 'permit_empty|is_natural'
        ],
        'promo_awal' => [
            'label' => 'Tanggal Awal Promo',
            'rules' => 'permit_empty|valid_date[Y-m-d]'
        ],
        'promo_akhir' => [
            'label' => 'Tanggal Akhir Promo',
            'rules' => 'permit_empty|valid_date[Y-m-d]'
        ],
        'rumus' => [
            'label' => 'Rumus Perhitungan',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'unggulan' => [
            'label' => 'Produk Unggulan',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * Join ke dp_mesin & dp_bahan untuk tampilan nama, bukan hanya ID.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_produk a')
            ->select('a.id, a.nama, a.lebar, a.panjang, a.satuan, a.harga, a.promo, a.rumus, a.unggulan, b.nama as mesin_nama, c.nama as bahan_nama, a.created_at, a.updated_at')
            ->join('dp_mesin b', 'b.id = a.dp_mesin_id', 'left')
            ->join('dp_bahan c', 'c.id = a.dp_bahan_id', 'left');
    }

    /**
     * Cek apakah produk masih dipakai di transaksi nota atau aturan harga.
     * Tabel ini soft delete, jadi FK RESTRICT tidak akan menghalangi delete()
     * biasa — method ini untuk validasi bisnis, bukan pencegahan error FK.
     */
    public function isUsed(int $id): bool
    {
        $diNota = $this->db->table('dp_nota_isi')
            ->where('dp_produk_id', $id)
            ->countAllResults() > 0;

        $diKategoriHarga = $this->db->table('dp_kategori_harga')
            ->where('dp_produk_id', $id)
            ->countAllResults() > 0;

        $diHargaKhusus = $this->db->table('dp_harga_khusus')
            ->where('dp_produk_id', $id)
            ->countAllResults() > 0;

        return $diNota || $diKategoriHarga || $diHargaKhusus;
    }
}
