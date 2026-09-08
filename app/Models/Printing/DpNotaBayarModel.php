<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpNotaBayarModel extends Model
{
    protected $table            = 'dp_nota_bayar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // data pembayaran sensitif, penting untuk rekonsiliasi kas

    protected $allowedFields = [
        'user_id',
        'dp_nota_id',
        'tanggal',
        'metode',
        'bukti_transfer',
        'diterima',
        'jumlah',
        'kembalian',
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
        'dp_nota_id' => [
            'label' => 'Nomor Nota',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tanggal' => [
            'label' => 'Tanggal',
            'rules' => 'required|valid_date[Y-m-d]'
        ],
        'metode' => [
            'label' => 'Metode Pembayaran',
            // 0:Tunai, 1:Transfer
            'rules' => 'permit_empty|in_list[0,1]'
        ],
        'bukti_transfer' => [
            'label' => 'Bukti Transfer',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
        'diterima' => [
            'label' => 'Diterima',
            'rules' => 'permit_empty|is_natural'
        ],
        'jumlah' => [
            'label' => 'Jumlah',
            'rules' => 'required|is_natural'
        ],
        'kembalian' => [
            'label' => 'Kembalian',
            'rules' => 'permit_empty|is_natural'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * Join ke dp_nota & users untuk tampilan nomor nota dan nama kasir.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('dp_nota_bayar a')
            ->select('a.id, a.tanggal, b.nota, a.metode, a.jumlah, c.username, a.created_at')
            ->join('dp_nota b', 'b.id = a.dp_nota_id', 'left')
            ->join('users c', 'c.id = a.user_id', 'left');
    }

    /**
     * Total seluruh pembayaran (yang belum dihapus) untuk satu nota.
     * Dipakai bersama DpNotaModel::tentukanStatusNota() untuk menentukan
     * status_nota & menghitung sisa tagihan.
     */
    public function getTotalBayar(int $dpNotaId): int
    {
        $hasil = $this->where('dp_nota_id', $dpNotaId)->selectSum('jumlah')->first();

        return (int) ($hasil->jumlah ?? 0);
    }
}
