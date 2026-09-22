<?php

namespace App\Models;

use CodeIgniter\Model;

class KonsumenModel extends Model
{
    protected $table            = 'konsumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false; // FK RESTRICT → harga_khusus, nota
    protected $protectFields    = true;
    protected $allowedFields = [
        'konsumen_tipe_id',
        'user_id',
        'nama',
        'perusahaan',
        'alamat',
        'kota',
        'whatsapp',
        'telegram_id',
        'email',
        'divisi',
        'status'
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
        'konsumen_tipe_id' => [
            'label' => 'Tipe Konsumen',
            'rules' => 'required|is_natural_no_zero'
        ],
        'user_id' => [
            'label' => 'User ID',
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
        'whatsapp' => [
            'label' => 'Nomor WhatsApp',
            'rules' => 'permit_empty|string|max_length[20]'
        ],
        'telegram_id' => [
            'label' => 'Telegram ID',
            'rules' => 'permit_empty|string|max_length[20]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email|max_length[100]'
        ],
        'divisi' => [
            'label' => 'Divisi',
            'rules' => 'permit_empty|in_list[0,1,2]' // 0:Umum, 1:Printing, 2:Advertising
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('konsumen a')
            ->select('a.id, a.nama as nama_konsumen, a.perusahaan, a.alamat, a.kota, a.whatsapp, a.telegram_id, a.email, a.created_at, a.updated_at, b.nama as tipe_konsumen')
            ->join('konsumen_tipe b', 'b.id = a.konsumen_tipe_id', 'left');
    }

    /**
     * Mendapatkan daftar nama konsumen untuk dropdown menu.
     */
    public function getDropdown()
    {
        return $this->select('id, nama')->orderBy('nama', 'ASC')->findAll();
    }
}
