<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('konten') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title text-white">Data Nota</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item"><b>No. Nota</b> <span class="float-right"><?= esc($dataNota->no_nota ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Tgl. Nota</b> <span class="float-right"><?= esc($dataNota->tgl_nota ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Konsumen</b> <span class="float-right"><?= esc($dataNota->konsumen ?: '-') ?></span></li>
                            <li class="list-group-item"><b>Status</b> <span class="float-right"><?= esc(['Belum Bayar', 'Belum Lunas', 'Lunas', 'Hapus Nota'][(int) $dataNota->status_nota] ?? '-') ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-white">Rincian Nota</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tema/Materi</th>
                                    <th>Ukuran</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="none">Produk</th>
                                    <th class="none">Finishing</th>
                                    <th class="none">Keterangan</th>
                                    <th class="none">Status</th>
                                    <th class="none">Dibuat</th>
                                    <th class="none">Diubah</th>
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
                    <input type="hidden" id="nota_id" name="nota_id" value="<?= (int) $dataNota->id ?>">
                    <div class="row">

                        <!-- Kategori Produk -->
                        <div class="col-6 mb-4">
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori" name="kategori" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="0">Internal</option>
                                <option value="1">Eksternal</option>
                                <option value="2">Jasa/Layanan</option>
                            </select>
                        </div>

                        <!-- rumus -->
                        <div class="col-6 mb-4">
                            <label for="rumus">Rumus</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text rumus">?</span>
                                </div>
                                <input type="text" id="rumus" class="form-control" disabled>
                            </div>
                        </div>

                        <!-- produk_id -->
                        <div class="col-12 mb-4">
                            <label for="produk_id">Produk <span class="text-danger">*</span></label>
                            <select id="produk_id" name="produk_id" class="form-control select2" style="width:100%;" required disabled>
                            </select>
                        </div>

                        <!-- finishing_id -->
                        <div class="col-12 mb-4">
                            <label for="finishing_id">Finishing</label>
                            <select id="finishing_id" name="finishing_id" class="form-control select2" style="width:100%;">
                                <option value="">Tanpa Finishing</option>
                                <?php foreach ($menuFinishing as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- tema -->
                        <div class="col-12 mb-4">
                            <label for="tema">Tema <span class="text-danger">*</span></label>
                            <input type="text" id="tema" name="tema" class="form-control upper" maxlength="100" required>
                        </div>

                        <!-- lebar -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="lebar">Lebar</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="lebar" name="lebar" value="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- panjang -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="panjang">Panjang</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="panjang" name="panjang" value="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- luas -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="luas">Luas</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="luas" name="luas" value="0" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">m²</span>
                                </div>
                            </div>
                        </div>

                        <!-- qty -->
                        <div class="col-6 col-md-3 mb-4">
                            <label for="qty">Qty <span class="text-danger">*</span></label>
                            <input type="number" id="qty" name="qty" class="form-control text-center" min="1" value="1" required>
                        </div>

                        <!-- harga -->
                        <div class="col-6 col-md-4 mb-4">
                            <label for="harga">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="harga" name="harga" value="0" required>
                            </div>
                        </div>

                        <!-- jumlah -->
                        <div class="col-6 col-md-5 mb-4">
                            <label for="jumlah">Jumlah</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-bold text-right rupiah" id="jumlah" name="jumlah" value="0" readonly>
                            </div>
                        </div>

                        <!-- status -->
                        <!-- <div class="col-md-4 mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="0">Antrian</option>
                                <option value="1">Tertunda</option>
                                <option value="2">Proses</option>
                                <option value="3">Selesai</option>
                                <option value="4">Batal</option>
                            </select>
                        </div> -->

                        <!-- keterangan -->
                        <div class="col-12 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control" maxlength="100">
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
    const thisUrl = '<?= site_url('nota/isi') ?>';
    const idNota = <?= (int) $dataNota->id ?>;
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('page/nota_isi.min.js') ?>" defer></script>
<?= $this->endSection() ?>