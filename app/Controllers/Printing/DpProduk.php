<?php

namespace App\Controllers\Printing;

use App\Controllers\BaseController;
use App\Models\Printing\DpProdukModel;
use App\Models\Printing\DpMesinModel;
use App\Models\Printing\DpBahanModel;
use App\Libraries\TabelLibrari;

class DpProduk extends BaseController
{
    /**
     * @var DpProdukModel
     */
    protected $dpProdukModel;

    /**
     * @var DpMesinModel
     */
    protected $dpMesinModel;

    /**
     * @var DpBahanModel
     */
    protected $dpBahanModel;

    public function __construct()
    {
        $this->dpProdukModel = new DpProdukModel();
        $this->dpMesinModel = new DpMesinModel();
        $this->dpBahanModel = new DpBahanModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Produk',
            'navigasi'  => '<a href="/printing">Produk</a> &nbsp;',
            'dpMesin'   => $this->dpMesinModel->getDropdown(),
            'dpBahan'   => $this->dpBahanModel->getDropdown()
        ];
        return view('printing/dp_produk', $data);
    }

    public function tabel()
    {
        $builder = $this->dpProdukModel->tabel();

        // Ajax filter dp_mesin_id
        $filterMesin = $this->request->getPost('filter_mesin');

        if ($filterMesin !== null && $filterMesin !== '') {
            $builder->where('dp_mesin_id', $filterMesin);
        }

        $dataTable = new TabelLibrari($builder, $this->request);
        $dataTable->setSearchable(['nama']);

        $dataTable->setRowCallback(function ($row) {
            // ukuran
            $lebar = rtrim(rtrim($row->lebar, '0'), '.');
            $panjang = rtrim(rtrim($row->panjang, '0'), '.');
            $ukuran = $lebar . ' x ' . $panjang . ' m';

            // status promo
            $hariIni = date('Y-m-d');

            if (empty($row->promo)) {
                $statusPromo = '<span class="lencana bg-secondary">Tidak Ada</span>';
            } elseif ($hariIni < $row->promo_awal) {
                $statusPromo = '<span class="lencana bg-info">Belum Mulai</span>';
            } elseif ($hariIni > $row->promo_akhir) {
                $statusPromo = '<span class="lencana bg-danger">Berakhir</span>';
            } else {
                $statusPromo = '<span class="lencana bg-success">Aktif</span>';
            }

            // aksi tombol
            $aksi = '<div class="btn-group" role="group">';
            $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
            $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
            $aksi .= '</div>';

            return [
                $row->id,
                $row->mesin,
                $row->bahan,
                $row->nama,
                $ukuran,
                $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
                $row->hpp,
                $row->harga,
                $row->promo,
                $statusPromo,
                $row->promo_awal,
                $row->promo_akhir,
                $row->created_at,
                $row->updated_at,
                $aksi,
            ];
        });

        return $this->response->setJSON($dataTable->getResult());
    }

    public function getId()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');

        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid', null, 400);
        }

        $data = $this->dpProdukModel->find($id);

        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function simpan()
    {
        if ($res = $this->ajax()) return $res;

        $data = [
            'id'          => $this->request->getPost('id'),
            'dp_mesin_id' => $this->request->getPost('dp_mesin_id'),
            'dp_bahan_id' => $this->request->getPost('dp_bahan_id'),
            'nama'        => $this->request->getPost('nama'),
            'lebar'       => $this->request->getPost('lebar'),
            'panjang'     => $this->request->getPost('panjang'),
            'hpp'         => $this->request->getPost('hpp'),
            'harga'       => $this->request->getPost('harga'),
            'promo'       => $this->request->getPost('promo'),
            'promo_awal'  => $this->request->getPost('promo_awal'),
            'promo_akhir' => $this->request->getPost('promo_akhir'),
            'rumus'       => $this->request->getPost('rumus')
        ];

        // Bersihkan input kosong jadi null
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        try {
            // save() sudah include validation
            if (! $this->dpProdukModel->save($data)) {
                return $this->json(false, $this->dpProdukModel->errors());
            }

            $pesan = empty($data['id']) ? lang("App.insert-success") : lang("App.update-success");

            return $this->json(true, $pesan);
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }

    public function hapus()
    {
        if ($res = $this->ajax()) return $res;

        $id = $this->request->getPost('id');

        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid');
        }

        try {
            if (! $this->dpProdukModel->find($id)) {
                return $this->json(false, 'Data tidak ditemukan');
            }

            if ($this->dpProdukModel->delete($id)) {
                return $this->json(true, lang("App.delete-success"));
            }

            return $this->json(false, lang("App.delete-error"));
        } catch (\Throwable $e) {
            log_message('critical', __METHOD__ . ': ' . $e->getMessage());
            return $this->json(false, 'Critical: ' . $e->getMessage());
        }
    }
}
