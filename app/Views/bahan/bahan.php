<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<?= $this->include('plugin/css_tabel') ?>
<link rel="stylesheet" href="<?= versi('plugin/select2/css/select2.min.css') ?>">
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
                                    <th>Kode</th>
                                    <th>Nama Bahan</th>
                                    <th>Gramasi</th>
                                    <th>Lebar</th> <!-- 5 -->
                                    <th>Panjang</th>
                                    <th>Isi Paket</th>
                                    <th>Rumus</th>
                                    <th class="none">Tgl. Buat</th>
                                    <th class="none">Tgl. Ubah</th> <!-- 10 -->
                                    <th class="min-tablet-l no-export">Aksi</th>
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

                        <!-- mesin_tipe_id -->
                        <div class="col-8 mb-4">
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

                        <!-- kode -->
                        <div class="col-4 mb-4">
                            <label for="kode">Kode Bahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="kode" name="kode" required>
                        </div>

                        <!-- nama -->
                        <div class="col-8 mb-4">
                            <label for="nama">Nama Bahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" required placeholder="Contoh: Flexy 280-3270">
                        </div>

                        <!-- gsm -->
                        <div class="col-4 mb-4">
                            <label for="gsm">Gramasi (gsm)</label>
                            <input type="text" class="form-control text-center angka" id="gsm" name="gsm" value="0">
                        </div>

                        <!-- rumus -->
                        <div class="col-md-12 mb-4">
                            <label for="rumus">Metode Hitung (Rumus) <span class="text-danger">*</span></label>
                            <select class="form-control" id="rumus" name="rumus">
                                <option value="0">Perkalian Luas (Lebar x Panjang x Qty x Harga)</option>
                                <option value="1">Perkalian Qty (Quantity x Harga)</option>
                            </select>
                        </div>

                        <!-- lebar -->
                        <div class="col-6 col-md-3 mb-4 group-dimensi">
                            <label for="lebar">Lebar</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="lebar" name="lebar" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- panjang -->
                        <div class="col-6 col-md-3 mb-4 group-dimensi">
                            <label for="panjang">Panjang</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="panjang" name="panjang" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- isi_paket -->
                        <div class="col-12 col-md-6 mb-4">
                            <label for="isi_paket" class="satuan2">1 roll = 0 m²</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="isi_paket" name="isi_paket" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text satuan1">m²</span>
                                </div>
                            </div>
                        </div>

                        <!-- satuan_1 -->
                        <div class="col-6 col-md-6 mb-3">
                            <div class="form-group">
                                <label for="satuan_1">Satuan isi stok <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="satuan_1" name="satuan_1" style="width: 100%;">
                                    <option value="m²">m²</option>
                                    <option value="lembar">lembar</option>
                                    <option value="pcs">pcs</option>
                                    <option value="unit">unit</option>
                                </select>
                            </div>
                        </div>

                        <!-- satuan_2 -->
                        <div class="col-6 col-md-6 mb-3">
                            <div class="form-group">
                                <label for="satuan_2">Satuan paket pembelian <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="satuan_2" name="satuan_2" style="width: 100%;">
                                    <option value="roll">roll</option>
                                    <option value="rim">rim</option>
                                    <option value="dus">dus</option>
                                    <option value="pack">pack</option>
                                    <option value="box">box</option>
                                </select>
                            </div>
                        </div>

                    </div> <!-- row -->
                </div>

                <div class="modal-footer">
                    <button type="submit" id="btnSubmit" class="btn btn-primary mr-1 float-right">Simpan</button>
                    <button type="button" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const thisUrl = '<?= site_url('bahan') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= base_url('page/bahan.min.js') ?>" defer></script>
<?= $this->endSection() ?>