<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpHargaKhususModel extends Model
{
    protected $table            = 'dp_harga_khusus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // baris mapping harga, gampang dibuat ulang, tidak vital dipertahankan history

    protected $allowedFields = [
        'konsumen_id',
        'dp_produk_id',
        'harga',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi server-side
    // Catatan: kombinasi [konsumen_id, dp_produk_id] wajib unik (lihat migration),
    // tapi rule bawaan is_unique CI4 hanya berlaku 1 kolom. Cek kombinasi lewat isDuplicate()
    // di Controller sebelum insert/update.
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'konsumen_id' => [
            'label' => 'Konsumen',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_produk_id' => [
            'label' => 'Produk',
            'rules' => 'required|is_natural_no_zero'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'required|is_natural'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * Join ke konsumen & dp_produk untuk tampilan nama.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_harga_khusus a')
            ->select('a.id, b.nama as konsumen_nama, c.nama as produk_nama, a.harga, a.created_at, a.updated_at')
            ->join('konsumen b', 'b.id = a.konsumen_id', 'left')
            ->join('dp_produk c', 'c.id = a.dp_produk_id', 'left');
    }

    /**
     * Cek apakah kombinasi konsumen_id + dp_produk_id sudah ada.
     * Panggil dari Controller sebelum insert/update karena is_unique bawaan
     * CI4 tidak mendukung validasi kombinasi 2 kolom sekaligus.
     * $ignoreId diisi id baris sendiri saat mode update, supaya tidak dianggap duplikat dirinya sendiri.
     */
    public function isDuplicate(int $konsumenId, int $dpProdukId, ?int $ignoreId = null): bool
    {
        $builder = $this->where('konsumen_id', $konsumenId)
            ->where('dp_produk_id', $dpProdukId);

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Ambil harga khusus untuk kombinasi konsumen + produk tertentu.
     * Berguna dipanggil dari service/logika penentuan harga efektif nota
     * (prioritas tertinggi sebelum harga kategori/promo/dasar).
     */
    public function getHarga(int $konsumenId, int $dpProdukId)
    {
        return $this->where('konsumen_id', $konsumenId)
            ->where('dp_produk_id', $dpProdukId)
            ->first();
    }
}
