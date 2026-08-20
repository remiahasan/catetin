<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\StockLog;
use App\Models\RiwayatStok;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $satuan = ['pcs', 'bungkus', 'kantong', 'cup'];
        $namaStock = ['cup', 'susu', 'buah', 'sedotan', 'kantong', 'selai coklat'];

        foreach (range(1, 20) as $i) {

            // Stok awal 7 hari lalu
            $stokAwal = $faker->numberBetween(50, 120);

            $stock = Stock::create([
                'nama'        => $faker->randomElement($namaStock),
                'business_id' => $faker->numberBetween(1, 2),
                'jumlah_stok' => $stokAwal, // stok awal 7 hari lalu
                'satuan'      => $faker->randomElement($satuan),
                'harga'       => $faker->numberBetween(500, 50000),
                'created_at'  => now()->subDays(7),
                'updated_at'  => now()->subDays(7),
            ]);

            // Loop 7 hari terakhir
            $currentStok = $stokAwal;

            for ($day = 7; $day >= 1; $day--) {

                $tanggal = now()->subDays($day);

                // stok keluar acak tiap hari
                $stokKeluar = $faker->numberBetween(0, 10);

                // hitung stok akhir
                $stokAkhir = max(0, $currentStok - $stokKeluar);

                // Insert ke stock_log
                StockLog::create([
                    'stock_id'    => $stock->id,
                    'stok_awal'   => $currentStok,
                    'stok_keluar' => $stokKeluar,
                    'stok_akhir'  => $stokAkhir,
                    'created_at'  => $tanggal,
                    'updated_at'  => $tanggal,
                ]);

                // Insert ke riwayat_stoks
                RiwayatStok::create([
                    'stock_id'   => $stock->id,
                    'user_id'    => 1,
                    'status'     => $stokKeluar > 0 ? 'keluar' : 'masuk',
                    'jumlah'     => $stokKeluar,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                // Update stok untuk hari selanjutnya
                $currentStok = $stokAkhir;
            }

            // Update stok terakhir ke tabel stock
            $stock->update([
                'jumlah_stok' => $currentStok,
                'updated_at'  => now(),
            ]);
        }
    }
}
