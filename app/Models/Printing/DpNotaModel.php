<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpNotaModel extends Model
{
    protected $table            = 'dp_nota';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // data transaksi inti, wajib audit trail

    protected $allowedFields = [
        'user_id',
        'konsumen_id',
        'tanggal',
        'nota',
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
        'user_id' => [
            'label' => 'Nama User',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'konsumen_id' => [
            'label' => 'Nama Konsumen',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date[Y-m-d]'
        ],
        'nota' => [
            'label' => 'Nomor Nota',
            'rules' => 'required|string|max_length[30]|is_unique[dp_nota.nota,id,{id}]'
        ],
        'subtotal' => [
            'label' => 'Subtotal',
            'rules' => 'permit_empty|is_natural'
        ],
        'diskon_persen' => [
            'label' => 'Diskon Persen',
            'rules' => 'permit_empty|is_natural|less_than_equal_to[100]'
        ],
        'diskon_nominal' => [
            'label' => 'Diskon Nominal',
            'rules' => 'permit_empty|is_natural'
        ],
        'nettotal' => [
            'label' => 'Nettotal',
            'rules' => 'permit_empty|is_natural'
        ],
        'bayar' => [
            'label' => 'Bayar',
            'rules' => 'permit_empty|is_natural'
        ],
        'sisa' => [
            'label' => 'Sisa',
            'rules' => 'permit_empty|is_natural'
        ],
        'status_nota' => [
            'label' => 'Status Nota',
            'rules' => 'permit_empty|in_list[0,1,2,3,4]'
        ],
        'status_barang' => [
            'label' => 'Status Barang',
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'tgl_ambil' => [
            'label' => 'Tanggal Ambil',
            'rules' => 'permit_empty|valid_date[Y-m-d]'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * Join ke konsumen & users (Shield) untuk tampilan nama.
     * Catatan: kolom users.username mengikuti struktur default CI4 Shield —
     * sesuaikan jika struktur tabel users Anda berbeda.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_nota a')
            ->select('a.id, a.tanggal, a.nota, b.nama as konsumen_nama, a.nettotal, a.bayar, a.sisa, a.status_nota, a.status_barang, c.username, a.created_at')
            ->join('konsumen b', 'b.id = a.konsumen_id', 'left')
            ->join('users c', 'c.id = a.user_id', 'left');
    }

    /**
     * Cek apakah nota sudah memiliki riwayat pembayaran.
     * Tabel ini soft delete, jadi FK RESTRICT tidak menghalangi delete() biasa —
     * method ini untuk validasi bisnis: cegah hapus fisik nota yang sudah ada pembayaran,
     * arahkan pengguna memakai status_nota=3 (Hapus Nota / void) sebagai gantinya.
     */
    public function isUsed(int $id): bool
    {
        return $this->db->table('dp_nota_bayar')
            ->where('dp_nota_id', $id)
            ->countAllResults() > 0;
    }

    /**
     * Tentukan status_nota berdasarkan nettotal & total bayar.
     * Dipanggil dari Controller/Service setelah bayar diinput/diubah.
     * Tidak menimpa status 3 (Hapus Nota) atau 4 (Retur Nota) —
     * status tersebut hanya diubah eksplisit lewat aksi khusus, bukan otomatis.
     */
    public function tentukanStatusNota(int $nettotal, int $totalBayar): int
    {
        if ($totalBayar <= 0) {
            return 0; // Belum Bayar
        }

        if ($totalBayar < $nettotal) {
            return 1; // Belum Lunas
        }

        return 2; // Lunas
    }
}
