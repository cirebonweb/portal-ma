<?php

namespace App\Controllers\Bahan;

use App\Controllers\BaseController;
use App\Models\BahanModel;
use App\Models\BahanJenisModel;
use App\Models\MesinTipeModel;
use App\Controllers\Traits\CrudTrait;
use CodeIgniter\Database\BaseBuilder;

class Bahan extends BaseController
{
    use CrudTrait {
        simpan as private simpanCrud;
    }

    protected BahanModel $model;
    protected BahanJenisModel $bahanJenisModel;
    protected MesinTipeModel $mesinTipeModel;
    protected $searchable = ['a.kode', 'a.nama'];
    protected $orderable  = ['a.id', 'c.nama', 'b.kategori', 'a.kode', 'a.nama'];

    public function __construct()
    {
        $this->model = new BahanModel();
        $this->bahanJenisModel = new BahanJenisModel();
        $this->mesinTipeModel = new MesinTipeModel();
        helper('satuan');
        helper('format');
    }

    public function index(): string
    {
        return view('bahan/bahan', [
            'pageTitle'     => 'Bahan Cetak & Material Produksi',
            'navigasi'      => '<a href="/bahan">Bahan</a> &nbsp;',
            'menuMesinTipe' => $this->mesinTipeModel->getTipeMesin(),
            'menuSatuan'    => getSatuan()
        ]);
    }

    protected function filterTabel(BaseBuilder $builder): BaseBuilder
    {
        $builder = $this->model->tabel();

        $filterTipe = $this->request->getPost('filter_tipe');
        if (!empty($filterTipe)) {
            $builder->where('b.mesin_tipe_id', $filterTipe);
        }

        $filterKategori = $this->request->getPost('filter_kategori');
        if (!empty($filterKategori)) {
            $builder->where('b.kategori', $filterKategori);
        }

        return $builder;
    }

    protected function dataTabel(\stdClass $row): array
    {
        $aksi = '<div class="btn-group" role="group">';
        $aksi .= '<button class="btn btn-sm btn-dark" type="button" onclick="simpan(' . $row->id . ')">edit</button>';
        $aksi .= '<button class="btn btn-sm btn-danger" type="button" onclick="hapus(' . $row->id . ')">hapus</button>';
        $aksi .= '</div>';

        return [
            $row->id,
            $row->tipe_mesin,
            $row->kategori ? 'Material' : 'Bahan',
            $row->kode,
            $row->nama_bahan,
            (int) $row->gsm > 0 ? $row->gsm . ' gsm' : '-',
            formatDesimal($row->lebar) . ' x ' . formatDesimal($row->panjang) . ' m',
            '1 ' . $row->satuan_2 . ' = ' .  formatDesimal($row->isi_paket) . ' ' . $row->satuan_1,
            (int) $row->rumus ? 'Perkalian Qty' : 'Perkalian Luas',
            $aksi,
            $row->created_at,
            $row->updated_at
        ];
    }

    public function getId()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $id = $this->request->getPost('id');
        if (!$id || !is_numeric($id)) {
            return $this->json(false, 'ID tidak valid', null, 400);
        }

        $data = $this->model->getId($id);
        if (!$data) {
            return $this->json(false, 'Data tidak ditemukan', null, 404);
        }

        return $this->json(true, null, $data);
    }

    public function getBahanJenis()
    {
        $kategori = $this->request->getPost('kategori');
        if (!is_numeric($kategori) || !in_array((int) $kategori, [0, 1], true)) {
            return $this->response->setJSON([]);
        }

        $mesinTipeId = null;
        if ((int) $kategori === 0) {
            $mesinTipeId = $this->request->getPost('mesinTipeId');
            if (!$mesinTipeId || !is_numeric($mesinTipeId)) {
                return $this->response->setJSON([]);
            }
            $mesinTipeId = (int) $mesinTipeId;
        }

        return $this->response->setJSON(
            $this->bahanJenisModel->getForBahan((int) $kategori, $mesinTipeId)
        );
    }

    public function simpan()
    {
        if ($res = $this->ajax()) {
            return $res;
        }

        $kategori = $this->request->getPost('kategori');
        $bahanJenisId = $this->request->getPost('bahan_jenis_id');

        if (!in_array($kategori, ['0', '1'], true)
            || !ctype_digit((string) $bahanJenisId) || (int) $bahanJenisId < 1) {
            return $this->json(false, 'Kategori dan jenis bahan wajib dipilih.');
        }

        $bahanJenis = $this->bahanJenisModel->find((int) $bahanJenisId);
        if (!$bahanJenis || (int) $bahanJenis->kategori !== (int) $kategori) {
            return $this->json(false, 'Jenis bahan tidak sesuai dengan kategori yang dipilih.');
        }

        $mesinTipeId = $this->request->getPost('mesin_tipe_id');
        if ((int) $kategori === 0
            && (!ctype_digit((string) $mesinTipeId) || (int) $bahanJenis->mesin_tipe_id !== (int) $mesinTipeId)) {
            return $this->json(false, 'Jenis bahan tidak sesuai dengan tipe mesin yang dipilih.');
        }

        if ((int) $kategori === 1 && $bahanJenis->mesin_tipe_id !== null) {
            return $this->json(false, 'Jenis material tidak boleh terikat pada tipe mesin.');
        }

        return $this->simpanCrud();
    }

    protected function dataSimpan(): array
    {
        return [
            'id'             => $this->request->getPost('id'),
            'bahan_jenis_id' => $this->request->getPost('bahan_jenis_id'),
            'kode'           => $this->request->getPost('kode'),
            'nama'           => $this->request->getPost('nama'),
            'lebar'          => $this->request->getPost('lebar'),
            'panjang'        => $this->request->getPost('panjang'),
            'satuan_1'       => $this->request->getPost('satuan_1'),
            'satuan_2'       => $this->request->getPost('satuan_2'),
            'isi_paket'      => $this->request->getPost('isi_paket')
        ];
    }
}
