<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/jquery/jquery-ui.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-white">Riwayat Pembayaran</h3>
                    </div>

                    <div class="card-body">
                        <table id="tabelBayar" class="table table-bordered table-hover dataTable dtr-inline">
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

                    <div class="card-footer">
                        <span class="text-muted">Pembayaran ditambahkan dari halaman detail nota (menu Data Nota &rarr; tombol detail).</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modalBayar" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formBayar" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="bayar_id" name="id">
                    <input type="hidden" id="bayar_nota_id" name="nota_id" value="0">
                    <div class="row">

                        <div class="col-6 mb-4">
                            <label for="bayar_tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center tanggal" id="bayar_tanggal" name="tanggal" readonly>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="bayar_metode">Metode <span class="text-danger">*</span></label>
                            <select id="bayar_metode" name="metode" class="form-control" required>
                                <option value="0">Tunai</option>
                                <option value="1">Transfer</option>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="bayar_diterima">Uang Diterima <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control text-right rupiah" id="bayar_diterima" name="diterima" value="0" maxlength="14" required>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="bayar_jumlah">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control text-right rupiah" id="bayar_jumlah" name="jumlah" value="0" maxlength="14" required>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="bayar_kembalian">Kembalian</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control text-right" id="bayar_kembalian" value="0" disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-dark mb-0">Kembalian = Uang Diterima − Jumlah Bayar (tidak boleh negatif)</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitBayar" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const bayarUrl = '<?= site_url('nota/bayar') ?>';
    const idNota = 0;
    const statusNota = 0;
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="<?= versi('page/nota_bayar.min.js') ?>" defer></script>
<?= $this->endSection() ?>
