<?php

if (! function_exists('formatDesimal')) {
    /**
     * Memformat angka dan menghapus trailing zeros serta titik jika tidak diperlukan.
     * 
     * @param mixed $val
     * @return string
     */
    function formatDesimal($val)
    {
        return rtrim(rtrim(number_format((float)$val, 2, '.', ''), '0'), '.');
    }
}

if (! function_exists('formatRupiah')) {
    /**
     * Memformat angka menjadi format rupiah.
     * 
     * @param mixed $val
     * @return string
     */
    function formatRupiah($val)
    {
        return 'Rp ' . number_format((float) $val, 0, ',', '.');
    }
}
