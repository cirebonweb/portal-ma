<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDigitalPrintingTables extends Migration
{
    public function up()
    {
        // 1. Tabel: kategori_konsumen
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 20, 'unique' => true], // Retail, Corporate, Karyawan
            'status'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_konsumen');

        // 2. Tabel: konsumen
        $this->forge->addField([
            'id'                   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_konsumen_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_id'              => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'                 => ['type' => 'varchar', 'constraint' => 40],
            'perusahaan'           => ['type' => 'varchar', 'constraint' => 40, 'null' => true],
            'alamat'               => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kota'                 => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'whatsapp'             => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'telegram_id'          => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'email'                => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'divisi'               => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:umum, 1:printing, 2:advertising
            'status'               => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at'           => ['type' => 'timestamp', 'null' => true],
            'updated_at'           => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_konsumen_id', 'kategori_konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('konsumen');

        // 3. Tabel: kategori_produk
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'divisi'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0: Umum, 1: Printing, 2: Advertising, 3: Partner
            'nama'       => ['type' => 'varchar', 'constraint' => 50], // Mesin Outdoor, Jasa dan Layanan, Cetak Offset
            'status'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['divisi', 'nama']);
        $this->forge->createTable('kategori_produk');

        // 4. Tabel: dp_bahan
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'varchar', 'constraint' => 30, 'unique' => true],
            'status'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at' => ['type' => 'timestamp', 'null' => true],
            'updated_at' => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dp_bahan');

        // 5. Tabel: dp_produk
        $this->forge->addField([
            'id'                 => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_produk_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'dp_bahan_id'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'               => ['type' => 'varchar', 'constraint' => 100, 'unique' => true],
            'lebar'              => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'            => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'satuan'             => ['type' => 'varchar', 'constraint' => 10, 'default' => 'm²'],
            'hpp'                => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'harga'              => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'promo'              => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'promo_awal'         => ['type' => 'date', 'null' => true],
            'promo_akhir'        => ['type' => 'date', 'null' => true],
            'rumus'              => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:perkalian luas, 1:perkalian qty
            'unggulan'           => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'status'             => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at'         => ['type' => 'timestamp', 'null' => true],
            'updated_at'         => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_produk_id', 'kategori_produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_bahan_id', 'dp_bahan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_produk');

        // 6. Tabel: dp_kategori_harga
        $this->forge->addField([
            'id'                   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_konsumen_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'harga'                => ['type' => 'int', 'constraint' => 11],
            'status'               => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
            'created_at'           => ['type' => 'timestamp', 'null' => true],
            'updated_at'           => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_konsumen_id', 'kategori_konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey(['kategori_konsumen_id', 'dp_produk_id']);
        $this->forge->createTable('dp_kategori_harga');

        // 7. Tabel: dp_harga_khusus
        $this->forge->addField([
            'id'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'harga'        => ['type' => 'int', 'constraint' => 11],
            'created_at'   => ['type' => 'timestamp', 'null' => true],
            'updated_at'   => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey(['konsumen_id', 'dp_produk_id']);
        $this->forge->createTable('dp_harga_khusus');

        // 8. Tabel: dp_nota
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'date'],
            'nota'           => ['type' => 'varchar', 'constraint' => 30, 'unique' => true],
            'subtotal'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'diskon_persen'  => ['type' => 'tinyint', 'constraint' => 3, 'default' => 0],
            'diskon_nominal' => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'nettotal'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'bayar'          => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'sisa'           => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'status_nota'    => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Belum Bayar, 1:Belum Lunas, 2:Lunas, 3:Hapus Nota, 4:Retur Nota
            'status_barang'  => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Belum Ambil, 1:Sudah Ambil
            'tgl_ambil'      => ['type' => 'date', 'null' => true],
            'keterangan'     => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_buat'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_ubah'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'timestamp', 'null' => true],
            'updated_at'     => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('user_buat', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_ubah', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_nota');

        // 9. Tabel: dp_nota_isi
        $this->forge->addField([
            'id'           => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_nota_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'tema'         => ['type' => 'varchar', 'constraint' => 100],
            'lebar'        => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'      => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'luas'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00],
            'qty'          => ['type' => 'smallint', 'constraint' => 6, 'default' => 0],
            'harga_satuan' => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'subtotal'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'hpp'          => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'created_at'   => ['type' => 'timestamp', 'null' => true],
            'updated_at'   => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_nota_isi');

        // 10. Tabel: dp_nota_bayar
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'dp_nota_id'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'date'],
            'metode'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Tunai, 1:Transfer
            'bukti_transfer' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'diterima'       => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'jumlah'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'kembalian'      => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'created_at'     => ['type' => 'timestamp', 'null' => true],
            'updated_at'     => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_nota_bayar');

        // 11. Tabel: dp_laporan
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal'       => ['type' => 'date'],
            'pemasukan'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pengeluaran'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pendapatan'    => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'tunai'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'transfer'      => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'diserahkan_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diterima_id'   => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diketahui_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'status'        => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'locked_by'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'locked_at'     => ['type' => 'datetime', 'null' => true],
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_buat'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'user_ubah'     => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('diserahkan_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('diterima_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('diketahui_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('locked_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_buat', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_ubah', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey('tanggal');
        $this->forge->createTable('dp_laporan');

        // 12. Tabel: dp_laporan_isi
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_laporan_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'dp_nota_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nota'          => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'nama'          => ['type' => 'varchar', 'constraint' => 100],
            'qty'           => ['type' => 'smallint', 'constraint' => 6, 'default' => 0],
            'satuan'        => ['type' => 'varchar', 'constraint' => 10, 'null' => true],
            'harga'         => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pemasukan'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'pengeluaran'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
            'updated_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_laporan_id', 'dp_laporan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_laporan_isi');

        // 13. Tabel: dp_laporan_log
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_laporan_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'user_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'status'        => ['type' => 'tinyint', 'constraint' => 1], // 0:Dicetak, 1:Dikunci, 2:Dibuka
            'keterangan'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'timestamp', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_laporan_id', 'dp_laporan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dp_laporan_log');
    }

    public function down()
    {
        $this->forge->dropTable('dp_laporan_log', true);    // printing → status laporan
        $this->forge->dropTable('dp_laporan_isi', true);
        $this->forge->dropTable('dp_laporan', true);        // printing → data laporan
        $this->forge->dropTable('dp_nota_bayar', true);
        $this->forge->dropTable('dp_nota_isi', true);
        $this->forge->dropTable('dp_nota', true);           // printing → data nota
        $this->forge->dropTable('dp_harga_khusus', true);   // printing → harga khusus konsumen
        $this->forge->dropTable('dp_kategori_harga', true); // printing → kategori harga konsumen
        $this->forge->dropTable('dp_produk', true);         // printing → data produk
        $this->forge->dropTable('dp_bahan', true);          // printing → data bahan
        $this->forge->dropTable('kategori_produk', true);   // umum → kategori produk
        $this->forge->dropTable('konsumen', true);          // umum → konsumen
        $this->forge->dropTable('kategori_konsumen', true); // umum → kategori konsumen
    }
}
