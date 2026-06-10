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

                        <select id="filter_mesin" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Mesin</option>

                            <?php foreach ($dpMesin as $row): ?>
                                <option value="<?= $row->id ?>">
                                    <?= $row->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select id="filter_bahan" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Bahan</option>

                            <?php foreach ($dpBahan as $row): ?>
                                <option value="<?= $row->id ?>">
                                    <?= $row->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Mesin</th>
                                    <th>Bahan</th>
                                    <th>Produk</th>
                                    <th>Ukuran</th>
                                    <th>Rumus</th> <!-- 5 -->
                                    <th>HPP</th>
                                    <th>Harga Normal</th>
                                    <th>Harga Promo</th>
                                    <th>Status Promo</th>
                                    <th class="none">Tgl. Awal Promo</th> <!-- 10 -->
                                    <th class="none">Tgl. Akhir Promo</th>
                                    <th class="none">Tgl. Buat</th>
                                    <th class="none">Tgl. Rubah</th>
                                    <th class="none">Aksi</th> <!-- 14 -->
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
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <div class="modal-body">
                <form id="formData" class="pl-3 pr-3" data-cek="true">
                    <input type="hidden" id="id" name="id">
                    <div class="row">

                        <!-- dp_mesin_id -->
                        <div class="col-md-6 mb-4">
                            <label for="dp_mesin_id">Kategori Mesin <span class="text-danger">*</span></label>
                            <select id="dp_mesin_id" name="dp_mesin_id" class="form-control">
                                <?php foreach ($dpMesin as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- dp_bahan_id -->
                        <div class="col-md-6 mb-4">
                            <label for="dp_bahan_id">Kategori Bahan <span class="text-danger">*</span></label>
                            <select id="dp_bahan_id" name="dp_bahan_id" class="form-control">
                                <?php foreach ($dpBahan as $row): ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- nama produk -->
                        <div class="col-md-12 mb-4">
                            <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama">
                        </div>

                        <!-- lebar -->
                        <div class="col-6 col-md-3 mb-4">
                            <label for="lebar">Lebar (m) <span class="text-danger">*</span></label>
                            <input type="text" id="lebar" name="lebar" class="form-control text-center" value="0.00">
                        </div>

                        <!-- panjang -->
                        <div class="col-6 col-md-3 mb-4">
                            <label for="panjang">Panjang (m) <span class="text-danger">*</span></label>
                            <input type="text" id="panjang" name="panjang" class="form-control text-center" value="0.00">
                        </div>

                        <!-- hpp -->
                        <div class="col-md-6 mb-4">
                            <label for="hpp">HPP (Harga Pokok Produksi)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="hppRp" name="hppRp" class="form-control text-right rupiah" value="0">
                                <input type="hidden" id="hpp" name="hpp">
                            </div>
                        </div>

                        <!-- harga -->
                        <div class="col-md-6 mb-4">
                            <label for="harga">Harga Normal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" id="hargaRp" name="hargaRp" class="form-control text-right rupiah" value="0">
                                <input type="hidden" id="harga" name="harga">
                            </div>
                        </div>

                        <!-- promo -->
                        <div class="col-md-6 mb-4">
                            <label for="promo">Harga Promo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><input type="checkbox" id="isPromo"></span>
                                </div>
                                <input type="text" id="promoRp" name="promoRp" class="form-control text-right rupiah" disabled>
                                <input type="hidden" id="promo" name="promo">
                            </div>
                        </div>

                        <!-- promo_awal -->
                        <div class="col-6 mb-2">
                            <label>Tgl. Promo Awal</label>
                            <div class="form-group">
                                <input type="text" id="promo_awal_tgl" name="promo_awal_tgl" class="form-control text-center tanggal" disabled>
                                <input type="hidden" id="promo_awal" name="promo_awal">
                            </div>
                        </div>

                        <!-- promo_akhir -->
                        <div class="col-6 mb-2">
                            <label>Tgl. Promo Akhir</label>
                            <div class="form-group">
                                <input type="text" id="promo_akhir_tgl" name="promo_akhir_tgl" class="form-control text-center tanggal" disabled>
                                <input type="hidden" id="promo_akhir" name="promo_akhir">
                            </div>
                        </div>

                        <!-- rumus -->
                        <div class="col-md-12 mb-5">
                            <label for="rumus">Rumus <span class="text-danger">*</span></label>
                            <select id="rumus" name="rumus" class="form-control">
                                <option value="0">Perkalian Luas = Lebar x Panjang x Harga x Qty</option>
                                <option value="1">Perkalian Qty = Harga x Qty</option>
                            </select>
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
<script src="<?= base_url('page/dp_produk.min.js') ?>" defer></script>
<?= $this->endSection() ?>