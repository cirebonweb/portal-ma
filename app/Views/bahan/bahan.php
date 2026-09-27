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
                            <option value=""># Tipe Mesin</option>
                            <?php foreach ($menuMesinTipe as $row): ?>
                                <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <select id="filter_kategori" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Kategori</option>
                            <option value="0">Bahan</option>
                            <option value="1">Material</option>
                        </select>

                        <table id="tabelData" class="table table-striped table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Tipe Mesin</th>
                                    <th>Kategori</th>
                                    <th>Kode</th>
                                    <th>Nama Bahan</th>
                                    <th>Gramasi</th> <!-- 5 -->
                                    <th>Ukuran</th>
                                    <th>Isi Paket</th>
                                    <th>Rumus</th>
                                    <th class="no-export">Aksi</th>
                                    <th class="none">Tgl. Buat</th> <!-- 10 -->
                                    <th class="none">Tgl. Ubah</th>
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

                        <!-- kategori -->
                        <div class="col-6 mb-4">
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori" name="kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="0">Bahan Cetak</option>
                                <option value="1">Material Produksi</option>
                            </select>
                        </div>

                        <!-- mesin_tipe -->
                        <div class="col-6 mb-4 group-mesin">
                            <label for="mesin_tipe_id">Tipe Mesin <span class="text-danger">*</span></label>
                            <select id="mesin_tipe_id" name="mesin_tipe_id" class="form-control" style="width:100%;">
                                <option value="">-- Pilih ---</option>
                                <?php foreach ($menuMesinTipe as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- kode -->
                        <div class="col-6 mb-4">
                            <label for="kode">Kode Bahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="kode" name="kode" placeholder="FLX260-3260" required>
                        </div>

                        <!-- bahan_jenis_id -->
                        <div class="col-md-6 mb-4">
                            <label for="bahan_jenis_id">Jenis Bahan <span class="text-danger">*</span></label>
                            <select id="bahan_jenis_id" name="bahan_jenis_id" class="form-control select2" style="width:100%;" disabled>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>

                        <!-- nama -->
                        <div class="col-md-6 mb-4">
                            <label for="nama">Nama Bahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control capital" id="nama" name="nama" required placeholder="contoh: Flexy 280-3260">
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

                        <!-- satuan_1 -->
                        <div class="col-6 col-md-3 mb-4">
                            <div class="form-group">
                                <label for="satuan_1">Satuan isi <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="satuan_1" name="satuan_1" style="width: 100%;">
                                    <?php foreach ($menuSatuan as $key => $value): ?>
                                        <option value="<?= $key; ?>" <?= ($key == 'm²') ? 'selected' : ''; ?>><?= esc($value) ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- satuan_2 -->
                        <div class="col-6 col-md-3 mb-4">
                            <div class="form-group">
                                <label for="satuan_2">Satuan paket <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="satuan_2" name="satuan_2" style="width: 100%;">
                                    <?php foreach ($menuSatuan as $key => $value): ?>
                                        <option value="<?= $key; ?>" <?= ($key == 'roll') ? 'selected' : ''; ?>><?= esc($value) ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- isi_paket -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="isi_paket">Isi paket: <span class="satuan2">1 roll = 0 m²</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control text-center angka" id="isi_paket" name="isi_paket" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text satuan1">m²</span>
                                </div>
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
    const urlThis = '<?= site_url('bahan') ?>';
    const categoryBahanCetak = 0;
    const categoryMaterial = 1;
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('plugin/select2/js/select2.min.js') ?>" defer></script>
<script src="<?= base_url('page/bahan.min.js') ?>" defer></script>
<?= $this->endSection() ?>