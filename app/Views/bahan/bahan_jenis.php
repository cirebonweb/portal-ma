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

                        <select id="filter_jenis" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Jenis</option>
                            <option value="0">Bahan</option>
                            <option value="1">Material</option>
                        </select>

                        <select id="filter_tipe" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Tipe</option>
                            <?php foreach ($menuMesinTipe as $row): ?>
                                <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Jenis</th>
                                    <th>Kode</th>
                                    <th>Tipe</th>
                                    <th>Nama</th>
                                    <th>Gramasi</th> <!-- 5 -->
                                    <th>Rumus</th>
                                    <th class="min-tablet-l no-export">Aksi</th>
                                    <th class="none">Tgl. Buat</th>
                                    <th class="none">Tgl. Ubah</th> <!-- 9 -->
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

                        <!-- jenis -->
                        <div class="col-6 mb-4">
                            <label for="jenis">Jenis <span class="text-danger">*</span></label>
                            <select id="jenis" name="jenis" class="form-control" required>
                                <option value="0">Bahan (diproses mesin)</option>
                                <option value="1">Material (pendukung)</option>
                            </select>
                        </div>

                        <!-- kode -->
                        <div class="col-6 mb-4">
                            <label for="kode">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="kode" name="kode" placeholder="contoh: FLX260" required>
                        </div>

                        <!-- nama -->
                        <div class="col-12 mb-4">
                            <label for="nama">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control upper" id="nama" name="nama" placeholder="contoh: Flexy 260" required>
                        </div>

                        <!-- mesin_tipe_id -->
                        <div class="col-6 mb-4 group-mesin">
                            <label for="mesin_tipe_id">Tipe Mesin</label>
                            <select id="mesin_tipe_id" name="mesin_tipe_id" class="form-control">
                                <option value="">-- Pilih --</option>
                                <?php foreach ($menuMesinTipe as $row): ?>
                                    <option value="<?= $row->id ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- gsm -->
                        <div class="col-6 mb-4 group-mesin">
                            <label for="gsm">Gramasi (gsm)</label>
                            <input type="text" class="form-control text-center angka" id="gsm" name="gsm" value="0">
                        </div>

                        <!-- rumus -->
                        <div class="col-12 mb-4">
                            <label for="rumus">Metode Hitung (Rumus) <span class="text-danger">*</span></label>
                            <select id="rumus" name="rumus" class="form-control" required>
                                <option value="0">Perkalian Luas (Lebar x Panjang)</option>
                                <option value="1">Perkalian Qty</option>
                            </select>
                        </div>

                    </div>
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
    const urlThis = '<?= site_url('bahan-jenis') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('page/bahan_jenis.min.js') ?>" defer></script>
<?= $this->endSection() ?>
