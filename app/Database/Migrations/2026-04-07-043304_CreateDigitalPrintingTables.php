<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDigitalPrintingTables extends Migration
{
    public function up()
    {
        // 1. Tabel: level_harga
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('level_harga');

        // 2. Tabel: konsumen
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'level_harga_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 40, 'unique' => true],
            'perusahaan'     => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'alamat'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kota'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'whatsapp'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'telegram'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'divisi'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:umum, 1:printing, 2:adv
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('level_harga_id', 'level_harga', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('konsumen');

        // 3. Tabel: dp_kategori
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 30],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dp_kategori');

        // 4. Tabel: dp_bahan
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 30],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dp_bahan');

        // 5. Tabel: dp_produk
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_bahan_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'lebar'          => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'        => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'hpp'            => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'harga'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'promo'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'promo_awal'     => ['type' => 'DATE', 'null' => true],
            'promo_akhir'    => ['type' => 'DATE', 'null' => true],
            'promo_aktif'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'rumus'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:luas, 1:qty
            'unggulan'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_kategori_id', 'dp_kategori', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_bahan_id', 'dp_bahan', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['dp_kategori_id', 'dp_bahan_id']);
        $this->forge->createTable('dp_produk');

        // 6. Tabel: dp_harga_level
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'level_harga_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'harga'          => ['type' => 'INT', 'constraint' => 11],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('level_harga_id', 'level_harga', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['level_harga_id', 'dp_produk_id']);
        $this->forge->createTable('dp_harga_level');

        // 7. Tabel: dp_harga_khusus
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'harga'        => ['type' => 'INT', 'constraint' => 11],
            'created_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'   => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['konsumen_id', 'dp_produk_id']);
        $this->forge->createTable('dp_harga_khusus');

        // 8. Tabel: dp_nota
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'nota'           => ['type' => 'VARCHAR', 'constraint' => 10, 'unique' => true],
            'subtotal'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'diskon_persen'  => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 0],
            'diskon_nominal' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'nettotal'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'bayar'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'sisa'           => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status_nota'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:B.Bayar, 1:B.Lunas, 2:Lunas
            'status_barang'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:B.Ambil, 1:S.Ambil
            'tgl_ambil'      => ['type' => 'DATE', 'null' => true],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota');

        // 9. Tabel: dp_nota_isi
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_nota_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'produk_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tema'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'lebar'        => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'      => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'luas'         => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'qty'          => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'harga_satuan' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'subtotal'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'hpp'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'   => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota_isi');

        // 10. Tabel: dp_nota_bayar
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_nota_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'metode'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:Tunai, 1:Transfer
            'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'jumlah'         => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota_bayar');
    }

    public function down()
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari kendala kunci asing (FK).
        $this->forge->dropTable('dp_nota_bayar', true);
        $this->forge->dropTable('dp_nota_isi', true);
        $this->forge->dropTable('dp_nota', true);
        $this->forge->dropTable('dp_harga_khusus', true);
        $this->forge->dropTable('dp_harga_level', true);
        $this->forge->dropTable('dp_produk', true);
        $this->forge->dropTable('dp_bahan', true);
        $this->forge->dropTable('dp_kategori', true);
        $this->forge->dropTable('konsumen', true);
        $this->forge->dropTable('level_harga', true);
    }
}
