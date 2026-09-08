<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpLaporanModel extends Model
{
    protected $table            = 'dp_laporan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // dokumen resmi serah terima, wajib ada jejak

    protected $allowedFields = [
        'user_id',
        'tanggal',
        'pemasukan',
        'pengeluaran',
        'pendapatan',
        'tunai',
        'transfer',
        'diserahkan_id',
        'diterima_id',
        'diketahui_id',
        'status',
        'locked_at',
        'locked_by',
        'print_at',
        'keterangan',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validasi server-side
    // Catatan: kombinasi [user_id, tanggal] wajib unik (satu user hanya 1 laporan/hari) —
    // is_unique bawaan CI4 hanya 1 kolom, cek kombinasi lewat isDuplicate() di Controller.
    protected $validationRules = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'user_id' => [
            'label' => 'Pembuat Laporan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date[Y-m-d]'
        ],
        'pemasukan' => [
            'label' => 'Pemasukan',
            'rules' => 'permit_empty|is_natural'
        ],
        'pengeluaran' => [
            'label' => 'Pengeluaran',
            'rules' => 'permit_empty|is_natural'
        ],
        'pendapatan' => [
            'label' => 'Pendapatan',
            'rules' => 'permit_empty|integer'
        ],
        'tunai' => [
            'label' => 'Tunai',
            'rules' => 'permit_empty|is_natural'
        ],
        'transfer' => [
            'label' => 'Transfer',
            'rules' => 'permit_empty|is_natural'
        ],
        'diserahkan_id' => [
            'label' => 'Diserahkan Oleh',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'diterima_id' => [
            'label' => 'Diterima Oleh',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'diketahui_id' => [
            'label' => 'Diketahui Oleh',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'status' => [
            'label' => 'Status Laporan',
            'rules' => 'permit_empty|in_list[0,1,2,3]'
        ],
        'locked_at' => [
            'label' => 'Waktu Dikunci',
            'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]'
        ],
        'locked_by' => [
            'label' => 'Pengunci Laporan',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'print_at' => [
            'label' => 'Waktu Cetak Terakhir',
            'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]'
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
     * Join 4x ke users (pembuat, diserahkan, diterima, diketahui) —
     * AS wajib di sini karena kolom username muncul lebih dari sekali.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_laporan a')
            ->select('a.id, a.tanggal, b.username as pembuat, a.pemasukan, a.pengeluaran, a.pendapatan, a.tunai, a.transfer, c.username as diserahkan, d.username as diterima, e.username as diketahui, a.status, a.locked_at, a.locked_by, a.print_at, a.created_at')
            ->join('users b', 'b.id = a.user_id', 'left')
            ->join('users c', 'c.id = a.diserahkan_id', 'left')
            ->join('users d', 'd.id = a.diterima_id', 'left')
            ->join('users e', 'e.id = a.diketahui_id', 'left');
    }

    /**
     * Cek apakah user_id sudah punya laporan di tanggal tersebut.
     * Panggil dari Controller sebelum insert/update karena is_unique bawaan
     * CI4 tidak mendukung validasi kombinasi 2 kolom sekaligus.
     */
    public function isDuplicate(int $userId, string $tanggal, ?int $ignoreId = null): bool
    {
        $builder = $this->where('user_id', $userId)
            ->where('tanggal', $tanggal);

        if ($ignoreId !== null) {
            $builder->where('id !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
    * Cek apakah laporan sudah masuk proses serah terima.
     * Dipakai untuk mengunci laporan dari edit/hapus setelah proses serah terima
     * berjalan, karena dokumen ini harus tetap beku sebagai bukti resmi.
     */
    public function sudahDiserahkan(int $id): bool
    {
        $row = $this->find($id);

        return $row !== null && in_array((int) $row->status, [1, 2], true);
    }
}
