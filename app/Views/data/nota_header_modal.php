<div id="modalNotaHeader" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title">Edit Data Nota</h5>
            </div>

            <form id="formNotaHeader" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="notaHeaderId" name="id">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="notaHeaderKonsumen">Nama Konsumen <span class="text-danger">*</span></label>
                            <select id="notaHeaderKonsumen" name="konsumen_id" class="form-control" style="width:100%;" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($menuKonsumen as $row): ?>
                                    <option value="<?= $row->id ?>" data-tipe="<?= esc((string) $row->tipe, 'attr') ?>"><?= esc($row->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="notaHeaderTglNota">Tgl. Nota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center nota-header-date" id="notaHeaderTglNota" name="tgl_nota" required>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="notaHeaderStatusBarang">Status Barang</label>
                            <select id="notaHeaderStatusBarang" name="status_barang" class="form-control" required>
                                <option value="0">Belum Ambil</option>
                                <option value="1">Sudah Ambil</option>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="notaHeaderTglAmbil">Tgl. Ambil</label>
                            <input type="text" class="form-control text-center nota-header-date" id="notaHeaderTglAmbil" name="tgl_ambil">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="notaHeaderKeterangan">Keterangan</label>
                            <textarea class="form-control" id="notaHeaderKeterangan" name="keterangan" rows="3" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanNotaHeader" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
