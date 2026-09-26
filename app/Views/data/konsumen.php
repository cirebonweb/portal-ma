<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
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
                                <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Tipe</th>
                                    <th>Konsumen</th>
                                    <th class="min-tablet-l">Perusahaan</th>
                                    <th class="min-tablet-l">Alamat</th>
                                    <th class="min-tablet-l">Kota</th> <!-- 5 -->
                                    <th class="min-tablet-l">Whatsapp</th>
                                    <th class="none">Email</th>
                                    <th class="none">Tgl. Buat</th>
                                    <th class="none">Tgl. Ubah</th>
                                    <th class="min-tablet-l no-export">Aksi</th> <!-- 10 -->
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

                        <!-- konsumen_tipe_id -->
                        <div class="col-5 mb-4">
                            <label for="konsumen_tipe_id">Tipe konsumen <span class="text-danger">*</span></label>
                            <select id="konsumen_tipe_id" name="konsumen_tipe_id" class="form-control" required>
                                <option value="">-- Pilih ---</option>
                                <?php foreach ($menuTipe as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- konsumen -->
                        <div class="col-7 mb-4">
                            <label for="nama">Nama Konsumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" required>
                        </div>

                        <!-- perusahaan -->
                        <div class="col-7 mb-4">
                            <label for="perusahaan">Perusahaan</label>
                            <input type="text" class="form-control upper" id="perusahaan" name="perusahaan">
                        </div>

                        <!-- whatsapp -->
                        <div class="col-5 mb-4">
                            <label for="whatsapp">Whatsapp</label>
                            <input type="text" class="form-control text-right angka" id="whatsapp" name="whatsapp">
                        </div>

                        <!-- alamat -->
                        <div class="col-7 mb-4">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control upper" id="alamat" name="alamat">
                        </div>

                        <!-- kota -->
                        <div class="col-5 mb-4">
                            <label for="kota">Kota/Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="kota" name="kota" required>
                        </div>

                        <!-- email -->
                        <div class="col-12 mb-3">
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
<script>
    const urlThis = '<?= site_url('konsumen') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= base_url('page/konsumen.min.js') ?>" defer></script>
<?= $this->endSection() ?>