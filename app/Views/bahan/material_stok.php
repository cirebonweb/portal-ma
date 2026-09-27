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

                        <select id="filter_kondisi" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Kondisi</option>
                            <option value="0">Baik</option>
                            <option value="1">Rusak</option>
                            <option value="2">Cacat</option>
                        </select>

                        <select id="filter_status" class="form-control form-control-sm d-inline-block w-auto mx-1">
                            <option value=""># Status</option>
                            <option value="0">Aktif</option>
                            <option value="1">Nonaktif</option>
                            <option value="2">Habis</option>
                        </select>

                        <table id="tabelData" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>ID</th> <!-- 0 -->
                                    <th>Kode</th>
                                    <th>Material</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Pakai</th>
                                    <th>Stok Sisa</th> <!-- 5 -->
                                    <th>Harga Satuan</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th class="none">Keterangan</th>
                                    <th class="none">Tgl. Buat</th> <!-- 10 -->
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

                        <div class="col-md-6 mb-4">
                            <label for="kode">Kode Material</label>
                            <input type="text" class="form-control" id="kode" disabled>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="nama">Nama Material</label>
                            <input type="text" class="form-control" id="nama" disabled>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="stok_masuk">Stok Masuk</label>
                            <input type="text" class="form-control text-right" id="stok_masuk" disabled>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="stok_pakai">Stok Pakai</label>
                            <input type="text" class="form-control text-right" id="stok_pakai" disabled>
                        </div>

                        <div class="col-4 mb-4">
                            <label for="stok_sisa">Stok Sisa</label>
                            <input type="text" class="form-control text-right" id="stok_sisa" disabled>
                        </div>

                        <div class="col-6 col-md-3 mb-4">
                            <label for="kondisi">Kondisi</label>
                            <select id="kondisi" name="kondisi" class="form-control">
                                <option value="0">Baik</option>
                                <option value="1">Rusak</option>
                                <option value="2">Cacat</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-3 mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="0">Aktif</option>
                                <option value="1">Nonaktif</option>
                                <option value="2">Habis</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" maxlength="100"></textarea>
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
    const urlThis = '<?= site_url('material-stok') ?>';
</script>
<?= $this->include('plugin/js_tabel_form') ?>
<script src="<?= versi('page/material_stok.min.js') ?>" defer></script>
<?= $this->endSection() ?>
