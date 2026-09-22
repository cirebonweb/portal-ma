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

                        <select id="filter_supplier" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Supplier</option>
                            <?php foreach ($menuSupplier as $row): ?>
                                <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Tgl. Order</th>
                                    <th>No. Order</th>
                                    <th>Supplier</th>
                                    <th>Qty</th>
                                    <th>SubTotal</th> <!-- 5 -->
                                    <th>Ongkir</th>
                                    <th>Total</th>
                                    <th class="none">Status Stok</th>
                                    <th class="none">Keterangan</th>
                                    <th class="none">User Buat</th> <!-- 10 -->
                                    <th class="none">User Ubah</th>
                                    <th class="none">Tgl. Ubah</th>
                                    <th class="none">Tgl. Ubah</th>
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
                            <label for="supplier_id">Nama Supplier <span class="text-danger">*</span></label>
                            <select id="supplier_id" name="supplier_id" class="form-control select2" style="width:100%;" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($menuSupplier as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="tgl_order">Tanggal Order <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center tanggal" id="tgl_order" name="tgl_order" required>
                        </div>

                        <div class="col-8 mb-4">
                            <label for="no_order">Nomor Order</label>
                            <input type="text" class="form-control upper" id="no_order" name="no_order" placeholder="Contoh: PO-2026-001">
                        </div>

                        <div class="col-6 mb-4">
                            <label for="subtotal">Total Qty</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-right" id="total_qty" value="0" disabled>
                                <div class="input-group-append">
                                    <span class="input-group-text">item</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="subtotal">SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="subtotal" name="subtotal" value="0" readonly>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="ongkir">Ongkir/Biaya Tambahan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="ongkir" name="ongkir" value="0" minlength="1" maxlength="14" required>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="total">Total</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="total" name="total" value="0" readonly>
                            </div>
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
    const thisUrl = '<?= site_url('bahan-order') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="<?= versi('page/bahan_order.min.js') ?>" defer></script>
<?= $this->endSection() ?>