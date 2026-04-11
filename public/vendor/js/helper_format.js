/*!
 * CirebonWeb (https://cirebonweb.com)
 * Copyright (c) 2023 CirebonWeb
 * Licensed under MIT (https://opensource.org/licenses/MIT)
 */

/**
 * Objek global untuk mengelola fungsionalitas format.
 * Mengandung fungsi-fungsi helper untuk memformat data seperti tanggal dan angka.
 */
const CirebonwebFormat = {
    /**
     * Memformat tanggal dari YYYY-MM-DD atau DD-MM-YYYY menjadi DD-MM-YYYY.
     *
     * @param {string} value String tanggal yang akan diformat.
     * @returns {string} Tanggal yang diformat atau string asli jika format tidak dikenali.
     */
    Tanggal: function (value, mode = 'full') {
        if (!value) return '-';

        const dateObj = new Date(value);

        const optionsDate = { day: '2-digit', month: 'short', year: 'numeric' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

        const tanggal = dateObj.toLocaleDateString('id-ID', optionsDate);
        const waktu = dateObj.toLocaleTimeString('id-ID', optionsTime);

        if (mode === 'tanggal') return tanggal;
        return `${tanggal} → ${waktu}`;
    },

    /**
     * Memformat angka menjadi format Rupiah (dengan titik sebagai pemisah ribuan).
     *
     * @param {string|number} value Angka yang akan diformat.
     * @returns {string} String dengan format Rupiah.
     */
    Rupiah: function (value) {
        // Konversi ke string dan hapus semua karakter non-digit
        const stringValue = String(value).replace(/\D/g, '');
        // Tambahkan titik sebagai pemisah ribuan
        return stringValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    /**
     * Menghilangkan format Rupiah (menghapus titik dan karakter non-digit lainnya).
     *
     * @param {string} value String dengan format Rupiah.
     * @returns {string} String yang hanya berisi digit.
     */
    unRupiah: function (value) {
        return String(value).replace(/\D/g, '');
    },

    /**
     * Memformat angka ke format lokal (ID) dengan pemisah ribuan dan desimal.
     * Ini adalah cara yang lebih baik daripada formatRupiah karena menggunakan API bawaan browser.
     *
     * @param {number} value Angka yang akan diformat.
     * @returns {string} String angka yang diformat secara lokal.
     */
    lokasiRupiah: function (value) {
        if (typeof value !== 'number' || isNaN(value)) return '';
        return value.toLocaleString('id-ID');
    },
};