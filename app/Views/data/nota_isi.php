<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= versi('plugin/jquery/jquery-ui.min.css') ?>">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<style>
    @media (max-width: 600px) {
        #tabelData tfoot th.footer-extra {
            display: none !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <!-- Data Nota -->
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title text-white">Data Nota</h3>
                        <button type="button" class="btn btn-sm btn-light float-right" onclick="editNotaHeader(notaId)">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">No. Nota</b>
                                <span class="flex-grow-1">: <span class="infoNoNota"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Tgl. Nota</b>
                                <span class="flex-grow-1">: <span class="infoTglNota"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Konsumen</b>
                                <span class="flex-grow-1">: <span class="infoKonsumen"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Status Nota</b>
                                <span class="flex-grow-1">: <span class="infoStatusNota"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Status Barang</b>
                                <span class="flex-grow-1">: <span class="infoStatusBarang"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Tgl. Ambil</b>
                                <span class="flex-grow-1">: <span class="infoTglAmbil"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Keterangan</b>
                                <span class="flex-grow-1">: <span class="infoKeterangan"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Dibuat Oleh</b>
                                <span class="flex-grow-1">: <span class="infoUserBuat"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Diubah Oleh</b>
                                <span class="flex-grow-1">: <span class="infoUserUbah"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Tgl. Buat</b>
                                <span class="flex-grow-1">: <span class="infoTglBuat"></span></span>
                            </li>
                            <li class="list-group-item d-flex px-0">
                                <b class="col-4 col-md-5 px-0">Tgl. Ubah</b>
                                <span class="flex-grow-1">: <span class="infoTglUbah"></span></span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Rincian Nota -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-white">Rincian Nota</h3>
                    </div>
                    <div class="card-body py-4">
                        <table id="tabelData" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tema/Materi</th>
                                    <th>Ukuran</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="none">Produk</th>
                                    <th class="none">Finishing</th>
                                    <th class="none">Keterangan</th>
                                    <th class="none">Status Cetak</th>
                                    <th class="none">Dibuat</th>
                                    <th class="none">Diubah</th>
                                    <th class="no-export">Aksi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Subtotal</th>
                                    <th><span class="float-md-left">Rp</span> <span class="float-md-right rpSubtotal"></span></th>
                                    <th colspan="7" class="footer-extra"></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Diskon</th>
                                    <th><span class="float-md-left">Rp</span> <span class="float-md-right rpDiskon"></span></th>
                                    <th colspan="7" class="footer-extra"></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">NetTotal</th>
                                    <th><span class="float-md-left">Rp</span> <span class="float-md-right rpNetTotal"></span></th>
                                    <th colspan="7" class="footer-extra"></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Bayar</th>
                                    <th><span class="float-md-left">Rp</span> <span class="float-md-right rpBayar"></span></th>
                                    <th colspan="7" class="footer-extra"></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Sisa</th>
                                    <th><span class="float-md-left">Rp</span> <span class="float-md-right rpSisa"></span></th>
                                    <th colspan="7" class="footer-extra"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Pembayaran Nota -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-white">Pembayaran Nota</h3>
                    </div>
                    <div class="card-body py-4">
                        <table id="tabelBayar" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>No. Nota</th>
                                    <th>Tgl. Bayar</th>
                                    <th>Metode</th>
                                    <th>Diterima</th>
                                    <th>Jumlah</th>
                                    <th>Kembalian</th>
                                    <th class="none">User</th>
                                    <th class="none">Dibuat</th>
                                    <th class="none">Diubah</th>
                                    <th class="none no-export">Aksi</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?= $this->include('data/nota_isi_modal'); ?>
<?= $this->include('data/nota_header_modal'); ?>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const urlThis = '<?= site_url('nota/isi') ?>';
    const urlBayar = '<?= site_url('nota/bayar') ?>';
    const urlNota = '<?= site_url('nota') ?>';
    const notaId = <?= (int) $id ?>;
    const statusDraft = <?= (int) $statusDraft ?>;
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js" defer></script>
<script src="<?= versi('plugin/pica/pica.min.js') ?>" defer></script>
<script src="<?= versi('vendor/js/helper_upload_single.min.js') ?>" defer></script>
<script src="<?= versi('page/nota_isi.min.js') ?>" defer></script>
<script src="<?= versi('page/nota_bayar.min.js') ?>" defer></script>
<script src="<?= versi('page/nota_detail.min.js') ?>" defer></script>
<?= $this->endSection() ?>