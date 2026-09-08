<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDigitalPrintingTables extends Migration
{
    public function up()
    {
        // 1. Tabel: kategori_konsumen
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true], // misal: Retail, Corporate, Karyawan
            'created_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_konsumen');

        // 2. Tabel: konsumen
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_konsumen_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'                 => ['type' => 'VARCHAR', 'constraint' => 40],
            'perusahaan'           => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'alamat'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kota'                 => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'whatsapp'             => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'telegram_id'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'                => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'divisi'               => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:umum, 1:printing, 2:advertising
            'created_at'           => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'           => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'           => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_konsumen_id', 'kategori_konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('konsumen');

        // 3. Tabel: dp_mesin
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori'   => ['type' => 'VARCHAR', 'constraint' => 30], // misal: Outdoor, Indoor, Cutting, Copy Color
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true], // misal: Allwin 512i, Mimaki CG-60SRIII
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dp_mesin');

        // 4. Tabel: dp_bahan
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('dp_bahan');

        // 5. Tabel: dp_produk
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_mesin_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_bahan_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'lebar'        => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'panjang'      => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'satuan'       => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'm²'],
            'hpp'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // harga pokok produksi
            'harga'        => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // harga dasar
            'promo'        => ['type' => 'INT', 'constraint' => 11, 'null' => true], // harga promosi
            'promo_awal'   => ['type' => 'DATE', 'null' => true], // tanggal awal promo
            'promo_akhir'  => ['type' => 'DATE', 'null' => true], // tanggal akhir promo
            'rumus'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:perkalian luas, 1:perkalian qty
            'unggulan'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'   => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_mesin_id', 'dp_mesin', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_bahan_id', 'dp_bahan', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_produk');

        // 6. Tabel: dp_kategori_harga
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_konsumen_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'harga'                => ['type' => 'INT', 'constraint' => 11],
            'created_at'           => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'           => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'           => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_konsumen_id', 'kategori_konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['kategori_konsumen_id', 'dp_produk_id']);
        $this->forge->createTable('dp_kategori_harga');

        // 7. Tabel: dp_harga_khusus
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'konsumen_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'harga'        => ['type' => 'INT', 'constraint' => 11],
            'created_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'   => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'   => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['konsumen_id', 'dp_produk_id']);
        $this->forge->createTable('dp_harga_khusus');

        // 8. Tabel: dp_nota
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'konsumen_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'nota'           => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'subtotal'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'diskon_persen'  => ['type' => 'TINYINT', 'constraint' => 3, 'default' => 0],
            'diskon_nominal' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'nettotal'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'bayar'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'sisa'           => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status_nota'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:Belum Bayar, 1:Belum Lunas, 2:Lunas, 3:Hapus Nota, 4:Retur Nota
            'status_barang'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:Belum Ambil, 1:Sudah Ambil
            'tgl_ambil'      => ['type' => 'DATE', 'null' => true],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('konsumen_id', 'konsumen', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota');

        // 9. Tabel: dp_nota_isi
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_nota_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_produk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
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
            'deleted_at'   => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_produk_id', 'dp_produk', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota_isi');

        // 10. Tabel: dp_nota_bayar
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'dp_nota_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'metode'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // 0:Tunai, 1:Transfer
            'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'diterima'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah'         => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'kembalian'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_nota_bayar');

        // 11. Tabel: dp_laporan
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // pembuat laporan (misal: Andi)
            'tanggal'        => ['type' => 'DATE'],
            'pemasukan'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'pengeluaran'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'pendapatan'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // snapshot: pemasukan - pengeluaran
            'tunai'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'transfer'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'diserahkan_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true], // misal: Budi (update saat laporan dicetak)
            'diterima_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'diketahui_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'print_at'       => ['type' => 'DATETIME', 'null' => true], // terakhir kali dicetak
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('diserahkan_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('diterima_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('diketahui_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addUniqueKey(['user_id', 'tanggal']);
        $this->forge->createTable('dp_laporan');

        // 12. Tabel: dp_laporan_isi
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'dp_laporan_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dp_nota_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true], // isi jika baris ini dari transaksi nota
            'nota'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true], // nomor nota manual/arsip lama, atau salinan dp_nota.nota
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 100], // nama konsumen/keterangan transaksi
            'qty'            => ['type' => 'SMALLINT', 'constraint' => 6, 'default' => 0],
            'satuan'         => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'harga'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'pemasukan'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'pengeluaran'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'keterangan'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dp_laporan_id', 'dp_laporan', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('dp_nota_id', 'dp_nota', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('dp_laporan_isi');
    }

    public function down()
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari kendala kunci asing (FK).
        $this->forge->dropTable('dp_laporan_isi', true);
        $this->forge->dropTable('dp_laporan', true); // menu printing: Data Laporan
        $this->forge->dropTable('dp_nota_bayar', true);
        $this->forge->dropTable('dp_nota_isi', true);
        $this->forge->dropTable('dp_nota', true); // menu printing: Nota Penjualan
        $this->forge->dropTable('dp_harga_khusus', true); // menu printing: Harga Khusus
        $this->forge->dropTable('dp_kategori_harga', true); // menu printing: Harga Per Kategori
        $this->forge->dropTable('dp_produk', true); // menu printing: Data Produk
        $this->forge->dropTable('dp_bahan', true); // menu printing: Data Bahan
        $this->forge->dropTable('dp_mesin', true); // menu printing: Data Mesin
        $this->forge->dropTable('konsumen', true); // menu umum: Data Konsumen
        $this->forge->dropTable('kategori_konsumen', true); // menu umum: Kategori Konsumen
    }
}
