<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-9">
                <div class="card">
                    <div class="card-body">

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Finishing</th>
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

                        <!-- nama -->
                        <div class="col-md-12 mb-4">
                            <label for="nama">Nama Finishing <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" required>
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
    const thisUrl = '<?= site_url('mesin-tipe') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('page/finishing.min.js') ?>" defer></script>
<?= $this->endSection() ?>