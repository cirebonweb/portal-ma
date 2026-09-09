<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('umum', function ($routes) {
    
    $routes->get('', 'Umum\KategoriKonsumen::index');

    $routes->group('kategori-konsumen', function ($routes) {
        $routes->get('', 'Umum\KategoriKonsumen::index');
        $routes->get('tabel', 'Umum\KategoriKonsumen::tabel');
        $routes->post('tabel', 'Umum\KategoriKonsumen::tabel');
        $routes->post('getid', 'Umum\KategoriKonsumen::getId');
        $routes->post('simpan', 'Umum\KategoriKonsumen::simpan');
        $routes->post('hapus', 'Umum\KategoriKonsumen::hapus');
    });

    $routes->group('konsumen', function ($routes) {
        $routes->get('', 'Umum\Konsumen::index');
        $routes->get('tabel', 'Umum\Konsumen::tabel');
        $routes->post('tabel', 'Umum\Konsumen::tabel');
        $routes->post('getid', 'Umum\Konsumen::getId');
        $routes->post('simpan', 'Umum\Konsumen::simpan');
        $routes->post('hapus', 'Umum\Konsumen::hapus');
    });
});

$routes->group('printing', function ($routes) {

    $routes->addRedirect('', 'printing/mesin');

    $routes->group('mesin', function ($routes) {
        $routes->get('', 'Printing\DpMesin::index');
        $routes->get('tabel', 'Printing\DpMesin::tabel');
        $routes->post('tabel', 'Printing\DpMesin::tabel');
        $routes->post('getid', 'Printing\DpMesin::getId');
        $routes->post('simpan', 'Printing\DpMesin::simpan');
        $routes->post('hapus', 'Printing\DpMesin::hapus');
    });

    $routes->group('bahan', function ($routes) {
        $routes->get('', 'Printing\DpBahan::index');
        $routes->get('tabel', 'Printing\DpBahan::tabel');
        $routes->post('tabel', 'Printing\DpBahan::tabel');
        $routes->post('getid', 'Printing\DpBahan::getId');
        $routes->post('simpan', 'Printing\DpBahan::simpan');
        $routes->post('hapus', 'Printing\DpBahan::hapus');
    });

    $routes->group('produk', function ($routes) {
        $routes->get('', 'Printing\DpProduk::index');
        $routes->get('tabel', 'Printing\DpProduk::tabel');
        $routes->post('tabel', 'Printing\DpProduk::tabel');
        $routes->post('getid', 'Printing\DpProduk::getId');
        $routes->post('simpan', 'Printing\DpProduk::simpan');
        $routes->post('hapus', 'Printing\DpProduk::hapus');
    });

    $routes->group('harga-level', function ($routes) {
        $routes->get('', 'Printing\DpHargaLevel::index');
        $routes->get('tabel', 'Printing\DpHargaLevel::tabel');
        $routes->post('tabel', 'Printing\DpHargaLevel::tabel');
        $routes->post('getid', 'Printing\DpHargaLevel::getId');
        $routes->post('simpan', 'Printing\DpHargaLevel::simpan');
        $routes->post('hapus', 'Printing\DpHargaLevel::hapus');
    });

    $routes->group('harga-khusus', function ($routes) {
        $routes->get('', 'Printing\DpHargaKhusus::index');
        $routes->get('tabel', 'Printing\DpHargaKhusus::tabel');
        $routes->post('tabel', 'Printing\DpHargaKhusus::tabel');
        $routes->post('getid', 'Printing\DpHargaKhusus::getId');
        $routes->post('simpan', 'Printing\DpHargaKhusus::simpan');
        $routes->post('hapus', 'Printing\DpHargaKhusus::hapus');
    });
});

service('auth')->routes($routes);
