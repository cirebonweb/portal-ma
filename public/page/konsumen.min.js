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
            url: 'konsumen/tabel',
            type: 'POST',
            data: function (d) {
                d.filter_level = $('#filter_level').val();
                d.filter_divisi = $('#filter_divisi').val();
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
                    $('#filter_level').val('').trigger('change');
                    $('#filter_divisi').val('').trigger('change');

                    // Reload data dari server
                    swal.fire('Informasi', 'Selesai reload tabel ke kondisi awal', 'success').then(function () {
                        dt.ajax.reload(null, true);
                    })
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: { columns: ':visible:not(:eq(12))' }
            }
        ],
        columnDefs: [
            { targets: '_all', className: 'dt-head-center' },
            { targets: [10, 11], render: function (data, type, row) { return CirebonwebFormat.Tanggal(data) } },
            { targets: 12, className: 'dt-body-center', orderable: false },
        ]
    });

    // Custom dropdown filter
    $('#tabelData_filter.dataTables_filter').append($('#filter_level, #filter_divisi'));
    $(document).on('change', '#filter_level, #filter_divisi', function () {
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

// Form Submit (Insert/Update)
function simpan(id) {
    let $modalTitle = $('.modal-title'),
        $btnSubmit = $('#btnSubmit'),
        postUrl = 'konsumen/simpan';

    // Tentukan URL POST: Simpan atau Update
    if (id > 0) {
        CirebonwebForm.FormFetch('konsumen/getid', { id }, 'POST', {})
            .done(function (response) {
                if (response.success) {
                    $modalTitle.text('Edit Data');
                    $btnSubmit.text('Update');

                    // Isi form
                    $formData.find('#id').val(response.data.id);
                    $formData.find('#nama').val(response.data.nama);
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
            level_harga_id: { required: false },
            nama: { required: true, minlength: 3, maxlength: 40 },
            perusahaan: { required: false, minlength: 3, maxlength: 40 },
            alamat: { required: false, minlength: 5, maxlength: 100 },
            kota: { required: false, minlength: 5, maxlength: 20 },
            whatsapp: { required: false, minlength: 5, maxlength: 20 },
            telegram: { required: false, digits: true, minlength: 5, maxlength: 20 },
            email: { required: false, customEmail: true },
            divisi: { required: false, digits: true }
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
