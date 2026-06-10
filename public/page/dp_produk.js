/*!
 * CirebonWeb (https://cirebonweb.com)
 * Copyright (c) 2026 CirebonWeb
 * Licensed under MIT (https://opensource.org/licenses/MIT)
 */

// Deklarasi variabel
const $tabelData = $('#tabelData'),
    $formData = $('#formData'),
    $modalDiv = $('#modalDiv'),
    formCek = CirebonwebForm.FormCek($formData);

// DataTables
$(function () {
    $tabelData.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'produk/tabel',
            type: 'POST',
            data: function (d) {
                d.filter_mesin = $('#filter_mesin').val();
                d.filter_bahan = $('#filter_bahan').val();
            }
        },
        autoWidth: false,
        responsive: true,
        search: { return: true },
        dom: 'Blfrtip',
        buttons: [
            { text: 'Add', action: () => { onclick = simpan() } },
            {
                text: 'Reload',
                action: function (e, dt, node, config) {
                    dt.search('').draw();
                    $('#filter_mesin').val('').trigger('change');
                    $('#filter_bahan').val('').trigger('change');

                    // Reload data dari server
                    swal.fire('Informasi', 'Selesai reload tabel ke kondisi awal', 'success').then(function () {
                        dt.ajax.reload(null, true);
                    })
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13] }
                // exportOptions: { columns: ':visible:not(:eq(14))' }
            }
        ],
        columnDefs: [
            { targets: '_all', className: 'dt-head-center' },
            { targets: [10, 11, 12, 13], render: function (data, type, row) { return CirebonwebFormat.Tanggal(data) } },
            { targets: [4, 9], className: 'dt-body-center' },
            { targets: [6, 7, 8], className: 'dt-body-right', render: function (data, type, row) { return CirebonwebFormat.Rupiah(data) } }
        ]
    });

    // Custom dropdown filter
    $('#tabelData_filter.dataTables_filter').append($('#filter_mesin, #filter_bahan'));
    $(document).on('change', '#filter_mesin, #filter_bahan', function () {
        $tabelData.DataTable().ajax.reload(null, false);
    });
});

// Reset Modal
$modalDiv.on('shown.bs.modal', function () { formCek.resetInitial() });
$modalDiv.on('hidden.bs.modal', function () { CirebonwebForm.FormReset(this) });

// Capitalize First Letter
$('.upper').on('keyup', function () {
    let start = this.selectionStart;
    let end = this.selectionEnd;
    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
    this.setSelectionRange(start, end); // Mempertahankan kursor di tempatnya semula
});

// Input angka
$('.angka').on('input', function () {
    let value = $(this).val();
    value = value.replace(/[^0-9.]/g, '');
    let match = value.match(/^\d*\.?\d{0,2}/);
    $(this).val(match ? match[0] : '');
});

// Input rupiah
$('.rupiah').on('input', function () {
    let nilai = CirebonwebFormat.unRupiah($(this).val());
    $(this).val(CirebonwebFormat.Rupiah(nilai));
    const hiddenId = $(this).attr('id').replace('Rp', '');
    $('#' + hiddenId).val(nilai);
});

// Input tanggal
$('#modalDiv').on('shown.bs.modal', function () {
    $('.tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function () {
            var mysqlDate = $.datepicker.formatDate(
                'yy-mm-dd',
                $(this).datepicker('getDate')
            );
            $('#' + this.id.replace('_tgl', '')).val(mysqlDate);
        }
    });
});

// Cek promo
function togglePromo() {
    const isPromo = $('#isPromo').is(':checked');

    $('#promoRp').prop('disabled', !isPromo);
    $('#promo_awal_tgl').prop('disabled', !isPromo);
    $('#promo_akhir_tgl').prop('disabled', !isPromo);

    if (!isPromo) {
        $('#promoRp').val('');
        $('#promo').val('');

        $('#promo_awal_tgl').val('');
        $('#promo_awal').val('');

        $('#promo_akhir_tgl').val('');
        $('#promo_akhir').val('');
    }
}

$('#isPromo').on('change', togglePromo);

// Form Submit (Insert/Update)
function simpan(id) {
    let $modalTitle = $('.modal-title'),
        $btnSubmit = $('#btnSubmit'),
        postUrl = 'produk/simpan';

    // Tentukan URL POST: Simpan atau Update
    if (id > 0) {
        CirebonwebForm.FormFetch('produk/getid', { id }, 'POST', {})
            .done(function (response) {
                if (response.success) {
                    $modalTitle.text('Edit Data');
                    $btnSubmit.text('Update');

                    // Isi form
                    $formData.find('#id').val(response.data.id);
                    $formData.find('#dp_mesin_id').val(response.data.dp_mesin_id);
                    $formData.find('#dp_bahan_id').val(response.data.dp_bahan_id);
                    $formData.find('#nama').val(response.data.nama);
                    $formData.find('#lebar').val(response.data.lebar);
                    $formData.find('#panjang').val(response.data.panjang);

                    $formData.find('#hppRp').val(response.data.hpp).trigger('input');
                    $formData.find('#hargaRp').val(response.data.harga).trigger('input');

                    if (response.data.promo > 0) {
                        $('#isPromo')
                            .prop('checked', true)
                            .trigger('change');
                    }

                    $formData.find('#promoRp').val(response.data.promo || '').trigger('input');

                    $formData.find('#promo_awal_tgl').val(response.data.promo_awal);
                    $formData.find('#promo_akhir_tgl').val(response.data.promo_akhir);

                    $formData.find('#promo_awal').val(response.data.promo_awal);
                    $formData.find('#promo_akhir').val(response.data.promo_akhir);

                    $formData.find('#rumus').val(response.data.rumus);
                    $modalDiv.modal('show');
                }
            })
    } else {
        $('#id').val('');
        $modalTitle.text('Tambah Data');
        $btnSubmit.text('Simpan');
        $modalDiv.modal('show');
    }

    // Form Validasi
    $formData.validate($.extend(CirebonwebForm.FormValidasi(), {
        rules: {
            dp_mesin_id: { required: true },
            dp_bahan_id: { required: true },
            nama: { required: true, minlength: 3, maxlength: 100 },
            lebar: { required: true, minlength: 1, maxlength: 4 },
            panjang: { required: true, minlength: 1, maxlength: 4 },
            hpp: { required: false, digits: true, minlength: 1, maxlength: 11 },
            harga: { required: true, digits: true, minlength: 3, maxlength: 11 },
            promo: { required: function () { return $('#isPromo').is(':checked') }, digits: true, minlength: 3, maxlength: 11 },
            promo_awal: { required: function () { return $('#isPromo').is(':checked') }, dateISO: true },
            promo_akhir: { required: function () { return $('#isPromo').is(':checked') }, dateISO: true },
            rumus: { required: true }
        },
        submitHandler: function () {
            if (!formCek.isChanged()) return false;
            CirebonwebForm.FormFetch(postUrl, $formData.serializeArray(), 'POST', {
                before_call: function () {
                    if (!formCek.isChanged()) return false;
                    $btnSubmit.attr('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...');
                },
                after_call: function () { $btnSubmit.attr('disabled', false).html('Submit') }
            })
                .done(function (response) {
                    if (response.success) {
                        Swal.fire('Sukses', response.messages, 'success').then(function () {
                            $modalDiv.modal('hide');
                            $tabelData.DataTable().ajax.reload(null, false);
                        })
                    }
                });
            return false; // Mencegah submit native
        }
    })
    )
}

// Form Submit (Delete)
function hapus(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            CirebonwebForm.FormFetch('konsumen/hapus', { id }, 'POST', {})
                .done(function (response) {
                    if (response.success) {
                        Swal.fire('Sukses', response.messages, 'success').then(() => {
                            $tabelData.DataTable().ajax.reload(null, false);
                        });
                    } else {
                        Swal.fire('Gagal', response.messages, 'error');
                    }
                });
        }
    });
}
