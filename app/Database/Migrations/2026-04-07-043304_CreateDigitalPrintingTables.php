<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDigitalPrintingTables extends Migration
{
    public function up()
    {
        // konsumen_tipe → (Master) Tipe Konsumen
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 20, 'unique' => true], // Retail, Corporate, Karyawan
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('konsumen_tipe');

        // konsumen → (Data) Konsumen
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_tipe_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // Default 1:Retail
            'user_id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // Persiapan jika konsumen bisa login
            'nama'             => ['type' => 'varchar', 'constraint' => 30],
            'perusahaan'       => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'alamat'           => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kota'             => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'whatsapp'         => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'telegram_id'      => ['type' => 'varchar', 'constraint' => 20, 'null' => true], // Skip untuk saat ini
            'email'            => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'divisi'           => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Umum, 1:Printing, 2:Advertising
            'created_at'       => ['type' => 'timestamp', 'null' => true],
            'updated_at'       => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_tipe_id', 'konsumen_tipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('konsumen');

        // supplier → (Data) Supplier
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 30],
            'perusahaan' => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'alamat'     => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kota'       => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'kontak'     => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'email'      => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('supplier');

        // mesin_tipe → (Master) Tipe Mesin | mengelompokkan nama 'mesin' dan 'bahan'
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 40, 'unique' => true], // Outdoor, Indoor, Printing (Outdoor, Indoor), Cutting, Press Mug, Sablon Kaos
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('mesin_tipe');

        // mesin → (Data) Mesin | menentukan bahan sisa atau limbah
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'mesin_tipe_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nama'          => ['type' => 'varchar', 'constraint' => 30, 'unique' => true],
            'print_area'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Tidak, 1:Ya = min-max wajib diisi
            'min_lebar'     => ['type' => 'decimal', 'constraint' => '5,2', 'null' => true],
            'max_lebar'     => ['type' => 'decimal', 'constraint' => '5,2', 'null' => true],
            'min_panjang'   => ['type' => 'decimal', 'constraint' => '5,2', 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mesin_tipe_id', 'mesin_tipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('mesin');

        // bahan → (Bahan) Data Bahan
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'mesin_tipe_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nama'          => ['type' => 'varchar', 'constraint' => 30],
            'kode'          => ['type' => 'varchar', 'constraint' => 5], // input manual, misal: FLX untuk Flexy, OWY untuk One Way
            'gsm'           => ['type' => 'smallint', 'constraint' => 6, 'default' => 0],
            'lebar'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'isi_paket'     => ['type' => 'decimal', 'constraint' => '5,2', 'null' => true],   // jumlah satuan_1 dalam satu paket; otomatis dari lebar × panjang atau input manual
            'satuan_1'      => ['type' => 'varchar', 'constraint' => 10, 'default' => 'm²'],   // satuan isi stok (kecil)
            'satuan_2'      => ['type' => 'varchar', 'constraint' => 10, 'default' => 'roll'], // satuan paket pembelian (besar)
            'rumus'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],       // 0:Perkalian luas, 1:Perkalian qty
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mesin_tipe_id', 'mesin_tipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('bahan');

        // bahan_order → (Bahan) Order Bahan | pembelian bahan baku yang digunakan langsung mesin
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'supplier_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tgl_order'   => ['type' => 'date'],
            'no_order'    => ['type' => 'varchar', 'constraint' => 50, 'null' => true], // nomor: invoice, faktur, nota, po
            'total_qty'   => ['type' => 'smallint', 'constraint' => 6, 'default' => 0], // total_qty = total dari bahan_order_isi.qty
            'subtotal'    => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // kalkulasi dari bahan_order_isi.jumlah (disabled)
            'ongkir'      => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // ongkos kirim atau biaya tambahan
            'total'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // total = subtotal + ongkir (disabled)
            'status_stok' => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],  // 0:Stok bahan belum ditambahkan, 1:Stok bahan sudah ditambahkan
            'keterangan'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_buat'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_ubah'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'timestamp', 'null' => true],
            'updated_at'  => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tgl_order');
        $this->forge->addKey('no_order');
        $this->forge->addForeignKey('supplier_id', 'supplier', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_buat', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_ubah', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('bahan_order');

        // bahan_order_isi | detail item bahan_order
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bahan_order_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'bahan_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // nama bahan yang dibeli
            'harga_satuan'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // pembagian dari harga_paket berdasarkan bahan.rumus
            'harga_paket'    => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // harga paket pembelian
            'qty'            => ['type' => 'smallint', 'constraint' => 6, 'default' => 0], // qty paket pembelian
            'jumlah'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],     // jumlah = harga_paket x qty
            'keterangan'     => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'timestamp', 'null' => true],
            'updated_at'     => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_order_id', 'bahan_order', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bahan_id', 'bahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('bahan_order_isi');

        // bahan_stok → (Bahan) Stok Bahan
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bahan_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'bahan_order_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'kode_bahan'     => ['type' => 'varchar', 'constraint' => 20, 'null' => true, 'unique' => true],
            'stok_masuk'     => ['type' => 'decimal', 'constraint' => '7,2', 'default' => 0.00], // isi satu paket dalam satuan_1
            'stok_pakai'     => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // stok_pakai = stok_pakai + cetak.luas
            'stok_sisa'      => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // stok_sisa = stok_masuk - stok_pakai
            'kondisi'        => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Baik, 1:Rusak, 2:Cacat
            'status'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Aktif, 1:Nonaktif, 2:Habis
            'keterangan'     => ['type' => 'varchar', 'constraint' => 100, 'null' => true], 
            'created_at'     => ['type' => 'timestamp', 'null' => true],
            'updated_at'     => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_id', 'bahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bahan_order_id', 'bahan_order', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('bahan_stok');

        // produk → (Data) Produk
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bahan_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // null untuk produk tanpa bahan
            'kategori'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Internal (antrean cetak & potong stok), 1:Eksternal, 2:Jasa/Layanan
            'nama'        => ['type' => 'varchar', 'constraint' => 100, 'unique' => true],
            'lebar'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'     => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'rumus'       => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Perkalian luas, 1:Perkalian qty
            'hpp'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'harga'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'promo'       => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'promo_awal'  => ['type' => 'date', 'null' => true],
            'promo_akhir' => ['type' => 'date', 'null' => true],
            'unggulan'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'status'      => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at'  => ['type' => 'timestamp', 'null' => true],
            'updated_at'  => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_id', 'bahan', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('produk');

        // harga_tipe → (Master) Tipe Harga
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_tipe_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // Kecuali Retail atau harga produk umum
            'produk_id'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'harga'            => ['type' => 'int', 'constraint' => 11],
            'created_at'       => ['type' => 'timestamp', 'null' => true],
            'updated_at'       => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_tipe_id', 'konsumen_tipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey(['konsumen_tipe_id', 'produk_id']);
        $this->forge->createTable('harga_tipe');

        // harga_khusus → (Master) Harga Khusus
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'produk_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'harga'       => ['type' => 'int', 'constraint' => 11],
            'created_at'  => ['type' => 'timestamp', 'null' => true],
            'updated_at'  => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey(['konsumen_id', 'produk_id']);
        $this->forge->createTable('harga_khusus');

        // finishing → (Master) Finishing
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 50, 'unique' => true],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('finishing');

        // nota → (Data) Nota
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'no_nota'        => ['type' => 'varchar', 'constraint' => 30, 'unique' => true],
            'tgl_nota'       => ['type' => 'date'],
            'subtotal'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'diskon_persen'  => ['type' => 'tinyint', 'constraint' => 3, 'default' => 0],
            'diskon_nominal' => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'nettotal'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'bayar'          => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'sisa'           => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'status_nota'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Belum Bayar, 1:Belum Lunas, 2:Lunas, 3:Hapus Nota
            'status_barang'  => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Belum Ambil, 1:Sudah Ambil
            'tgl_ambil'      => ['type' => 'date', 'null' => true],
            'keterangan'     => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_buat'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_ubah'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'timestamp', 'null' => true],
            'updated_at'     => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tgl_nota');
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_buat', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_ubah', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('nota');

        // nota_isi → (Data)
        $this->forge->addField([
            'id'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nota_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'produk_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'finishing_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tema'         => ['type' => 'varchar', 'constraint' => 100],
            'lebar'        => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'      => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'luas'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'qty'          => ['type' => 'smallint', 'constraint' => 6, 'default' => 0],
            'harga'        => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'jumlah'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            // Status alur kerja cetak (produksi)
            // 0: Draft, 1: Antrian, 2: Pending, 3: Proses, 4: Selesai, 5: Batal | WHERE nota_isi.status IN (1, 2)
            'status'       => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'keterangan'   => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'created_at'   => ['type' => 'timestamp', 'null' => true],
            'updated_at'   => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('nota_id', 'nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('finishing_id', 'finishing', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('nota_isi');

        // nota_bayar → (Data) Pembayaran
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nota_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'tanggal'    => ['type' => 'date'],
            'metode'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],   // 0:Tunai, 1:Transfer
            'file'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true], // Simpan lokasi dan upload file bukti transfer
            'diterima'   => ['type' => 'int', 'constraint' => 11, 'default' => 0], // uang yang diberikan konsumen (input)
            'jumlah'     => ['type' => 'int', 'constraint' => 11, 'default' => 0], // nominal yang dicatat sebagai pembayaran: nota.bayar (kunci)
            'kembalian'  => ['type' => 'int', 'constraint' => 11, 'default' => 0], // diterima - jumlah (kalkulasi)
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // User input pembayaran
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('nota_id', 'nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('nota_bayar');

        // cetak → (Data) Cetak
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'mesin_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'bahan_stok_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'bahan_sisa_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tanggal'       => ['type' => 'date'],
            'lebar'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'luas'          => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // lebar x panjang = (+) bahan_stok.stok_pakai
            'lokasi'        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],      // sumber lokasi file cetak tanpa upload file
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],       // nama operator
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mesin_id', 'mesin', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bahan_stok_id', 'bahan_stok', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('cetak');

        // cetak_isi → (Data)
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cetak_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nota_isi_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'created_at'  => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('cetak_id', 'cetak', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nota_isi_id', 'nota_isi', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('cetak_isi');

        // bahan_sisa → (Bahan) Sisa Bahan
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bahan_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'cetak_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'lebar'      => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'    => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'luas'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'status'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],   // 0:Tersedia, 1:Tercetak | tombol: Tidak layak cetak → bahan_limbah
            'keterangan' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_id', 'bahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cetak_id', 'cetak', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('bahan_sisa');

        // Tambahkan FK cetak → bahan_sisa setelah kedua tabel tersedia
        $this->db->query('ALTER TABLE cetak ADD CONSTRAINT fk_cetak_bahan_sisa FOREIGN KEY (bahan_sisa_id) REFERENCES bahan_sisa(id) ON DELETE SET NULL ON UPDATE CASCADE');

        // bahan_limbah → (Bahan) Limbah Bahan
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bahan_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'cetak_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'bahan_sisa_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'lebar'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'luas'          => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'jenis'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Operasional, 1:Lainnya, 2:Tidak layak cetak, 3:Masalah teknis
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bahan_id', 'bahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cetak_id', 'cetak', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bahan_sisa_id', 'bahan_sisa', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('bahan_limbah');

        // laporan → (Data) Laporan
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal'       => ['type' => 'date', 'unique' => true],
            'pemasukan'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pengeluaran'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pendapatan'    => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'tunai'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'transfer'      => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'diserahkan_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diterima_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diketahui_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'status'        => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0: Draft, 1: Dicetak, 2: Dikunci, 3: Dibuka, 4: Direvisi
            'locked_by'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'locked_at'     => ['type' => 'datetime', 'null' => true],
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_buat'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_ubah'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('diserahkan_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('diterima_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('diketahui_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('locked_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_buat', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_ubah', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('laporan');

        // laporan_isi → (Data)
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'laporan_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nota_id'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'no_faktur'   => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'nama'        => ['type' => 'varchar', 'constraint' => 100],
            'qty'         => ['type' => 'smallint', 'constraint' => 6, 'default' => 0],
            'satuan'      => ['type' => 'varchar', 'constraint' => 10, 'null' => true],
            'harga'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pemasukan'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pengeluaran' => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'keterangan'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'timestamp', 'null' => true],
            'updated_at'  => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('laporan_id', 'laporan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nota_id', 'nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('laporan_isi');

        // laporan_log → (Log) Log Laporan
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'laporan_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'aksi'       => ['type' => 'tinyint', 'constraint' => 1], // 0: Draft, 1: Dicetak, 2: Dikunci, 3: Dibuka, 4: Direvisi
            'catatan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('laporan_id', 'laporan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('laporan_log');
    }

    public function down()
    {
        // Drop Trigger Laporan
        $this->db->query("DROP TRIGGER IF EXISTS trg_laporan_isi_after_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_laporan_isi_after_update");
        $this->db->query("DROP TRIGGER IF EXISTS trg_laporan_isi_after_delete");

        // Drop Trigger Nota
        $this->db->query("DROP TRIGGER IF EXISTS trg_nota_isi_after_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_nota_isi_after_update");
        $this->db->query("DROP TRIGGER IF EXISTS trg_nota_isi_after_delete");

        // Drop Trigger Bahan Order
        $this->db->query("DROP TRIGGER IF EXISTS trg_bahan_order_isi_after_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_bahan_order_isi_after_update");
        $this->db->query("DROP TRIGGER IF EXISTS trg_bahan_order_isi_after_delete");

        // 23 Tabel → 18 Menu
        $this->forge->dropTable('laporan_log', true);
        $this->forge->dropTable('laporan_isi', true);
        $this->forge->dropTable('laporan', true);
        $this->db->query('ALTER TABLE cetak DROP FOREIGN KEY fk_cetak_bahan_sisa');
        $this->forge->dropTable('bahan_limbah');
        $this->forge->dropTable('bahan_sisa');
        $this->forge->dropTable('cetak_isi', true);
        $this->forge->dropTable('cetak', true);
        $this->forge->dropTable('nota_bayar', true);
        $this->forge->dropTable('nota_isi', true);
        $this->forge->dropTable('nota', true);
        $this->forge->dropTable('finishing', true);
        $this->forge->dropTable('harga_khusus', true);
        $this->forge->dropTable('harga_tipe', true);
        $this->forge->dropTable('produk', true);
        $this->forge->dropTable('bahan_order_isi', true);
        $this->forge->dropTable('bahan_order', true);
        $this->forge->dropTable('bahan_stok', true);
        $this->forge->dropTable('bahan', true);
        $this->forge->dropTable('mesin');
        $this->forge->dropTable('mesin_tipe');
        $this->forge->dropTable('supplier', true);
        $this->forge->dropTable('konsumen', true);
        $this->forge->dropTable('konsumen_tipe', true);
    }
}
