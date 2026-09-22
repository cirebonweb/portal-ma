<?php

if (! function_exists('versi')) {
    /**
     * Membaca file asset dan menambahkan timestamp modifikasi terakhir sebagai cache buster.
     * 
     * @param string $path Jalur file relatif terhadap folder public/ (misal: 'dist/css/adminlte.min.css')
     * @return string URL lengkap dengan query string versi
     */
    function versi(string $path): string
    {
        // Cari lokasi fisik file di dalam folder public
        $filepath = FCPATH . $path;

        if (file_exists($filepath)) {
            // Mengambil waktu modifikasi terakhir file (unix timestamp)
            $version = filemtime($filepath);
            return base_url($path) . '?v=' . $version;
        }

        return base_url($path);
    }
}
