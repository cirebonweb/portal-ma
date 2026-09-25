<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= versi('plugin/jquery/jquery-ui.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <select id="filter_nota" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Status Nota</option>
                            <option value="0">Belum Bayar</option>
                            <option value="1">Belum Lunas</option>
                            <option value="2">Lunas</option>
                            <option value="3">Hapus Nota</option>
                        </select>

                        <select id="filter_barang" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Status Barang</option>
                            <option value="0">Belum Ambil</option>
                            <option value="1">Sudah Ambil</option>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>No. Nota</th> <!-- 0 -->
                                    <th>Tgl. Nota</th>
                                    <th>Konsumen</th>
                                    <th>SubTotal</th>
                                    <th>Diskon</th>
                                    <th>NetTotal</th> <!-- 5 -->
                                    <th>Bayar</th>
                                    <th>Sisa</th>
                                    <th>Status Nota</th>
                                    <th>Status Barang</th>
                                    <th class="none">Tgl. Ambil</th> <!-- 10 -->
                                    <th class="none">Keterangan</th>
                                    <th class="none">User Buat</th>
                                    <th class="none">User Ubah</th>
                                    <th class="none">Tgl. Ubah</th>
                                    <th class="none">Tgl. Ubah</th> <!-- 15 -->
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

<div id="modalDiv" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formData" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <div class="col-12 mb-4">
                            <label for="konsumen_id">Nama Konsumen <span class="text-danger">*</span></label>
                            <select id="konsumen_id" name="konsumen_id" class="form-control select2" style="width:100%;" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($menuKonsumen as $row): ?>
                                    <option value="<?= $row->id ?>" data-tipe="<?= $row->tipe ?>"><?= $row->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="tgl_nota">Tgl. Nota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center tanggal" id="tgl_nota" name="tgl_nota" required>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="no_nota">No. Nota</label>
                            <input type="text" class="form-control text-center upper" id="no_nota" name="no_nota" value="00000" disabled>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="konsumen_tipe">Tipe</label>
                            <input type="text" class="form-control" id="konsumen_tipe" value="Retail" disabled>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="nettotal">Nettotal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" id="nettotal" name="nettotal" value="0" disabled>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="bayar">Bayar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" id="bayar" name="bayar" value="0" disabled>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="sisa">Sisa</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" id="sisa" name="sisa" value="0" disabled>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="status_nota">Status Nota</label>
                            <input type="text" class="form-control text-center" id="status_nota" name="status_nota" value="Belum Bayar" disabled>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="status_barang">Status Barang</label>
                            <select id="status_barang" name="status_barang" class="form-control" required>
                                <option value="0">Belum Ambil</option>
                                <option value="1">Sudah Ambil</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-4 mb-4">
                            <label for="tgl_ambil">Tgl. Ambil</label>
                            <input type="text" class="form-control text-center tanggal" id="tgl_ambil" name="tgl_ambil" readonly>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Opsional"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const thisUrl = '<?= site_url('nota') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="<?= versi('page/nota.min.js') ?>" defer></script>
<?= $this->endSection() ?>