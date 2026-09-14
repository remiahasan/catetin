<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Demo dataset for development/staging ONLY.
 *
 * Builds the two sample stalls (Pisgor, Miss) with staff, categories, menus,
 * stock history, and a week of transactions. Assumes a FRESH database: several
 * seeders below reference each other by id (businesses 1–2, first users).
 *
 * Never enable on production: this writes fake businesses and sales into the
 * live database with no way to separate them from real data afterwards.
 * Gated behind SEED_DEMO=true (see DatabaseSeeder).
 */
class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BusinessSeeder::class,
            PegawaiSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            StockSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
