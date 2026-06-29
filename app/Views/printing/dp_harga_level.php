<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/tabel_css') ?>
<link rel="stylesheet" href="<?= base_url('plugin/jquery/jquery-ui.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <select id="filter_level" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Level</option>

                            <?php foreach ($menuLevel as $row): ?>
                                <option value="<?= $row->id ?>">
                                    <?= $row->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Nama Level</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Produk</th>
                                    <th>Harga Level</th>
                                    <th>Tgl. Buat</th>
                                    <th>Tgl. Rubah</th> <!-- 5 -->
                                    <th>Aksi</th>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <div class="modal-body">
                <form id="formData" class="pl-3 pr-3" data-cek="true">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <!-- level_harga_id -->
                        <div class="col-md-4 mb-4">
                            <label for="level_harga_id">Level <span class="text-danger">*</span></label>
                            <select id="level_harga_id" name="level_harga_id" class="form-control">
                                <option value="">Pilih</option>

                                <?php foreach ($menuLevel as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- dp_produk_id -->
                        <div class="col-md-8 mb-4">
                            <label for="dp_produk_id">Produk <span class="text-danger">*</span></label>
                            <select id="dp_produk_id" name="dp_produk_id" class="form-control">
                                <option value="">Pilih</option>

                                <?php foreach ($menuProduk as $row): ?>
                                    <option value="<?= $row->id ?>" data-harga="<?= $row->harga ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- harga produk -->
                        <div class="col-md-6 mb-4">
                            <label for="hargaProduk">Harga Produk</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="hargaProdukRp" name="hargaProdukRp" class="form-control text-right" value="0" readonly>
                                <input type="hidden" id="hargaProduk" name="hargaProduk">
                            </div>
                        </div>

                        <!-- harga -->
                        <div class="col-md-6 mb-4">
                            <label for="harga">Harga Level <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="hargaRp" name="hargaRp" class="form-control text-right rupiah" value="0">
                                <input type="hidden" id="harga" name="harga">
                            </div>
                        </div>

                    </div> <!-- .row -->

                    <button type="submit" id="btnSubmit" class="btn btn-primary mb-2">Simpan</button>
                    <button type="button" id="btnClose" class="btn btn-danger mb-2 float-right" data-dismiss="modal">Batal</button>
                </form>
            </div> <!-- .modal-body -->

            <!-- <div class="modal-footer">
            </div> -->

        </div> <!-- .modal-content -->
    </div> <!-- .modal-dialog -->
</div> <!-- .modalDiv -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('plugin/datatables/datatables.min.js') ?>" defer></script>
<?= $this->include('plugin/validasi_js') ?>
<script src="<?= base_url('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="<?= base_url('vendor/js/helper_format.min.js') ?>" defer></script>
<script src="<?= base_url('vendor/js/helper_form.min.js') ?>" defer></script>
<script src="<?= base_url('page/dp_harga_level.min.js') ?>" defer></script>
<?= $this->endSection() ?>