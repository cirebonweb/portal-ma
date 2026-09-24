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

                        <select id="filter_kategori" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Kategori</option>
                            <option value="0">Internal</option>
                            <option value="1">Eksternal</option>
                            <option value="2">Jasa/Layanan</option>
                        </select>

                        <select id="filter_status" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Bahan</th>
                                    <th>Nama Produk</th>
                                    <th>HPP Produk</th>
                                    <th>Harga Produk</th>
                                    <th>Harga Promo</th>
                                    <th class="none">Tgl. Awal Promo</th>
                                    <th class="none">Tgl. Akhir Promo</th>
                                    <th class="none">Produk Unggulan</th>
                                    <th class="none">Produk Status</th>
                                    <th class="none">Tgl. Buat</th>
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

                        <div class="col-5 col-md-4 mb-4">
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori" name="kategori" class="form-control" required>
                                <option value="0">Internal</option>
                                <option value="1">Eksternal</option>
                                <option value="2">Jasa/Layanan</option>
                            </select>
                        </div>

                        <!-- <div class="col-6 mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div> -->

                        <div class="col-7 col-md-8 mb-4">
                            <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" minlength="3" maxlength="100" required>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="mesin_tipe_id">Filter Tipe Mesin</label>
                            <select id="mesin_tipe_id" name="mesin_tipe_id" class="form-control" style="width:100%;">
                                <option value="">Tanpa Bahan</option>
                                <?php foreach ($menuMesinTipe as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="bahan_id">Bahan</label>
                            <select id="bahan_id" name="bahan_id" class="form-control select2" style="width:100%;" disabled>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="rumus">Metode Hitung (Rumus) <span class="text-danger">*</span></label>
                            <select id="rumus" name="rumus" class="form-control">
                                <option value="0">Perkalian Luas (Lebar x Panjang x Qty x Harga)</option>
                                <option value="1">Perkalian Qty (Qty x Harga)</option>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="hpp">HPP</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control text-right rupiah" id="hpp" name="hpp" value="0" minlength="1" maxlength="14" required>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="harga">Harga Produk</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control text-right rupiah" id="harga" name="harga" value="0" minlength="3" maxlength="14" required>
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="promo">Harga Promo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="promoCek"><input type="checkbox"></span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="promo" name="promo" value="" minlength="3" maxlength="14">
                            </div>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="promo_awal">Promo Awal</label>
                            <input type="text" class="form-control text-center tanggal" id="promo_awal" name="promo_awal" autocomplete="off">
                        </div>

                        <div class="col-6 mb-4">
                            <label for="promo_akhir">Promo Akhir</label>
                            <input type="text" class="form-control text-center tanggal" id="promo_akhir" name="promo_akhir" autocomplete="off">
                        </div>

                        <!-- <div class="col-6 mb-3">
                            <label for="unggulan">Produk Unggulan</label>
                            <select id="unggulan" name="unggulan" class="form-control">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div> -->

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
    const thisUrl = '<?= site_url('produk') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= versi('plugin/jquery/jquery-ui.min.js') ?>" defer></script>
<script src="<?= versi('page/produk.min.js') ?>" defer></script>
<?= $this->endSection() ?>