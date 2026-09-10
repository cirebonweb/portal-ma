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
            url: 'kategori-produk/tabel',
            type: 'POST',
            data: function (d) {
                d.filter_divisi = $('#filter_divisi').val();
                d.filter_status = $('#filter_status').val();
            }
        },
        autoWidth: false,
        responsive: true,
        search: { return: true },
        dom: 'Blfrtip',
        buttons: [
            { text: 'Add', action: () => { simpan(); } },
            {
                text: 'Reload',
                action: function (e, dt, node, config) {
                    dt.search('').draw();
                    $('#filter_divisi').val('').trigger('change');
                    $('#filter_status').val('').trigger('change');

                    // Reload data dari server
                    swal.fire('Informasi', 'Selesai reload tabel ke kondisi awal', 'success').then(function () {
                        dt.ajax.reload(null, true);
                    });
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: { columns: ':not(.no-export)' }
            }
        ],
        columnDefs: [
            { targets: '_all', className: 'dt-head-center' },
            { targets: [0, 3, 4, 5, 6], className: 'dt-body-center' },
            { targets: [4, 5], render: function (data, type, row) { return CirebonwebFormat.Tanggal(data); } },
            { targets: [4, 5, 6], orderable: false },
        ]
    });

    // Custom dropdown filter
    $('#tabelData_filter.dataTables_filter').append($('#filter_divisi, #filter_status'));
    $(document).on('change', '#filter_divisi, #filter_status', function () {
        $tabelData.DataTable().ajax.reload(null, false);
    });
});

// Reset Modal
$modalDiv.on('shown.bs.modal', function () { formCek.resetInitial() });
$modalDiv.on('hidden.bs.modal', function () { CirebonwebForm.FormReset(this) });

// Capitalize First Letter
$('#nama').on('keyup', function () {
    let start = this.selectionStart;
    let end = this.selectionEnd;
    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
    this.setSelectionRange(start, end);
});

// Status Checkbox
$(function () {
    $('#status_toggle').on('change', function () {
        $('#status').val($(this).prop('checked') ? '1' : '0');
    });
});

// Form Submit (Insert/Update)
function simpan(id) {
    let $modalTitle = $('.modal-title'),
        $btnSubmit = $('#btnSubmit'),
        postUrl = 'kategori-produk/simpan';

    // Tentukan URL POST: Simpan atau Update
    if (id > 0) {
        CirebonwebForm.FormFetch('kategori-produk/getid', { id }, 'POST', {})
            .done(function (response) {
                if (response.success) {
                    $modalTitle.text('Edit Data');
                    $btnSubmit.text('Update');

                    // Isi form
                    $formData.find('#id').val(response.data.id);
                    $formData.find('#nama').val(response.data.nama);

                    // Set status toggle & trigger change agar #status ikut terisi (1 / 0)
                    let statusVal = response.data.status == 1 ? 'on' : 'off';
                    $formData.find('#status_toggle').bootstrapToggle(statusVal).trigger('change');
                    $modalDiv.modal('show');
                }
            })
    } else {
        $('#id').val('');
        $modalTitle.text('Tambah Data');
        $btnSubmit.text('Simpan');

        // Default Tambah Data: Toggle ON & sync nilai input hidden ke '1'
        $formData.find('#status_toggle').bootstrapToggle('on').trigger('change');
        $modalDiv.modal('show');
    }

    // Form Validasi
    $formData.validate($.extend(CirebonwebForm.FormValidasi(), {
        rules: {
            divisi: { required: true, digits: true },
            nama: { required: true, minlength: 3, maxlength: 50 }
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
    }))
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
            CirebonwebForm.FormFetch('kategori-produk/hapus', { id }, 'POST', {})
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