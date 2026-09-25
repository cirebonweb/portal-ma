<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\NotaModel;
use App\Models\KonsumenModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Nota extends BaseController
{
    use CrudTrait;

    protected NotaModel $model;
    protected KonsumenModel $konsumenModel;
    protected $searchable = ['nama'];
    protected $orderable  = ['id', 'nama', 'created_at', 'updated_at'];

    public function __construct()
    {
        $this->model = new NotaModel();
        $this->konsumenModel = new KonsumenModel();
    }

    public function index(): string
    {
        return view('data/nota', [
            'pageTitle'    => 'Nota Penjualan',
            'navigasi'     => '<a href="/data">Nota</a> &nbsp;',
            'menuKonsumen' => $this->konsumenModel->getDropdown(),
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterNota = $this->request->getPost('filter_nota');
        if ($filterNota !== null && $filterNota !== '') {
            $builder->where('a.status_nota', $filterNota);
        }

        $filterBarang = $this->request->getPost('filter_barang');
        if ($filterBarang !== null && $filterBarang !== '') {
            $builder->where('a.status_barang', $filterBarang);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $statusNota = ['Belum Bayar', 'Belum Lunas', 'Lunas', 'Hapus Nota'];

        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<a href="/nota/isi?edit=' . $row->id . '" class="btn btn-sm btn-primary">detail</a>';
        $aksi .= '</div>';

        return [
            $row->no_nota,
            $row->tgl_nota,
            $row->konsumen,
            $row->subtotal,
            $row->diskon_nominal,
            $row->nettotal,
            $row->bayar,
            $row->sisa,
            $statusNota[(int) $row->status_nota] ?? '-',
            $row->status_barang ? 'Sudah Ambil' : 'Belum Ambil',
            $row->tgl_ambil,
            $row->keterangan,
            $row->user_buat_nama ?: '-',
            $row->user_ubah_nama ?: '-',
            $row->created_at,
            $row->updated_at,
            $aksi
        ];
    }

    protected function dataSimpan(): array
    {
        $userId = auth()->user()?->id;

        return [
            'id'            => $this->request->getPost('id'),
            'konsumen_id'   => $this->request->getPost('konsumen_id'),
            'tgl_nota'      => $this->request->getPost('tgl_nota'),
            'status_barang' => $this->request->getPost('status_barang') ?: 0,
            'tgl_ambil'    => $this->request->getPost('tgl_ambil'),
            'keterangan'   => $this->request->getPost('keterangan'),
            'user_buat'    => $userId,
            'user_ubah'    => $userId,
        ];
    }

    public function simpan()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $data = $this->dataSimpan();
        $id = $data['id'];
        $db = db_connect();

        try {
            $db->transBegin();

            $konsumenId = $data['konsumen_id'];
            if (!is_numeric($konsumenId)) {
                $namaKonsumen = trim((string) $konsumenId);
                if ($namaKonsumen === '') {
                    throw new \RuntimeException('Nama konsumen wajib diisi.');
                }

                if (!$this->konsumenModel->insert([
                    'konsumen_tipe_id' => 1,
                    'nama'             => $namaKonsumen,
                    'kota'             => 'Cirebon',
                ])) {
                    throw new \RuntimeException(implode('; ', $this->konsumenModel->errors()));
                }
                $konsumenId = $this->konsumenModel->getInsertID();
            }

            $data['konsumen_id'] = (int) $konsumenId;
            $data['tgl_ambil'] = $data['tgl_ambil'] ?: null;
            $data['user_buat'] = $data['user_buat'] ?: null;
            $data['user_ubah'] = $data['user_ubah'] ?: null;

            if (empty($id)) {
                $lastNumber = $db->query(
                    'SELECT COALESCE(MAX(CAST(no_nota AS UNSIGNED)), 0) AS nomor FROM nota FOR UPDATE'
                )->getRow()->nomor;
                $data['no_nota'] = str_pad((string) ((int) $lastNumber + 1), 5, '0', STR_PAD_LEFT);
                $data['status_nota'] = 0;
                $data['subtotal'] = 0;
                $data['diskon_persen'] = 0;
                $data['diskon_nominal'] = 0;
                $data['nettotal'] = 0;
                $data['bayar'] = 0;
                $data['sisa'] = 0;
            } else {
                $existing = $this->model->find($id);
                if (!$existing) {
                    throw new \RuntimeException('Data nota tidak ditemukan.');
                }
                $data['id'] = (int) $id;
                $data = array_merge((array) $existing, $data);
                $data['user_buat'] = $existing->user_buat;
            }

            if (!$this->model->save($data)) {
                throw new \RuntimeException(implode('; ', $this->model->errors()));
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();
            return $this->json(
                true,
                empty($id) ? lang('App.insert-success') : lang('App.update-success'),
                empty($id) ? ['id' => $this->model->getInsertID()] : null
            );
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, $e->getMessage());
        }
    }
}
