<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → bahan_order, bahan_stok
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama',
        'perusahaan',
        'alamat',
        'kota',
        'kontak',
        'email'
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
        'nama' => [
            'label' => 'Nama Konsumen',
            'rules' => 'required|string|min_length[3]|max_length[30]'
        ],
        'perusahaan' => [
            'label' => 'Nama Perusahaan',
            'rules' => 'permit_empty|string|min_length[3]|max_length[30]'
        ],
        'alamat' => [
            'label' => 'Alamat',
            'rules' => 'permit_empty|string|max_length[100]'
        ],
        'kota' => [
            'label' => 'Kota',
            'rules' => 'required|string|max_length[20]'
        ],
        'kontak' => [
            'label' => 'Nomor Kontak',
            'rules' => 'permit_empty|string|max_length[20]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email|max_length[100]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('supplier')
            ->select('id, nama, perusahaan, alamat, kota, kontak, email, created_at, updated_at');
    }

    /**
     * Mendapatkan daftar nama supplier untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this
            ->select('id, nama')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan detail informasi supplier untuk html.
     * @param mixed $id
     */
    public function getDetail($id)
    {
        return $this
            ->select('nama, perusahaan, alamat, kota, kontak, email')
            ->where('id', $id)
            ->first();
    }
}
