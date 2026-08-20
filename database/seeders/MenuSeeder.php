<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $smoothies = Category::where('nama', 'Smoothies')->first();
        $juice     = Category::where('nama', 'Juice')->first();
        $other     = Category::where('nama', 'Other')->first();

        // --- Smoothies dengan variasi ukuran ---
        $smoothiesMenus = [
            'Smoothies Mangga',
            'Smoothies Strawberry',
            'Smoothies Alpukat',
            'Smoothies Buah Naga',
            'Smoothies Coklat',
            'Smoothies Taro',
            'Smoothies Matcha',
            'Smoothies Alpucok',
            'Smoothies Red Velvet',
            'Smoothies Lychee',
        ];

        $sizes = [
            ['label' => '(S)',  'harga' => 10000],
            ['label' => '(M)',  'harga' => 12000],
            ['label' => '(L)',  'harga' => 15000],
            ['label' => '(XL)', 'harga' => 18000],
        ];

        foreach ($smoothiesMenus as $menu) {
            foreach ($sizes as $size) {
                Menu::create([
                    'nama'        => $menu . ' ' . $size['label'],
                    'kategori_id' => $smoothies->id,
                    'business_id' => 2,
                    'harga'       => $size['harga'],
                ]);
            }
        }

        // --- Juice ---
        $juiceMenus = [
            'Jus Alpukat',
            'Jus Mangga',
            'Jus Jambu',
            'Jus Semangka',
            'Jus Melon',
            'Jus Jeruk',
            'Jus Buah Naga',
            'Jus Apel',
        ];

        foreach ($juiceMenus as $menu) {
            Menu::create([
                'nama'        => $menu,
                'kategori_id' => $juice->id,
                'business_id' => 2,
                'harga'       => 6000,
            ]);
        }

        // --- Other ---
        Menu::create([
            'nama'        => 'Es Teh',
            'kategori_id' => $other->id,
            'business_id' => 2,
            'harga'       => 3000,
        ]);

        Menu::create([
            'nama'        => 'Es Teler',
            'kategori_id' => $other->id,
            'business_id' => 2,
            'harga'       => 10000,
        ]);

        // --- Pisgor & Cemilan ---
        $menus_pisgor = [
            ['nama' => 'Pisgor Ori Palm Sugar',     'harga' => 12000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat',             'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Full Keju',          'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Crunchy',     'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Keju',        'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Milo',        'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Tiramisu',    'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Matcha',      'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Taro',        'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],
            ['nama' => 'Pisgor Coklat Strawberry',  'harga' => 15000, 'kategori_id' => 4, 'business_id' => 1],

            // Cemilan
            ['nama' => 'Potato Twister',            'harga' => 10000, 'kategori_id' => 5, 'business_id' => 1],
            ['nama' => 'Singkong Keju',             'harga' => 13000, 'kategori_id' => 5, 'business_id' => 1],
            ['nama' => 'Brota isi 4',               'harga' => 15000, 'kategori_id' => 5, 'business_id' => 1],
            ['nama' => 'Pastel Ayam Premium',       'harga' => 15000, 'kategori_id' => 5, 'business_id' => 1],
            ['nama' => 'Pastel Sapi Premium',       'harga' => 15000, 'kategori_id' => 5, 'business_id' => 1],
        ];

        foreach ($menus_pisgor as $menu_pisgor) {
            Menu::create($menu_pisgor);
        }

        // --- Hotang ---
        $menus_hotang = [
            // Original
            ['nama' => 'Full Sosis',      'harga' => 10000, 'kategori_id' => 6, 'business_id' => 2],
            ['nama' => 'Sosis Mix Mozza', 'harga' => 13000, 'kategori_id' => 6, 'business_id' => 2],

            // Hotang
            ['nama' => 'Full Sosis',      'harga' => 13000, 'kategori_id' => 7, 'business_id' => 2],
            ['nama' => 'Full Mozza',      'harga' => 15000, 'kategori_id' => 7, 'business_id' => 2],
            ['nama' => 'Sosis Mix Mozza', 'harga' => 15000, 'kategori_id' => 7, 'business_id' => 2],
        ];

        foreach ($menus_hotang as $menu_hotang) {
            Menu::create($menu_hotang);
        }
    }
}
