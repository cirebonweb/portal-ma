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

service('auth')->routes($routes);
