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
    protected $allowedFields    = [
        'level_harga_id',
        'nama',
        'perusahaan',
        'alamat',
        'kota',
        'whatsapp',
        'telegram',
        'email',
        'divisi'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = false;

    // Validation
    protected $validationRules      = [
        'id' => [
            'label' => 'ID',
            'rules' => 'permit_empty|numeric'
        ],
        'level_harga_id' => [
            'label' => 'ID Level Harga',
            'rules' => 'permit_empty|numeric'
        ],
        'nama' => [
            'label' => 'Nama Konsumen',
            'rules' => 'required|max_length[40]|is_unique[konsumen.nama,id,{id}]'
        ],
        'perusahaan' => [
            'label' => 'Perusahaan',
            'rules' => 'permit_empty|max_length[40]'
        ],
        'alamat' => [
            'label' => 'Alamat',
            'rules' => 'permit_empty|max_length[100]'
        ],
        'kota' => [
            'label' => 'Kota',
            'rules' => 'permit_empty|max_length[20]'
        ],
        'whatsapp' => [
            'label' => 'Whatsapp',
            'rules' => 'permit_empty|max_length[20]'
        ],
        'telegram' => [
            'label' => 'ID Telegram',
            'rules' => 'permit_empty|max_length[20]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|max_length[100]|valid_email'
        ],
        'divisi' => [
            'label' => 'Divisi',
            'rules' => 'required|numeric'
        ]
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Query dasar untuk server-side dataTabel.
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    public function tabel()
    {
        return $this->db->table('konsumen a')
            ->select('a.id, a.nama, a.perusahaan, a.alamat, a.kota, a.whatsapp, a.telegram, a.email, 
            a.divisi, a.created_at, a.updated_at, b.nama nama_level')
            ->join('level_harga b', 'b.id = a.level_harga_id', 'left');
    }
}
