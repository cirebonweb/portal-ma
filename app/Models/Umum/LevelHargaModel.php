<?php

namespace App\Models\Umum;

use CodeIgniter\Model;

class LevelHargaModel extends Model
{
    protected $table            = 'level_harga';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama'];

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
        'id' => ['label' => 'ID', 'rules' => 'permit_empty|numeric'],
        'nama' => ['label' => 'Nama Level Harga', 'rules' => 'required|max_length[10]|is_unique[level_harga.nama,id,{id}]'],
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
		return $this->db->table('level_harga')->select('*');
	}
}
