<!-- Modal nota_isi -->
<div id="modalDiv" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>
            <form id="formData" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="nota_id" name="nota_id" data-skip-reset="true" value="<?= (int) $id ?>">
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
                                <option value="">-- Pilih --</option>
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
                            <input type="text" id="tema" name="tema" class="form-control capital" maxlength="100" required>
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

                        <!-- harga minimum: hanya berlaku untuk rumus perkalian luas -->
                        <div class="col-6 col-md-3 mb-4" id="wrapHargaMinimum">
                            <label for="minimum_toggle">Harga Minimum</label>
                            <div>
                                <input type="hidden" id="harga_min" name="harga_min" value="0">
                                <input type="checkbox" id="minimum_toggle" data-toggle="toggle" data-on="Ya" data-off="Tidak" data-onstyle="success" data-offstyle="danger" data-style="slow" data-size="sm" data-width="100">
                            </div>
                        </div>

                        <!-- keterangan -->
                        <div class="col-12 col-md-9 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" maxlength="100" placeholder="Opsional"></textarea>
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

<!-- Modal nota.diskon -->
<div id="modalBayar" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 350px;">
        <div class="modal-content">
            <div class="modal-header bg-biru d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formBayar" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="bayar_id" name="id">
                    <input type="hidden" id="bayar_nota_id" name="nota_id" value="<? (int) $id ?>">

                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="metode_bayar">Metode Bayar</label>
                            <select id="metode_bayar" name="metode_bayar" class="form-control" required>
                                <option value="0">Tunai</option>
                                <option value="1">Transfer</option>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="tgl_bayar">Tanggal Bayar</label>
                            <input type="text" class="form-control text-center tanggal" id="tgl_bayar" name="tgl_bayar" required>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="nota_total" class="col-sm-4 col-form-label">Total Nota</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="nota_total" value="0" minlength="3" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_terima" class="col-sm-4 col-form-label">Uang Terima</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_terima" name="uang_terima" value="0" maxlength="14" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_bayar" class="col-sm-4 col-form-label">Uang Bayar</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_bayar" name="uang_bayar" value="0" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="nota_sisa" class="col-sm-4 col-form-label">Sisa Nota</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="nota_sisa" value="0" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_kembali" class="col-sm-4 col-form-label">Uang Kembali</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_kembali" name="uang_kembali" value="0" maxlength="14" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="file_bukti_transfer">
                        <label class="col-sm-4 col-form-label">Bukti Transfer</label>
                        <div class="col-sm-8">
                            <!-- Area Dropzone -->
                            <div id="dropzone_area" class="border rounded p-3 text-center bg-light position-relative" style="border: 2px dashed #989ca0 !important; cursor: pointer;">
                                <i class="bi bi-cloud-arrow-up text-muted mb-0" style="font-size: 30px;"></i>
                                <p class="mb-0 small text-muted">Tarik & Lepas file di sini, atau <span class="text-primary font-weight-bold">Klik untuk Pilih File</span></p>
                                <span id="file_name_preview" class="d-block mt-2 font-weight-bold text-success"></span>

                                <!-- Hidden Input File -->
                                <input type="file" name="bukti_transfer" id="bukti_transfer" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitBayar" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal nota_bayar -->
<div id="modalBayar" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 350px;">
        <div class="modal-content">
            <div class="modal-header bg-biru d-flex justify-content-center">
                <h5 class="modal-title"></h5>
            </div>

            <form id="formBayar" class="px-2" data-cek="true">
                <div class="modal-body">
                    <input type="hidden" id="bayar_id" name="id">
                    <input type="hidden" id="bayar_nota_id" name="nota_id" value="<? (int) $id ?>">

                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="metode_bayar">Metode Bayar</label>
                            <select id="metode_bayar" name="metode_bayar" class="form-control" required>
                                <option value="0">Tunai</option>
                                <option value="1">Transfer</option>
                            </select>
                        </div>

                        <div class="col-6 mb-4">
                            <label for="tgl_bayar">Tanggal Bayar</label>
                            <input type="text" class="form-control text-center tanggal" id="tgl_bayar" name="tgl_bayar" required>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="nota_total" class="col-sm-4 col-form-label">Total Nota</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="nota_total" value="0" minlength="3" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_terima" class="col-sm-4 col-form-label">Uang Terima</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_terima" name="uang_terima" value="0" maxlength="14" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_bayar" class="col-sm-4 col-form-label">Uang Bayar</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_bayar" name="uang_bayar" value="0" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="nota_sisa" class="col-sm-4 col-form-label">Sisa Nota</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="nota_sisa" value="0" maxlength="14" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="uang_kembali" class="col-sm-4 col-form-label">Uang Kembali</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right rupiah" id="uang_kembali" name="uang_kembali" value="0" maxlength="14" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="file_bukti_transfer">
                        <label class="col-sm-4 col-form-label">Bukti Transfer</label>
                        <div class="col-sm-8">
                            <!-- Area Dropzone -->
                            <div id="dropzone_area" class="border rounded p-3 text-center bg-light position-relative" style="border: 2px dashed #989ca0 !important; cursor: pointer;">
                                <i class="bi bi-cloud-arrow-up text-muted mb-0" style="font-size: 30px;"></i>
                                <p class="mb-0 small text-muted">Tarik & Lepas file di sini, atau <span class="text-primary font-weight-bold">Klik untuk Pilih File</span></p>
                                <span id="file_name_preview" class="d-block mt-2 font-weight-bold text-success"></span>

                                <!-- Hidden Input File -->
                                <input type="file" name="bukti_transfer" id="bukti_transfer" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitBayar" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>