<?php

if (!function_exists('getSatuan')) {
    /**
     * Mengembalikan daftar satuan umum untuk Percetakan, Digital Printing, & Advertising.
     */
    function getSatuan(): array
    {
        return [
            'm'         => 'm',
            'm²'        => 'm²',
            'mm'        => 'mm',
            'cm'        => 'cm',
            'cm²'       => 'cm²',
            'a5'        => 'a5',
            'a4'        => 'a4',
            'a3'        => 'a3',
            'a3+'       => 'a3+',
            'a2'        => 'a2',
            'a1'        => 'a1',
            'buku'      => 'buku',
            'box'       => 'box',
            'buah'      => 'buah',
            'dus'       => 'dus',
            'gram'      => 'gram',
            'jam'       => 'jam',
            'kg'        => 'kg',
            'kilogram'  => 'kilogram',
            'lembar'    => 'lembar',
            'liter'     => 'liter',
            'pak'       => 'pak',
            'paket'     => 'paket',
            'pcs'       => 'pcs',
            'plano'     => 'plano',
            'play'      => 'play',
            'rim'       => 'rim',
            'roll'      => 'roll',
            'set'       => 'set',
            'sisi'      => 'sisi',
            'titik'     => 'titik',
            'unit'      => 'unit',
            '1 muka'    => '1 muka',
            '2 muka'    => '2 muka',
        ];
    }
}
