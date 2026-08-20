<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['nama' => 'Smoothies', 'business_id' => 2],
            ['nama' => 'Juice', 'business_id' => 2],
            ['nama' => 'Other', 'business_id' => 2],
            ['nama' => 'Pisgor', 'business_id' => 1],
            ['nama' => 'Cemilans', 'business_id' => 1],
            ['nama' => 'Original', 'business_id' => 2],
            ['nama' => 'Hotang', 'business_id' => 2],
        ]);
    }
}
