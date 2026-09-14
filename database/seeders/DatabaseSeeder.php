<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Always safe: bootstraps the owner account from SEED_OWNER_* env
     * (skipped quietly when unset). Demo data runs ONLY with explicit
     * opt-in — never on production by accident:
     *
     *   SEED_DEMO=true php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            OwnerSeeder::class,
        ]);

        if (env('SEED_DEMO', false)) {
            $this->call([
                DemoSeeder::class,
            ]);
        } else {
            $this->command?->warn('Demo data skipped (set SEED_DEMO=true to include it).');
        }
    }
}
