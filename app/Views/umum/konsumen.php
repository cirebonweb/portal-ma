<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/tabel_css') ?>
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
                            <option value="Retail">Retail</option>

                            <?php foreach ($levelHarga as $row): ?>
                                <option value="<?= $row->id ?>">
                                    <?= $row->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select id="filter_divisi" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Divisi</option>
                            <option value="0">Umum</option>
                            <option value="1">Printing</option>
                            <option value="2">Advertising</option>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Level</th>
                                    <th class="min-tablet-l">Divisi</th>
                                    <th>Konsumen</th>
                                    <th class="min-tablet-l">Perusahaan</th>
                                    <th class="desktop">Alamat</th> <!-- 5 -->
                                    <th class="desktop">Kota</th>
                                    <th class="none">Whatsapp</th>
                                    <th class="none">ID Telegram</th>
                                    <th class="none">Email</th>
                                    <th class="none">Tgl. Buat</th> <!-- 10 -->
                                    <th class="none">Tgl. Rubah</th>
                                    <th class="min-tablet-l">Aksi</th>
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
    <div class="modal-dialog modal modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formData" class="pl-3 pr-3" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <!-- level_harga_id -->
                        <div class="col-md-6 mb-4">
                            <label for="level_harga_id">Level harga <span class="text-danger">*</span></label>
                            <select id="level_harga_id" name="level_harga_id" class="form-control">
                                <option value="">Retail</option>

                                <?php foreach ($levelHarga as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- divisi -->
                        <div class="col-md-6 mb-4">
                            <label for="divisi">Divisi <span class="text-danger">*</span></label>
                            <select id="divisi" name="divisi" class="form-control">
                                <option value="0">Umum</option>
                                <option value="1">Printing</option>
                                <option value="2">Advertising</option>
                            </select>
                        </div>

                        <!-- konsumen -->
                        <div class="col-md-6 mb-4">
                            <label for="nama">Nama Konsumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" required>
                        </div>

                        <!-- perusahaan -->
                        <div class="col-md-6 mb-4">
                            <label for="perusahaan">Perusahaan</label>
                            <input type="text" class="form-control upper" id="perusahaan" name="perusahaan">
                        </div>

                        <!-- alamat -->
                        <div class="col-md-6 mb-4">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control upper" id="alamat" name="alamat">
                        </div>

                        <!-- kota -->
                        <div class="col-md-6 mb-4">
                            <label for="kota">Kota</label>
                            <input type="text" class="form-control upper" id="kota" name="kota">
                        </div>

                        <!-- whatsapp -->
                        <div class="col-md-6 mb-4">
                            <label for="whatsapp">Whatsapp</label>
                            <input type="text" class="form-control upper" id="whatsapp" name="whatsapp">
                        </div>

                        <!-- telegram -->
                        <div class="col-md-6 mb-4">
                            <label for="telegram">ID Telegram</label>
                            <input type="text" class="form-control upper" id="telegram" name="telegram">
                        </div>

                        <!-- email -->
                        <div class="col-md-12 mb-2">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email">
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
<script src="<?= base_url('page/konsumen.min.js') ?>" defer></script>
<?= $this->endSection() ?>