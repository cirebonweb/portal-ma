<?php

namespace App\Controllers\Traits;

use App\Libraries\TabelLibrari;

trait CrudTrait
{
    /**
     * Menangani request DataTables server-side.
     * - Cek request POST dan AJAX.
     * - Bangun query builder (custom atau default).
     * - Terapkan filter dan kolom pencarian/pengurutan.
     * - Set callback untuk data row.
     * - Return hasil dalam format JSON.
     *
     * @return \CodeIgniter\HTTP\Response JSON hasil DataTables
     */
    public function tabel()
    {
        if ($this->request->is('post')) {
            if ($res = $this->ajax()) return $res;
        }

        $builder = method_exists($this, 'builderTabel') ? $this->builderTabel() : $this->model->builder();
        if (method_exists($this, 'filterTabel')) $builder = $this->filterTabel($builder);

        $dataTable = new TabelLibrari($builder, $this->request);
        $searchableCols = isset($this->searchable) ? $this->searchable : [];
        $dataTable->setSearchable($searchableCols);
        $orderableCols = isset($this->orderable) ? $this->orderable : [];
        $dataTable->setOrderable($orderableCols);

        if (method_exists($this, 'dataTabel')) {
            $dataTable->setRowCallback(function ($row) {
                return $this->dataTabel($row);
            });
        } else {
            $dataTable->setRowCallback(function ($row) {
                return array_values((array) $row);
            });
        }

        return $this->response->setJSON($dataTable->getResult());
    }

    /**
     * Mengambil data berdasarkan ID.
     * - Validasi input ID (harus numeric).
     * - Gunakan fungsi custom model (getIdCustom) jika ada.
     * - Jika tidak, pakai find($id) standar.
     * - Return JSON dengan data atau error.
     *
     * @return \CodeIgniter\HTTP\Response JSON data hasil pencarian
     */
    public function getId()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) return $this->json(false, 'ID tidak valid', null, 400);

        $data = method_exists($this->model, 'getIdCustom') ? $this->model->getIdCustom($id) : $this->model->find($id);
        if (!$data) return $this->json(false, 'Data tidak ditemukan', null, 404);
        return $this->json(true, null, $data);
    }

    /**
     * Menyimpan data (insert/update).
     * - Ambil data dari request atau fungsi custom (dataSimpan).
     * - Bersihkan string kosong menjadi null.
     * - Tentukan apakah insert atau update.
     * - Simpan data via model->save().
     * - Return JSON dengan pesan sukses/gagal.
     *
     * @return \CodeIgniter\HTTP\Response JSON hasil simpan
     */
    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = method_exists($this, 'dataSimpan') ? $this->dataSimpan()  : $this->request->getPost();
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        $isInsert = empty($data['id']);

        try {
            if (!$this->model->save($data)) return $this->json(false, $this->model->errors());

            $pesan = $isInsert ? lang("App.insert-success") : lang("App.update-success");

            $responseData = null;
            if (method_exists($this, 'dataSimpanResponse')) {
                $responseData = $this->dataSimpanResponse($isInsert, $data);
            }

            return $this->json(true, $pesan, $responseData);
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data berdasarkan ID.
     * - Validasi input ID.
     * - Cek apakah data ada.
     * - Hapus data via model->delete().
     * - Tangani error termasuk foreign key constraint.
     * - Return JSON dengan pesan sukses/gagal.
     *
     * @return \CodeIgniter\HTTP\Response JSON hasil hapus
     */
    public function hapus()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) return $this->json(false, 'ID tidak valid');

        try {
            if (!$this->model->find($id)) return $this->json(false, 'Data tidak ditemukan');
            if ($this->model->delete($id)) return $this->json(true, lang("App.delete-success"));
            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'foreign key constraint fails') !== false) {
                $pesanErrorFK = property_exists($this, 'fkErrorMessage') ? $this->fkErrorMessage : 'Data tidak dapat dihapus karena masih digunakan pada data/menu lain.';
                return $this->json(false, $pesanErrorFK);
            }
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
