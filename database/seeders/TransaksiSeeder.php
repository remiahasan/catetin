<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('id', '!=', 3)->get();
        $businesses = Business::all();

        foreach ($businesses as $business) {

            // Ambil semua menu untuk business ini
            $menus = Menu::where('business_id', $business->id)->get();

            // Jika tidak ada menu → skip
            if ($menus->count() === 0) {
                continue;
            }

            // Buat 7 transaksi untuk 7 hari terakhir
            for ($i = 0; $i < 7; $i++) {

                $details = [];
                $totalBayar = 0;

                // Tentukan jumlah item dalam transaksi (1–4 item random)
                $jumlahItem = rand(1, 4);

                for ($j = 0; $j < $jumlahItem; $j++) {

                    $menu = $menus->random();

                    $qty = rand(1, 3);
                    $subtotal = $menu->harga * $qty;

                    $details[] = [
                        'menu_id'  => $menu->id,
                        'nama'     => $menu->nama,
                        'jumlah'   => $qty,
                        'harga'    => $menu->harga,
                        'subtotal' => $subtotal,
                    ];

                    $totalBayar += $subtotal;
                }

                $uangDibayarkan = $totalBayar + rand(0, 20000);
                $kembalian = $uangDibayarkan - $totalBayar;

                Transaksi::create([
                    'user_id'         => $users->random()->id,
                    'business_id'     => $business->id,
                    'total_bayar'     => $totalBayar,
                    'uang_dibayarkan' => $uangDibayarkan,
                    'kembalian'       => $kembalian,
                    'details'         => json_encode($details),
                    'created_at'      => Carbon::now()->subDays($i),
                    'updated_at'      => Carbon::now()->subDays($i),
                ]);
            }
        }
    }
}

