<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/tabel_css') ?>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-9">
                <div class="card">
                    <div class="card-body">

                        <select id="filter_divisi" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Divisi</option>
                            <option value="0">Umum</option>
                            <option value="1">Printing</option>
                            <option value="2">Advertising</option>
                            <option value="3">Partner</option>
                        </select>

                        <select id="filter_status" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Divisi</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th class="desktop">Tgl. Buat</th>
                                    <th class="desktop">Tgl. Ubah</th>
                                    <th class="no-export">Aksi</th>
                                </tr>
                            </thead>
                        </table>

                    </div> <!-- .card-body -->
                </div> <!-- .card -->
            </div> <!-- .col-md-12 col-lg-9 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
</section>

<div id="modalDiv" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formData" class="pl-3 pr-3" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <!-- divisi -->
                        <div class="col-md-12 mb-4">
                            <label for="divisi">Divisi <span class="text-danger">*</span></label>
                            <select id="divisi" name="divisi" class="form-control">
                                <option value="0">Umum</option>
                                <option value="1">Printing</option>
                                <option value="2">Advertising</option>
                                <option value="3">Partner</option>
                            </select>
                        </div>

                        <!-- nama -->
                        <div class="col-md-12 mb-4">
                            <label for="nama">Kategori Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <!-- status -->
                        <div class="col-md-12 mb-2">
                            <label for="status" class="mr-3">Status Produk</label>
                            <input type="checkbox" checked id="status_toggle" checked data-toggle="toggle" data-on="Aktif" data-off="Nonaktif" data-onstyle="success" data-offstyle="danger" data-style="slow" data-size="sm" data-width="100">
                            <input type="hidden" name="status" id="status" value="1">
                        </div>

                    </div> <!-- .row -->
                </div> <!-- .modal-body -->

                <div class="modal-footer">
                    <button type="submit" id="btnSubmit" class="btn btn-primary mr-1 float-right">Simpan</button>
                    <button type="button" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Batal</button>
                </div>
            </form>

        </div> <!-- .modal-content -->
    </div> <!-- .modal-dialog -->
</div> <!-- .modalDiv -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?= $this->include('plugin/validasi_js') ?>
<script src="<?= base_url('plugin/datatables/datatables.min.js') ?>" defer></script>
<script src="<?= base_url('vendor/js/helper_form.min.js') ?>" defer></script>
<script src="<?= base_url('vendor/js/helper_format.min.js') ?>" defer></script>
<script src="<?= base_url('page/kategori_produk.min.js') ?>" defer></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js" defer></script>
<?= $this->endSection() ?>