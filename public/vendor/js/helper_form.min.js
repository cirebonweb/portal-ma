/*!
 * CirebonWeb (https://cirebonweb.com)
 * Copyright (c) 2023 CirebonWeb
 * Licensed under MIT (https://opensource.org/licenses/MIT)
 */

if (typeof $.validator !== 'undefined') {
    $.validator.addMethod("customEmail", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(value);
    }, "Email tidak valid (contoh: user@example.com)");

    $.validator.addMethod('ipv4', function (value, element) {
        return this.optional(element) ||
            /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(\d{1,3}\.){2}\d{1,3}$/.test(value);
    }, "Masukkan IP valid.");
}

let loading_requests = 0;

/**
 * Cirebonweb Form Helper
 * @property {resetForm, cekForm, validasiForm}
 *
 * @param {string|object} target Selector atau elemen jQuery yang mengandung form.
 */
const CirebonwebForm = {
    /**
     * Reset validasi form
     * Hapus class, feedback dan reset nilai input.
     *
     * @param {string|object} target Selector atau elemen jQuery yang mengandung form.
     */
    FormReset: function (target) {
        const $target = $(target);
        const form = $target.find('form')[0];
        if (form) form.reset();
        $target.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $target.find('.invalid-feedback, .valid-feedback').remove();
    },

    /**
     * Cek perubahan valur pada input form.
     * Menggunakan closure untuk menyimpan data awal form.
     *
     * @param {object} formSelector Elemen form jQuery atau selector string.
     * @returns {{isChanged: function, resetInitial: function}}
     */
    FormCek: function (formSelector) {
        let $form = $(formSelector);
        if ($form.attr('data-cek') !== 'true') {
            return { isChanged: () => true, resetInitial: () => { } };
        }

        let initialData = {};

        function setInitialData() {
            initialData = {};
            $form.find('input, select, textarea').each(function () {
                let name = $(this).attr('name');
                if (!name) return;
                if ($(this).is(':checkbox, :radio')) {
                    initialData[name] = $(this).is(':checked');
                } else {
                    initialData[name] = $(this).val();
                }
            });
        }

        function checkChanged() {
            let changed = false;
            $form.find('input, select, textarea').each(function () {
                let name = $(this).attr('name');
                if (!name) return;
                let currentVal = $(this).is(':checkbox, :radio') ? $(this).is(':checked') : $(this).val();
                if (currentVal != initialData[name]) {
                    changed = true;
                    return false;
                }
            });

            if (!changed) {
                $form.find(':input').removeClass('is-invalid is-valid');
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    html: 'Data input tidak ada perubahan. <br> Proses simpan dibatalkan.',
                });
            }
            return changed;
        }

        setInitialData();
        return { isChanged: checkChanged, resetInitial: setInitialData };
    },

    /**
     * Fungsi untuk mengatur opsi validasi form
     * Mengembalikan konfigurasi validasi jQuery.validate
     * yang dapat digunakan kembali untuk berbagai jenis form.
     */
    FormValidasi: function () {
        return {
            ignore: [],
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function (error, element) {
                const $parent = element.closest('.input-group, .form-group');
                if ($parent.length) {
                    $parent.append(error);
                } else if (element.is('.select') || element.hasClass('select2') || element.hasClass('selectpicker')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },
        };
    },

    /**
     * Fungsi untuk menampilkan status loading global (misalnya, spinner)
     */
    showLoading: function () {
        if (loading_requests === 0) {
            // Logika menampilkan loading spinner/overlay di sini
            console.log("-> Tampilkan Loading: Mulai proses AJAX.");
            Beranda.showPreloader();
        }
        loading_requests++;
    },

    /**
     * Fungsi untuk menyembunyikan status loading global
     */
    hideLoading: function () {
        loading_requests--;
        if (loading_requests <= 0) {
            loading_requests = 0; // Pastikan tidak negatif
            // Logika menyembunyikan loading spinner/overlay di sini
            console.log("<- Sembunyikan Loading: Semua request AJAX selesai.");
            Beranda.hidePreloader();
        }
    },

    /**
     * Fungsi utama untuk mengirim request AJAX.
     * @param {string} url - URL tujuan di Controller CI4.
     * @param {Object} data - Data yang akan dikirim.
     * @param {string} method - Metode HTTP (GET, POST, PUT, DELETE). Default: 'POST'.
     * @param {Object} options - Opsi tambahan untuk $.ajax().
     * @returns {jqXHR} - Objek jqXHR (Promise) untuk chaining (.done(), .fail(), .always()).
     */
    FormFetch: function (url, data = {}, method = {}, options = {}) {

        // --- 1. Jalankan Callback Kustom BEFORE ---
        // Ini memungkinkan manipulasi DOM lokal (misalnya, tombol dinonaktifkan)
        if (options.before_call && typeof options.before_call === 'function') {
            options.before_call();
        }

        // Konfigurasi default untuk request data (bukan file upload)
        const default_options = {
            url: url,
            method: method.toUpperCase(),
            data: data,
            dataType: 'json', // Default: Mengharap respons JSON
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Data standar
            processData: true,
            cache: false, // Penting untuk GET agar tidak di-cache browser
            // Tambahkan timeout atau headers default lainnya di sini jika diperlukan
        };

        // Gabungkan opsi default dengan opsi kustom
        // Gunakan deep extend (true) untuk menggabungkan options dengan benar
        const final_options = $.extend(true, {}, default_options, options);

        // Tampilkan indikator loading global sebelum request
        this.showLoading();

        // Kirim request AJAX
        const jqXHR = $.ajax(final_options);

        jqXHR.done(function (response) {
            if (response.success) {
                Beranda.refreshCsrf();
                // Swal.fire('Sukses', response.messages, 'success');
            } else {
                let pesan = '';

                if (typeof response.messages === 'object') {
                    pesan = Object.values(response.messages).join('<br>');
                } else {
                    pesan = response.messages || 'Pesan kesalahan tidak dikenali.';
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    html: pesan
                });
            }
        })

        // Penanganan error global (Error HTTP, Timeout, Abort)
        jqXHR.fail(function (jqXHR, textStatus, errorThrown) {
            let error_message = "Info: ";

            if (jqXHR.status === 0) {
                error_message += "Gagal terhubung ke server atau request dibatalkan.";
            } else if (jqXHR.status >= 400 && jqXHR.status < 500) {
                error_message += `URL tidak ditemukan atau akses ditolak. ${jqXHR.status}.`;
            } else if (jqXHR.status >= 500) {
                error_message += `Kesalahan internal server. ${jqXHR.status}.`;
            } else if (textStatus === 'parsererror') {
                error_message += "Respons server tidak valid (bukan JSON). Cek format data di Controller.";
            } else if (textStatus === 'timeout') {
                error_message += "Waktu tunggu (timeout) request habis.";
            } else {
                error_message += `Kesalahan tidak terduga: ${errorThrown}.`;
            }

            console.error(error_message, jqXHR);
            Swal.fire('Error', error_message, 'error');
        });

        // Penanganan Complete (selalu dipanggil setelah sukses/gagal)
        jqXHR.always(function () {
            // --- 2. Jalankan Callback Kustom AFTER ---
            // Ini memungkinkan pembersihan DOM lokal (misalnya, tombol diaktifkan kembali)
            if (options.after_call && typeof options.after_call === 'function') {
                options.after_call();
            }

            // Sembunyikan indikator loading global
            CirebonwebForm.hideLoading();
        });

        // Kembalikan Promise (jqXHR) untuk penanganan spesifik
        return jqXHR;
    }
};
