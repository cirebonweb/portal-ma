<?php

namespace App\Models\Umum;

use CodeIgniter\Model;

class KonsumenModel extends Model
{
    protected $table            = 'konsumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'kategori_konsumen_id',
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
        'kategori_konsumen_id' => [
            'label' => 'Kategori Konsumen',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'user_id' => [
            'label' => 'User ID',
            'rules' => 'permit_empty|is_natural_no_zero'
        ],
        'nama' => [
            'label' => 'Nama Konsumen',
            'rules' => 'required|string|max_length[40]'
        ],
        'perusahaan' => [
            'label' => 'Nama Perusahaan',
            'rules' => 'permit_empty|string|max_length[40]'
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
            'rules' => 'permit_empty|in_list[0,1,2]'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'required|in_list[0,1]'
        ]
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Query dasar untuk server-side dataTabel.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('konsumen a')
            ->select('a.id, a.nama, a.perusahaan, a.alamat, a.kota, a.whatsapp, a.telegram_id, a.email, a.divisi, a.status, b.nama as kategori, a.created_at, a.updated_at')
            ->join('kategori_konsumen b', 'b.id = a.kategori_konsumen_id', 'left');
    }
}
