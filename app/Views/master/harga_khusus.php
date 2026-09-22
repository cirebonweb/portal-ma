<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <select id="filter_konsumen" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Konsumen</option>
                            <?php foreach ($menuKonsumen as $row): ?>
                                <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="filter_produk" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Produk</option>
                            <?php foreach ($menuProduk as $row): ?>
                                <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Konsumen</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th class="desktop">Tgl. Buat</th>
                                    <th class="desktop">Tgl. Ubah</th>
                                    <th class="no-export">Aksi</th>
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
                        <div class="col-md-12 mb-3">
                            <label for="konsumen_id">Konsumen <span class="text-danger">*</span></label>
                            <select id="konsumen_id" name="konsumen_id" class="form-control select2" required style="width:100%;">
                                <option value="">-- Pilih Konsumen --</option>
                                <?php foreach ($menuKonsumen as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="produk_id">Produk <span class="text-danger">*</span></label>
                            <select id="produk_id" name="produk_id" class="form-control select2" required style="width:100%;">
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($menuProduk as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="hargaRp">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="harga" name="harga" value="0" required>
                            </div>
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
    const thisUrl = '<?= site_url('harga-khusus') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('page/harga_khusus.min.js') ?>" defer></script>
<?= $this->endSection() ?>
