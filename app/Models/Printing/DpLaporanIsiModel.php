<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpLaporanIsiModel extends Model
{
    protected $table            = 'dp_laporan_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; // mengikuti induk (dp_laporan) untuk konsistensi audit

    protected $allowedFields = [
        'dp_laporan_id',
        'dp_nota_id',
        'nota',
        'nama',
        'qty',
        'satuan',
        'harga',
        'pemasukan',
        'pengeluaran',
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
        'dp_laporan_id' => [
            'label' => 'Laporan',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_nota_id' => [
            'label' => 'ID Nota',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'nota' => [
            'label' => 'Nomor Nota',
            'rules' => 'permit_empty|string|max_length[30]'
        ],
        'nama' => [
            'label' => 'Nama',
            'rules' => 'required|string|max_length[100]'
        ],
        'qty' => [
            'label' => 'Qty',
            'rules' => 'permit_empty|is_natural'
        ],
        'satuan' => [
            'label' => 'Satuan',
            'rules' => 'permit_empty|string|max_length[10]'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'permit_empty|is_natural'
        ],
        'pemasukan' => [
            'label' => 'Pemasukan',
            'rules' => 'permit_empty|is_natural'
        ],
        'pengeluaran' => [
            'label' => 'Pengeluaran',
            'rules' => 'permit_empty|is_natural'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'rules' => 'permit_empty|string|max_length[255]'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Ambil seluruh baris untuk satu laporan.
     * Dipakai saat menampilkan detail laporan atau mencetak laporan harian.
     */
    public function getByLaporan(int $dpLaporanId)
    {
        return $this->where('dp_laporan_id', $dpLaporanId)->findAll();
    }
}
