<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder data demo Portal MA.
 *
 * Menyiapkan data uji untuk alur:
 * bahan_jenis -> bahan -> bahan_order -> bahan_stok / material_stok -> produk -> nota
 *
 * Catatan: pada alur nyata `material_stok` dan `bahan_stok` dibuat oleh aksi
 * "tambah stok" pada order bahan. Di seeder ini keduanya diisi langsung agar
 * halaman stok langsung memiliki data uji.
 */
class DigitalPrintingSeeder extends Seeder
{
    private string $now;

    public function run(): void
    {
        $this->now = date('Y-m-d H:i:s');

        $this->seedMesinTipe();
        $this->seedBahanJenis();
        $this->seedBahan();
        $this->seedSupplier();
        $this->seedKonsumen();
        $this->seedFinishing();
        $this->seedProduk();
        $this->seedOrder();
        $this->seedStok();
    }

    /**
     * Ambil pasangan kolom unik => id untuk menghubungkan data antar tabel.
     */
    private function petaId(string $table, string $kolom): array
    {
        $peta = [];
        $rows = $this->db->table($table)->select("id, {$kolom}")->get()->getResult();

        foreach ($rows as $row) {
            $peta[$row->{$kolom}] = (int) $row->id;
        }

        return $peta;
    }

    private function seedMesinTipe(): void
    {
        $baris = [];
        foreach (['Outdoor', 'Indoor', 'Cutting', 'Copy Colour A3+'] as $nama) {
            $baris[] = ['nama' => $nama, 'created_at' => $this->now, 'updated_at' => $this->now];
        }

        $this->db->table('mesin_tipe')->insertBatch($baris);
    }

    private function seedBahanJenis(): void
    {
        $tipe = $this->petaId('mesin_tipe', 'nama');
        $baris = [];

        // Jenis bahan produksi (kategori 0) - diproses mesin
        $bahan = [
            ['FLX260', 'Outdoor', 'Flexy 260 gsm', 260, 0],
            ['FLX280', 'Outdoor', 'Flexy 280 gsm', 280, 0],
            ['FLX300', 'Outdoor', 'Flexy 300 gsm', 300, 0],
            ['FLX340', 'Outdoor', 'Flexy 340 gsm', 340, 0],
            ['ALB', 'Indoor', 'Albatros', 0, 0],
            ['BWG210', 'Copy Colour A3+', 'Bw Gca Lux 210 gsm', 210, 1],
            ['BWG260', 'Copy Colour A3+', 'Bw Gca Lux 260 gsm', 260, 1],
            ['LNTP', 'Copy Colour A3+', 'Linen Tebal Putih', 0, 1],
            ['SKP', 'Copy Colour A3+', 'Stiker Kromo Pindo', 0, 1],
            ['SKV', 'Copy Colour A3+', 'Stiker Vinil', 0, 1],
        ];

        foreach ($bahan as [$kode, $mesin, $nama, $gsm, $rumus]) {
            $baris[] = [
                'mesin_tipe_id' => $tipe[$mesin],
                'kategori'      => 0,
                'kode'          => $kode,
                'nama'          => $nama,
                'gsm'           => $gsm,
                'rumus'         => $rumus,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ];
        }

        // Material produksi (kategori 1) - pendukung statis, tanpa mesin
        $material = [
            'XBN-MINI'   => 'Mini X-Banner 0.26 x 0.38 m',
            'XBN-60x160' => 'X-Banner 0.6 x 1.6 m',
            'XBN-80x180' => 'X-Banner 0.8 x 1.8 m',
            'YBN-60x160' => 'Y-Banner 0.6 x 1.6 m',
            'YBN-80x180' => 'Y-Banner 0.8 x 1.8 m',
            'RBN-60x160' => 'Roll-Banner 0.6 x 1.6 m',
            'RBN-80x180' => 'Roll-Banner 0.8 x 1.8 m',
            'RBN-80x200' => 'Roll-Banner 0.8 x 2 m',
            'RBN-85x200' => 'Roll-Banner 0.85 x 2 m',
        ];

        foreach ($material as $kode => $nama) {
            $baris[] = [
                'mesin_tipe_id' => null,
                'kategori'      => 1,
                'kode'          => $kode,
                'nama'          => $nama,
                'gsm'           => 0,
                'rumus'         => 1,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ];
        }

        $this->db->table('bahan_jenis')->insertBatch($baris);
    }

    private function seedBahan(): void
    {
        $jenis = $this->petaId('bahan_jenis', 'kode');
        $baris = [];

        // Bahan roll: setiap jenis Flexy memiliki 4 ukuran roll
        foreach (['FLX260' => 260, 'FLX280' => 280, 'FLX300' => 300, 'FLX340' => 340] as $kode => $gsm) {
            foreach ([['3.2', '60'], ['3.2', '70'], ['2.2', '60'], ['2.2', '70']] as $ukuran) {
                [$lebar, $panjang] = $ukuran;

                $baris[] = [
                    'bahan_jenis_id' => $jenis[$kode],
                    'kode'           => $kode . '-' . str_replace('.', '', $lebar) . $panjang,
                    'nama'           => "Flexy {$gsm} gsm ({$lebar} x {$panjang}m)",
                    'lebar'          => (float) $lebar,
                    'panjang'        => (float) $panjang,
                    'isi_paket'      => round((float) $lebar * (float) $panjang, 2),
                    'satuan_1'       => 'm²',
                    'satuan_2'       => 'roll',
                    'created_at'     => $this->now,
                    'updated_at'     => $this->now,
                ];
            }
        }

        // Bahan roll m² lain
        $roll = [
            ['ALB', 'ALB-3260', 'Albatros (3.2 x 60m)', 3.2, 60],
            ['LNTP', 'LNTP-A3P', 'Linen Tebal Putih (0.79 x 1.09m)', 0.79, 1.09],
        ];

        foreach ($roll as [$jenisKode, $kode, $nama, $lebar, $panjang]) {
            $baris[] = [
                'bahan_jenis_id' => $jenis[$jenisKode],
                'kode'           => $kode,
                'nama'           => $nama,
                'lebar'          => $lebar,
                'panjang'        => $panjang,
                'isi_paket'      => round($lebar * $panjang, 2),
                'satuan_1'       => 'm²',
                'satuan_2'       => 'roll',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ];
        }

        // Bahan rim (Copy Colour A3+): 1 rim = 100 lembar, rumus perkalian qty
        $rim = [
            ['BWG210', 'BWG210-A3P', 'Bw Gca Lux 210 gsm (0.79 x 1.09m)', 0.79, 1.09],
            ['BWG260', 'BWG260-A3P', 'Bw Gca Lux 260 gsm (0.79 x 1.09m)', 0.79, 1.09],
            ['SKP', 'SKP-A3P', 'Stiker Kromo Pindo (0.70 x 1.08m)', 0.70, 1.08],
            ['SKV', 'SKV-A3P', 'Stiker Vinil (0.53 x 0.86m)', 0.53, 0.86],
        ];

        foreach ($rim as [$jenisKode, $kode, $nama, $lebar, $panjang]) {
            $baris[] = [
                'bahan_jenis_id' => $jenis[$jenisKode],
                'kode'           => $kode,
                'nama'           => $nama,
                'lebar'          => $lebar,
                'panjang'        => $panjang,
                'isi_paket'      => 100.00,
                'satuan_1'       => 'lembar',
                'satuan_2'       => 'rim',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ];
        }

        // Material (jenis 1): satu unit beli per spesifikasi, stok agregat per pcs
        $material = [
            ['XBN-MINI', 'XBN-0026x0038', 'Mini X-Banner 0.26 x 0.38 m', 0.26, 0.38],
            ['XBN-60x160', 'XBN-0616', 'X-Banner 0.6 x 1.6 m', 0.6, 1.6],
            ['XBN-80x180', 'XBN-0818', 'X-Banner 0.8 x 1.8 m', 0.8, 1.8],
            ['YBN-60x160', 'YBN-0616', 'Y-Banner 0.6 x 1.6 m', 0.6, 1.6],
            ['YBN-80x180', 'YBN-0818', 'Y-Banner 0.8 x 1.8 m', 0.8, 1.8],
            ['RBN-60x160', 'RBN-0616', 'Roll-Banner 0.6 x 1.6 m', 0.6, 1.6],
            ['RBN-80x180', 'RBN-0818', 'Roll-Banner 0.8 x 1.8 m', 0.8, 1.8],
            ['RBN-80x200', 'RBN-0820', 'Roll-Banner 0.8 x 2 m', 0.8, 2.0],
            ['RBN-85x200', 'RBN-8520', 'Roll-Banner 0.85 x 2 m', 0.85, 2.0],
        ];

        foreach ($material as [$jenisKode, $kode, $nama, $lebar, $panjang]) {
            $baris[] = [
                'bahan_jenis_id' => $jenis[$jenisKode],
                'kode'           => $kode,
                'nama'           => $nama . ' (pcs)',
                'lebar'          => $lebar,
                'panjang'        => $panjang,
                'isi_paket'      => 1.00,
                'satuan_1'       => 'pcs',
                'satuan_2'       => 'pcs',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ];
        }

        $this->db->table('bahan')->insertBatch($baris);
    }

    private function seedSupplier(): void
    {
        $this->db->table('supplier')->insertBatch([
            [
                'nama'       => 'Agen Bahan Cirebon',
                'perusahaan' => 'CV Sumber Bahan',
                'alamat'     => 'Jl. Industri No. 10',
                'kota'       => 'Cirebon',
                'kontak'     => '081200000001',
                'email'      => 'sales@sumberbahan.test',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ],
            [
                'nama'       => 'Penyedia Rangka',
                'perusahaan' => 'UD Rangka Jaya',
                'alamat'     => 'Jl. Raya Plumbon No. 5',
                'kota'       => 'Cirebon',
                'kontak'     => '081200000002',
                'email'      => 'order@rangkajaya.test',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ],
        ]);
    }

    private function seedKonsumen(): void
    {
        $this->db->table('konsumen_tipe')->insertBatch([
            ['nama' => 'Retail', 'created_at' => $this->now, 'updated_at' => $this->now],
            ['nama' => 'Corporate', 'created_at' => $this->now, 'updated_at' => $this->now],
            ['nama' => 'Karyawan', 'created_at' => $this->now, 'updated_at' => $this->now],
        ]);

        $tipe = $this->petaId('konsumen_tipe', 'nama');

        $this->db->table('konsumen')->insertBatch([
            [
                'konsumen_tipe_id' => $tipe['Retail'],
                'user_id'          => null,
                'nama'             => 'Umum',
                'perusahaan'       => null,
                'alamat'           => null,
                'kota'             => 'Cirebon',
                'whatsapp'         => '081300000001',
                'telegram_id'      => null,
                'email'            => null,
                'divisi'           => 0,
                'created_at'       => $this->now,
                'updated_at'       => $this->now,
            ],
            [
                'konsumen_tipe_id' => $tipe['Corporate'],
                'user_id'          => null,
                'nama'             => 'Dewi',
                'perusahaan'       => 'PT Maju Jaya',
                'alamat'           => 'Jl. Merdeka No. 2',
                'kota'             => 'Cirebon',
                'whatsapp'         => '081300000002',
                'telegram_id'      => null,
                'email'            => 'dewi@majujaya.test',
                'divisi'           => 2,
                'created_at'       => $this->now,
                'updated_at'       => $this->now,
            ],
            [
                'konsumen_tipe_id' => $tipe['Karyawan'],
                'user_id'          => null,
                'nama'             => 'Rian',
                'perusahaan'       => null,
                'alamat'           => null,
                'kota'             => 'Cirebon',
                'whatsapp'         => '081300000003',
                'telegram_id'      => null,
                'email'            => null,
                'divisi'           => 1,
                'created_at'       => $this->now,
                'updated_at'       => $this->now,
            ],
        ]);
    }

    private function seedFinishing(): void
    {
        $baris = [];
        foreach (['Cutting', 'Mata Ayam', 'Laminasi Glossy', 'Pasang Rangka'] as $nama) {
            $baris[] = ['nama' => $nama, 'created_at' => $this->now, 'updated_at' => $this->now];
        }

        $this->db->table('finishing')->insertBatch($baris);
    }

    private function seedProduk(): void
    {
        $jenis = $this->petaId('bahan_jenis', 'kode');

        // [nama, bahan_jenis, material_jenis, kategori, lebar, panjang, rumus, hpp, harga, unggulan]
        $data = [
            ['X-Banner 60x160 Flexy 280 + Rangka', 'FLX280', 'XBN-60x160', 0, 0.6, 1.6, 1, 20000, 35000, 0],
            ['X-Banner 80x180 Flexy 280 + Rangka', 'FLX280', 'XBN-80x180', 0, 0.8, 1.8, 1, 25000, 45000, 0],
            ['X-Banner 60x160 Albatros + Rangka', 'ALB', 'XBN-60x160', 0, 0.6, 1.6, 1, 22000, 38000, 0],
            ['Y-Banner 60x160 Flexy 280 + Rangka', 'FLX280', 'YBN-60x160', 0, 0.6, 1.6, 1, 30000, 55000, 0],
            ['Roll-Banner 60x160 Flexy 280 + Rangka', 'FLX280', 'RBN-60x160', 0, 0.6, 1.6, 0, 65000, 110000, 0],
            ['Spanduk Flexy 280', 'FLX280', null, 0, 1.0, 3.0, 0, 12000, 25000, 1],
            ['Cetak A3+ Bw Gca Lux 210', 'BWG210', null, 0, 0.29, 0.42, 1, 1500, 5000, 0],
            ['Neon Box', null, null, 1, 0.0, 0.0, 1, 0, 750000, 0],
            ['Desain Grafis', null, null, 2, 0.0, 0.0, 1, 0, 50000, 0],
            ['Jasa Pasang Banner', null, null, 2, 0.0, 0.0, 1, 0, 75000, 0],
        ];

        $baris = [];
        foreach ($data as $row) {
            [$nama, $jenisKode, $materialKode, $kategori, $lebar, $panjang, $rumus, $hpp, $harga, $unggulan] = $row;

            $baris[] = [
                'bahan_jenis_id'    => $jenisKode !== null ? $jenis[$jenisKode] : null,
                'material_jenis_id' => $materialKode !== null ? $jenis[$materialKode] : null,
                'kategori'          => $kategori,
                'nama'              => $nama,
                'lebar'             => $lebar,
                'panjang'           => $panjang,
                'rumus'             => $rumus,
                'hpp'               => $hpp,
                'harga'             => $harga,
                'promo'             => null,
                'promo_awal'        => null,
                'promo_akhir'       => null,
                'unggulan'          => $unggulan,
                'status'            => 1,
                'created_at'        => $this->now,
                'updated_at'        => $this->now,
            ];
        }

        $this->db->table('produk')->insertBatch($baris);

        // Harga khusus per tipe konsumen (Corporate & Karyawan) dan per konsumen
        $produk   = $this->petaId('produk', 'nama');
        $tipe     = $this->petaId('konsumen_tipe', 'nama');
        $konsumen = $this->petaId('konsumen', 'nama');
        $xb60     = $produk['X-Banner 60x160 Flexy 280 + Rangka'];

        $this->db->table('harga_tipe')->insertBatch([
            ['konsumen_tipe_id' => $tipe['Corporate'], 'produk_id' => $xb60, 'harga' => 40000, 'created_at' => $this->now, 'updated_at' => $this->now],
            ['konsumen_tipe_id' => $tipe['Karyawan'], 'produk_id' => $xb60, 'harga' => 30000, 'created_at' => $this->now, 'updated_at' => $this->now],
            ['konsumen_tipe_id' => $tipe['Corporate'], 'produk_id' => $produk['Spanduk Flexy 280'], 'harga' => 28000, 'created_at' => $this->now, 'updated_at' => $this->now],
        ]);

        $this->db->table('harga_khusus')->insertBatch([
            ['konsumen_id' => $konsumen['Dewi'], 'produk_id' => $xb60, 'harga' => 38000, 'created_at' => $this->now, 'updated_at' => $this->now],
        ]);
    }

    private function seedOrder(): void
    {
        $supplier = $this->petaId('supplier', 'nama');
        $bahan    = $this->petaId('bahan', 'kode');

        // PO-1 sudah masuk stok (status_stok = 1) dan cocok dengan data bahan_stok
        $this->db->table('bahan_order')->insert([
            'supplier_id' => $supplier['Agen Bahan Cirebon'],
            'tgl_order'   => date('Y-m-d', strtotime('-7 days')),
            'no_order'    => 'PO-2026-0001',
            'total_qty'   => 2,
            'subtotal'    => 9600000,
            'ongkir'      => 50000,
            'total'       => 9650000,
            'status_stok' => 1,
            'keterangan'  => 'Pembelian Flexy 280 gsm 3.2 x 60m',
            'created_at'  => $this->now,
            'updated_at'  => $this->now,
        ]);
        $order1 = (int) $this->db->insertID();

        $this->db->table('bahan_order_isi')->insert([
            'bahan_order_id' => $order1,
            'bahan_id'       => $bahan['FLX280-3260'],
            'harga_satuan'   => 25000,
            'harga_paket'    => 4800000,
            'qty'            => 2,
            'jumlah'         => 9600000,
            'keterangan'     => '1 roll = 192 m²',
            'created_at'     => $this->now,
            'updated_at'     => $this->now,
        ]);

        // PO-2 belum masuk stok (status_stok = 0) - untuk menguji tombol tambah stok
        $this->db->table('bahan_order')->insert([
            'supplier_id' => $supplier['Agen Bahan Cirebon'],
            'tgl_order'   => date('Y-m-d'),
            'no_order'    => 'PO-2026-0002',
            'total_qty'   => 11,
            'subtotal'    => 5750000,
            'ongkir'      => 25000,
            'total'       => 5775000,
            'status_stok' => 0,
            'keterangan'  => 'Uji campur bahan (Flexy) dan material (rangka)',
            'created_at'  => $this->now,
            'updated_at'  => $this->now,
        ]);
        $order2 = (int) $this->db->insertID();

        $this->db->table('bahan_order_isi')->insertBatch([
            [
                'bahan_order_id' => $order2,
                'bahan_id'       => $bahan['FLX280-3270'],
                'harga_satuan'   => 25000,
                'harga_paket'    => 5600000,
                'qty'            => 1,
                'jumlah'         => 5600000,
                'keterangan'     => '1 roll = 224 m²',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ],
            [
                'bahan_order_id' => $order2,
                'bahan_id'       => $bahan['XBN-0616'],
                'harga_satuan'   => 15000,
                'harga_paket'    => 15000,
                'qty'            => 10,
                'jumlah'         => 150000,
                'keterangan'     => 'Material rangka X-Banner 0.6 x 1.6 m',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ],
        ]);
    }

    private function seedStok(): void
    {
        $bahan      = $this->petaId('bahan', 'kode');
        $bahanJenis = $this->petaId('bahan_jenis', 'kode');

        $po = $this->db->table('bahan_order')->select('id')->where('no_order', 'PO-2026-0001')->get()->getRow();
        $orderSelesai = (int) $po->id;

        // Stok bahan per roll/barcode
        $this->db->table('bahan_stok')->insertBatch([
            [
                'bahan_id'       => $bahan['FLX280-3260'],
                'bahan_order_id' => $orderSelesai,
                'kode_bahan'     => 'FLX280-3260-001',
                'stok_masuk'     => 192.00,
                'stok_pakai'     => 6.72,
                'stok_sisa'      => 185.28,
                'kondisi'        => 0,
                'status'         => 0,
                'keterangan'     => 'Roll terpasang di mesin Outdoor',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ],
            [
                'bahan_id'       => $bahan['FLX280-3260'],
                'bahan_order_id' => $orderSelesai,
                'kode_bahan'     => 'FLX280-3260-002',
                'stok_masuk'     => 192.00,
                'stok_pakai'     => 0.00,
                'stok_sisa'      => 192.00,
                'kondisi'        => 0,
                'status'         => 0,
                'keterangan'     => 'Roll baru, belum dipakai',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ],
        ]);

        // Stok material: satu baris agregat per bahan_jenis material
        $this->db->table('material_stok')->insertBatch([
            ['bahan_jenis_id' => $bahanJenis['XBN-60x160'], 'stok_masuk' => 10.00, 'stok_pakai' => 3.00, 'stok_sisa' => 7.00, 'harga_satuan' => 15000, 'kondisi' => 0, 'status' => 0, 'keterangan' => 'Rangka X-Banner', 'created_at' => $this->now, 'updated_at' => $this->now],
            ['bahan_jenis_id' => $bahanJenis['XBN-80x180'], 'stok_masuk' => 5.00, 'stok_pakai' => 1.00, 'stok_sisa' => 4.00, 'harga_satuan' => 20000, 'kondisi' => 0, 'status' => 0, 'keterangan' => 'Rangka X-Banner besar', 'created_at' => $this->now, 'updated_at' => $this->now],
            ['bahan_jenis_id' => $bahanJenis['YBN-60x160'], 'stok_masuk' => 4.00, 'stok_pakai' => 0.00, 'stok_sisa' => 4.00, 'harga_satuan' => 35000, 'kondisi' => 0, 'status' => 0, 'keterangan' => 'Rangka Y-Banner', 'created_at' => $this->now, 'updated_at' => $this->now],
            ['bahan_jenis_id' => $bahanJenis['RBN-60x160'], 'stok_masuk' => 8.00, 'stok_pakai' => 0.00, 'stok_sisa' => 8.00, 'harga_satuan' => 75000, 'kondisi' => 0, 'status' => 0, 'keterangan' => 'Rangka Roll-Banner', 'created_at' => $this->now, 'updated_at' => $this->now],
        ]);
    }
}
