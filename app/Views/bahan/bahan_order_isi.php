<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <!-- Data Order -->
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title text-white">Data Order</h3>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item"><b>Tgl. Order</b> <span class="float-right"><?= esc($dataOrder->tgl_order ?: '-') ?></span></li>
                            <li class="list-group-item"><b>No. Order</b> <span class="float-right"><?= esc($dataOrder->no_order ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Supplier</b> <span class="float-right"><?= esc($dataOrder->supplier ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Perusahaan</b> <span class="float-right"><?= esc($dataOrder->perusahaan ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Kontak</b> <span class="float-right"><?= esc($dataOrder->kontak ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Email</b> <span class="float-right"><?= esc($dataOrder->email ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Alamat</b> <span class="float-right"><?= esc($dataOrder->alamat ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Kota</b> <span class="float-right"><?= esc($dataOrder->kota ?: '-') ?></span></li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Tabel Rincian Order -->
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-white">Rincian Order</h3>
                    </div>

                    <div class="card-body">
                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Nama Bahan</th>
                                    <th>Harga Satuan</th>
                                    <th>Harga Paket</th>
                                    <th>Qty Paket</th>
                                    <th>Jumlah</th> <!-- 5 -->
                                    <th class="none">keterangan</th>
                                    <th class="none">Tgl. Ubah</th>
                                    <th class="none">Tgl. Ubah</th>
                                    <th class="none no-export">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="card-footer">
                        <?php if ((int) $dataOrder->status_stok === 0): ?>
                            <button type="button" class="btn btn-dark btn-sm" id="tambah-stok" onclick="tambahStok(<?= $dataOrder->id ?>)"><i class="bi bi-box-seam mr-1"></i>Tambah Stok Bahan</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-sm" id="tambah-stok" disabled> <i class="bi bi-check-circle mr-1"></i> Stok bahan sudah ditambahkan</button>
                        <?php endif; ?>
                    </div>

                </div>
                <!-- </div> -->

                <div class="row">

                    <!-- SubTotal -->
                    <div class="col-6 col-md-4">
                        <div class="info-box bg-white">
                            <div class="info-box-content">
                                <span class="info-box-text text-center">Subtotal</span>
                                <span id="RpSubTotal" class="text-number text-center">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ongkir -->
                    <div class="col-6 col-md-4">
                        <div class="info-box bg-white">
                            <div class="info-box-content">
                                <span class="info-box-text text-center">Ongkir</span>
                                <span id="RpOngkir" class="text-number text-center">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-md-4">
                        <div class="info-box bg-white">
                            <div class="info-box-content">
                                <span class="info-box-text text-center">Total</span>
                                <span id="RpTotal" class="text-number text-center">0</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end Tabel Rincian Order -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
</section>

<!-- Modal Add/Edit Bahan Order -->
<div id="modalDiv" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formData" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="bahan_order_id" name="bahan_order_id">
                    <div class="row">

                        <!-- mesin_tipe_id -->
                        <div class="col-md-6 mb-4">
                            <label for="mesin_tipe_id">Tipe Mesin <span class="text-danger">*</span></label>
                            <select id="mesin_tipe_id" class="form-control" style="width:100%;" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($menuTipeMesin as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- bahan_id -->
                        <div class="col-md-6 mb-4">
                            <label for="bahan_id">Nama Bahan <span class="text-danger">*</span></label>
                            <select id="bahan_id" name="bahan_id" class="form-control select2" style="width:100%;" required disabled>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>

                        <!-- ukuran_bahan -->
                        <div class="col-6 mb-4">
                            <label for="harga_satuan">Ukuran Bahan</label>
                            <input type="text" class="form-control text-center ukuran_bahan" value="null" disabled>
                        </div>

                        <!-- isi_paket -->
                        <div class="col-6 mb-4">
                            <label for="harga_satuan">Isi Paket</label>
                            <input type="text" class="form-control text-center isi_paket" value="null" disabled>
                        </div>

                        <!-- harga_satuan -->
                        <div class="col-6 mb-4">
                            <label for="harga_satuan">Harga Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="harga_satuan" name="harga_satuan" class="form-control text-right rupiah" value="0" minlength="3" maxlength="14" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text satuan1">?</span>
                                </div>
                            </div>
                        </div>

                        <!-- harga_paket -->
                        <div class="col-6 mb-4">
                            <label for="harga_paket">Harga Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="harga_paket" name="harga_paket" class="form-control text-right rupiah" value="0" minlength="3" maxlength="14" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text satuan2">?</span>
                                </div>
                            </div>
                        </div>

                        <!-- qty -->
                        <div class="col-5 mb-4">
                            <label for="qty">Qty Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="qty" name="qty" class="form-control text-center" value="1" min="1" maxlength="6" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text satuanQty">?</span>
                                </div>
                            </div>
                        </div>

                        <!-- jumlah -->
                        <div class="col-7 mb-4">
                            <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="jumlah" name="jumlah" class="form-control text-bold text-right rupiah" value="0" minlength="3" maxlength="14" required readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-dark">Harga Paket = Harga Satuan × Isi Paket <br> Jumlah = Harga Paket × Qty Paket</div>
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
    const thisUrl = '<?= site_url('bahan-order/isi') ?>';
    const statusStok = <?= (int) $dataOrder->status_stok ?>;
    const idOrder = <?= (int) $dataOrder->id ?>;
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('page/bahan_order_isi.min.js') ?>" defer></script>
<?= $this->endSection() ?>