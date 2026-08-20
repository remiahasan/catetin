<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use FakerRestaurant\Provider\id_ID\Restaurant;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = $this->faker;
        $faker->addProvider(new Restaurant($faker));
        return [
            'nama' => $faker->foodName(),
            'deskripsi' => fake()->sentence(),
            'harga' => fake()->randomElement([10000, 15000, 20000, 25000]),
            'kategori_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'business_id' => Business::inRandomOrder()->first()->id ?? Business::factory(),
            'super_kategori' => fake()->randomElement(['Smoothies', 'Hotang']),

        ];
    }
}
