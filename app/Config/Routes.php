<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('umum', function ($routes) {
    
    $routes->get('', 'Umum\LevelHarga::index');

    $routes->group('level-harga', function ($routes) {
        $routes->get('', 'Umum\LevelHarga::index');
        $routes->get('tabel', 'Umum\LevelHarga::tabel');
        $routes->post('tabel', 'Umum\LevelHarga::tabel');
        $routes->post('getid', 'Umum\LevelHarga::getId');
        $routes->post('simpan', 'Umum\LevelHarga::simpan');
        $routes->post('hapus', 'Umum\LevelHarga::hapus');
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
});

service('auth')->routes($routes);
