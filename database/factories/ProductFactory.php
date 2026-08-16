<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'category' => fake()->randomElement(['food', 'handicraft', 'health', 'other']),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'unit' => 'ชิ้น',
            'contact' => fake()->phoneNumber(),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
