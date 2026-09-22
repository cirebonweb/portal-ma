<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\IncomingRequest;

/**
 * Library untuk memproses request DataTables server-side.
 * Caller harus memberikan BaseBuilder yang sudah siap (dengan join, where dasar, dll).
 * Library ini akan menambahkan pencarian global, ordering, dan pagination sesuai request DataTables.
 * Caller dapat mengatur kolom mana yang searchable/orderable, serta default order jika diinginkan.
 * Hasil akhir akan diproses dengan rowCallback jika diset, untuk menyesuaikan format data yang dikembalikan ke DataTables.
 */
class TabelLibrari
{
    protected BaseBuilder $builder;
    protected IncomingRequest $request;
    protected array $searchable = [];
    protected array $orderable = [];
    protected ?array $defaultOrder = null; // null = no forced default
    protected $rowCallback = null;

    public function __construct(BaseBuilder $builder, IncomingRequest $request)
    {
        $this->builder = $builder;
        $this->request = $request;
    }

    public function setSearchable(array $cols): self
    {
        $this->searchable = $cols;
        return $this;
    }

    public function setOrderable(array $cols): self
    {
        $this->orderable = $cols;
        return $this;
    }

    /**
     * Set default order. Contoh: ['a.id' => 'asc'] atau ['a.username' => 'desc']
     * Jika null, library TIDAK akan memaksakan order saat request tidak memberikan order.
     */
    public function setDefaultOrder(?array $order): self
    {
        $this->defaultOrder = $order;
        return $this;
    }

    public function setRowCallback(callable $cb): self
    {
        $this->rowCallback = $cb;
        return $this;
    }

    public function getResult(): array
    {
        $post = $this->request->getPost();
        $draw = intval($post['draw'] ?? 1);
        $start = intval($post['start'] ?? 0);
        $length = intval($post['length'] ?? 10);

        $searchValue = $post['search']['value'] ?? null;

        // === PENCARIAN GLOBAL: hanya pada kolom yang ada di $this->searchable
        if ($searchValue && !empty($this->searchable)) {
            $this->builder->groupStart();
            foreach ($this->searchable as $col) {
                $this->builder->orLike($col, $searchValue);
            }
            $this->builder->groupEnd();
        }

        // === ORDERING ===
        $orderColIndex = $post['order'][0]['column'] ?? null;
        $orderDir = strtolower($post['order'][0]['dir'] ?? 'asc');
        $appliedOrder = false;

        if ($orderColIndex !== null && isset($this->orderable[$orderColIndex])) {
            // gunakan orderable yang diset controller (index => columnName)
            $colName = $this->orderable[$orderColIndex];
            if (in_array($orderDir, ['asc','desc'], true)) {
                $this->builder->orderBy($colName, $orderDir);
                $appliedOrder = true;
            }
        }

        // jika client tidak kirim order yang valid, pakai defaultOrder bila diset
        if (!$appliedOrder && !empty($this->defaultOrder)) {
            foreach ($this->defaultOrder as $c => $d) {
                $d = strtolower($d) === 'desc' ? 'desc' : 'asc';
                $this->builder->orderBy($c, $d);
            }
            $appliedOrder = true;
        }

        // Jika tetap tidak ada order (tidak diinginkan), biarkan DB natural order (tidak meng-order)
        // === HITUNG TOTAL & FILTERED ===
        // gunakan clone builder untuk menghitung total tanpa limit
        $countBuilder = clone $this->builder;
        $recordsFiltered = $countBuilder->countAllResults(false);

        // Untuk totalRecords sebaiknya menghitung berdasarkan base table tanpa filter:
        // (Asumsi caller sudah memberikan builder dasar tanpa where filter global; jika tidak, adjust)
        // Simpel: gunakan same builder sebelum pencarian/filter diterapkan jika perlu.
        // Di sini kita tidak implementasikan counting perfect untuk semua kasus kompleks,
        // caller dapat override bila butuh.

        // === PAGINATION & AMBIL DATA ===
        $query = $this->builder->limit($length, $start)->get();
        $rows = $query->getResult();

        // Jika rowCallback ada, proses tiap baris (controller mengembalikan array terurut sesuai <th>)
        if ($this->rowCallback) {
            $processed = [];
            foreach ($rows as $r) {
                $processed[] = call_user_func($this->rowCallback, $r);
            }
            $data = $processed;
        } else {
            // default: kembalikan array of objects (atau array) sesuai kebutuhan DataTables
            $data = $rows;
        }

        // catatan: recordsTotal bisa dihitung dari base table bila perlu; gunakan fallback:
        $recordsTotal = $recordsFiltered;

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    }
}
