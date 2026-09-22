<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <select id="filter_tipe" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Tipe</option>
                            <?php foreach ($menuTipe as $row): ?>
                                <option value="<?= $row->id ?>">
                                    <?= $row->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Tipe Mesin</th>
                                    <th>Nama Mesin</th>
                                    <th>Print Area</th>
                                    <th>Min. Lebar</th>
                                    <th>Max. Lebar</th> <!-- 5 -->
                                    <th>Min. Panjang</th>
                                    <th>Tgl. Buat</th>
                                    <th>Tgl. Ubah</th>
                                    <th class="min-tablet-l no-export">Aksi</th>
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
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formData" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <!-- mesin_tipe_id -->
                        <div class="col-6 mb-4">
                            <label for="mesin_tipe_id">Tipe Mesin <span class="text-danger">*</span></label>
                            <select id="mesin_tipe_id" name="mesin_tipe_id" class="form-control" required>
                                <option value="">-- Pilih ---</option>
                                <?php foreach ($menuTipe as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- nama -->
                        <div class="col-9 mb-4">
                            <label for="nama">Nama Mesin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" required>
                        </div>

                        <!-- print_area -->
                        <div class="col-3 mb-4">
                            <label for="print_area">Area Print</label>
                            <input type="checkbox" id="print_toggle" data-toggle="toggle" data-on="Ya" data-off="Tidak" data-onstyle="success" data-offstyle="danger" data-style="slow" data-size="sm" data-width="100">
                            <input type="hidden" name="print_area" id="print_area" value="0">
                        </div>

                        <!-- min_lebar -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="min_lebar">Min. Lebar <span class="text-danger area-print-required">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="min_lebar" name="min_lebar" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- max_lebar -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="max_lebar">Max. Lebar <span class="text-danger area-print-required">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="max_lebar" name="max_lebar" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- min_panjang -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="min_panjang">Min. Panjang <span class="text-danger area-print-required">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="min_panjang" name="min_panjang" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
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
<script>
    const thisUrl = '<?= site_url('mesin') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js" defer></script>
<script src="<?= base_url('page/mesin.min.js') ?>" defer></script>
<?= $this->endSection() ?>