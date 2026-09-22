<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Dashboard
$routes->get('/', 'Home::index');
// $routes->get('/', 'Dashboard::index');

// ============================================================================
// KELOMPOK: BAHAN (Folder: Controllers/Bahan/)
// ============================================================================
$routes->group('', ['namespace' => 'App\Controllers\Bahan'], function ($routes) {

    // URL: /bahan
    $routes->group('bahan', function ($routes) {
        $routes->get('', 'Bahan::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Bahan::tabel');
        $routes->post('getid', 'Bahan::getId');
        $routes->post('simpan', 'Bahan::simpan');
        $routes->post('hapus', 'Bahan::hapus');
    });

    // URL: /bahan-order
    $routes->group('bahan-order', function ($routes) {
        $routes->get('', 'BahanOrder::index');
        $routes->match(['GET', 'POST'], 'tabel', 'BahanOrder::tabel');
        $routes->post('getid', 'BahanOrder::getId');
        $routes->post('simpan', 'BahanOrder::simpan');
        $routes->post('tambahstok', 'BahanOrder::tambahStok');
        $routes->post('gettotal', 'BahanOrder::getTotal');
    });

    // URL: /bahan-order/isi
    $routes->group('bahan-order/isi', function ($routes) {
        $routes->get('', 'BahanOrderIsi::index');
        $routes->match(['GET', 'POST'], 'tabel', 'BahanOrderIsi::tabel');
        $routes->post('getid', 'BahanOrderIsi::getId');
        $routes->post('getbahanmesin', 'BahanOrderIsi::getBahanMesin');
        $routes->post('simpan', 'BahanOrderIsi::simpan');
        $routes->post('hapus', 'BahanOrderIsi::hapus');
    });

    // URL: /bahan-stok
    $routes->group('bahan-stok', function ($routes) {
        $routes->get('', 'BahanStok::index');
        $routes->match(['GET', 'POST'], 'tabel', 'BahanStok::tabel');
        $routes->post('getid', 'BahanStok::getId');
        $routes->post('simpan', 'BahanStok::simpan');
    });

    // URL: /bahan-sisa
    // $routes->group('bahan-sisa', function ($routes) {
    //     $routes->get('', 'BahanSisa::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'BahanSisa::tabel');
    //     $routes->post('getid', 'BahanSisa::getId');
    //     $routes->post('simpan', 'BahanSisa::simpan');
    //     $routes->post('hapus', 'BahanSisa::hapus');
    // });

    // URL: /bahan-limbah
    // $routes->group('bahan-limbah', function ($routes) {
    //     $routes->get('', 'BahanLimbah::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'BahanLimbah::tabel');
    //     $routes->post('getid', 'BahanLimbah::getId');
    //     $routes->post('simpan', 'BahanLimbah::simpan');
    //     $routes->post('hapus', 'BahanLimbah::hapus');
    // });
});


// ============================================================================
// KELOMPOK: DATA (Folder: Controllers/Data/)
// ============================================================================
$routes->group('', ['namespace' => 'App\Controllers\Data'], function ($routes) {

    // URL: /konsumen
    $routes->group('konsumen', function ($routes) {
        $routes->get('', 'Konsumen::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Konsumen::tabel');
        $routes->post('getid', 'Konsumen::getId');
        $routes->post('simpan', 'Konsumen::simpan');
        $routes->post('hapus', 'Konsumen::hapus');
    });

    // URL: /supplier
    $routes->group('supplier', function ($routes) {
        $routes->get('', 'Supplier::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Supplier::tabel');
        $routes->post('getid', 'Supplier::getId');
        $routes->post('simpan', 'Supplier::simpan');
        $routes->post('hapus', 'Supplier::hapus');
    });

    // URL: /mesin
    $routes->group('mesin', function ($routes) {
        $routes->get('', 'Mesin::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Mesin::tabel');
        $routes->post('getid', 'Mesin::getId');
        $routes->post('simpan', 'Mesin::simpan');
        $routes->post('hapus', 'Mesin::hapus');
    });

    // URL: /produk
    $routes->group('produk', function ($routes) {
        $routes->get('', 'Produk::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Produk::tabel');
        $routes->post('getid', 'Produk::getId');
        $routes->post('simpan', 'Produk::simpan');
        $routes->post('hapus', 'Produk::hapus');
    });

    // URL: /nota
    // $routes->group('nota', function ($routes) {
    //     $routes->get('', 'Nota::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'Nota::tabel');
    //     $routes->post('getid', 'Nota::getId');
    //     $routes->post('simpan', 'Nota::simpan');
    //     $routes->post('hapus', 'Nota::hapus');
    // });

    // URL: /pembayaran (Memanggil Controller Pembayaran)
    // $routes->group('pembayaran', function ($routes) {
    //     $routes->get('', 'Pembayaran::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'Pembayaran::tabel');
    //     $routes->post('getid', 'Pembayaran::getId');
    //     $routes->post('simpan', 'Pembayaran::simpan');
    //     $routes->post('hapus', 'Pembayaran::hapus');
    // });

    // URL: /laporan
    // $routes->group('laporan', function ($routes) {
    //     $routes->get('', 'Laporan::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'Laporan::tabel');
    //     $routes->post('getid', 'Laporan::getId');
    //     $routes->post('simpan', 'Laporan::simpan');
    //     $routes->post('hapus', 'Laporan::hapus');
    // });

    // URL: /finishing
    // $routes->group('finishing', function ($routes) {
    //     $routes->get('', 'Finishing::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'Finishing::tabel');
    //     $routes->post('getid', 'Finishing::getId');
    //     $routes->post('simpan', 'Finishing::simpan');
    //     $routes->post('hapus', 'Finishing::hapus');
    // });



    // URL: /cetak
    // $routes->group('cetak', function ($routes) {
    //     $routes->get('', 'Cetak::index');
    //     $routes->match(['GET', 'POST'], 'tabel', 'Cetak::tabel');
    //     $routes->post('getid', 'Cetak::getId');
    //     $routes->post('simpan', 'Cetak::simpan');
    //     $routes->post('hapus', 'Cetak::hapus');
    // });
});


// ============================================================================
// KELOMPOK: MASTER (Folder: Controllers/Master/)
// ============================================================================
$routes->group('', ['namespace' => 'App\Controllers\Master'], function ($routes) {

    // URL: /konsumen-tipe
    $routes->group('konsumen-tipe', function ($routes) {
        $routes->get('', 'KonsumenTipe::index');
        $routes->match(['GET', 'POST'], 'tabel', 'KonsumenTipe::tabel');
        $routes->post('getid', 'KonsumenTipe::getId');
        $routes->post('simpan', 'KonsumenTipe::simpan');
        $routes->post('hapus', 'KonsumenTipe::hapus');
    });

    // URL: /mesin-tipe
    $routes->group('mesin-tipe', function ($routes) {
        $routes->get('', 'MesinTipe::index');
        $routes->match(['GET', 'POST'], 'tabel', 'MesinTipe::tabel');
        $routes->post('getid', 'MesinTipe::getId');
        $routes->post('simpan', 'MesinTipe::simpan');
        $routes->post('hapus', 'MesinTipe::hapus');
    });

    // URL: /harga-tipe
    $routes->group('harga-tipe', function ($routes) {
        $routes->get('', 'HargaTipe::index');
        $routes->match(['GET', 'POST'], 'tabel', 'HargaTipe::tabel');
        $routes->post('getid', 'HargaTipe::getId');
        $routes->post('simpan', 'HargaTipe::simpan');
        $routes->post('hapus', 'HargaTipe::hapus');
    });

    // URL: /harga-khusus
    $routes->group('harga-khusus', function ($routes) {
        $routes->get('', 'HargaKhusus::index');
        $routes->match(['GET', 'POST'], 'tabel', 'HargaKhusus::tabel');
        $routes->post('getid', 'HargaKhusus::getId');
        $routes->post('simpan', 'HargaKhusus::simpan');
        $routes->post('hapus', 'HargaKhusus::hapus');
    });

    // URL: /finishing
    $routes->group('finishing', function ($routes) {
        $routes->get('', 'Finishing::index');
        $routes->match(['GET', 'POST'], 'tabel', 'Finishing::tabel');
        $routes->post('getid', 'Finishing::getId');
        $routes->post('simpan', 'Finishing::simpan');
        $routes->post('hapus', 'Finishing::hapus');
    });
});


// ============================================================================
// KELOMPOK: LOG (Folder: Controllers/Log/)
// ============================================================================
// $routes->group('', ['namespace' => 'App\Controllers\Log'], function ($routes) {

//     // URL: /laporan-log
//     $routes->group('laporan-log', function ($routes) {
//         $routes->get('', 'LaporanLog::index');
//         $routes->match(['GET', 'POST'], 'tabel', 'LaporanLog::tabel');
//         $routes->post('getid', 'LaporanLog::getId');
//         $routes->post('simpan', 'LaporanLog::simpan');
//         $routes->post('hapus', 'LaporanLog::hapus');
//     });
// });


service('auth')->routes($routes);
