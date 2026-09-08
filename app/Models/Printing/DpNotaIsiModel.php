<?php

namespace App\Models\Printing;

use CodeIgniter\Model;

class DpNotaIsiModel extends Model
{
    protected $table            = 'dp_nota_isi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // baris item, dikelola manual saat edit nota; histori sudah terwakili di dp_nota

    protected $allowedFields = [
        'dp_nota_id',
        'dp_produk_id',
        'tema',
        'lebar',
        'panjang',
        'luas',
        'qty',
        'harga_satuan',
        'subtotal',
        'hpp',
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
        'dp_nota_id' => [
            'label' => 'Nomor Nota',
            'rules' => 'required|is_natural_no_zero'
        ],
        'dp_produk_id' => [
            'label' => 'Produk',
            'rules' => 'required|is_natural_no_zero'
        ],
        'tema' => [
            'label' => 'Tema',
            'rules' => 'required|string|max_length[100]'
        ],
        'lebar' => [
            'label' => 'Lebar',
            'rules' => 'permit_empty|decimal'
        ],
        'panjang' => [
            'label' => 'Panjang',
            'rules' => 'permit_empty|decimal'
        ],
        'luas' => [
            'label' => 'Luas',
            'rules' => 'permit_empty|decimal'
        ],
        'qty' => [
            'label' => 'Qty',
            'rules' => 'required|is_natural'
        ],
        'harga_satuan' => [
            'label' => 'Harga Satuan',
            'rules' => 'permit_empty|is_natural'
        ],
        'subtotal' => [
            'label' => 'Subtotal',
            'rules' => 'permit_empty|is_natural'
        ],
        'hpp' => [
            'label' => 'HPP',
            'rules' => 'permit_empty|is_natural'
        ],
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Ambil seluruh item untuk satu nota, join ke dp_produk untuk nama produk.
     * Dipakai saat menampilkan detail nota atau mencetak nota.
     */
    public function getByNota(int $dpNotaId)
    {
        return $this->select('a.id, a.dp_produk_id, b.nama, a.tema, a.lebar, a.panjang, a.luas, a.qty, a.harga_satuan, a.subtotal, a.hpp')
            ->from('dp_nota_isi a')
            ->join('dp_produk b', 'b.id = a.dp_produk_id', 'left')
            ->where('a.dp_nota_id', $dpNotaId)
            ->findAll();
    }
}
