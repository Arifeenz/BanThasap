<?php

namespace Database\Factories;

use App\Models\Attraction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attraction>
 */
class AttractionFactory extends Factory
{
    protected $model = Attraction::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'type' => fake()->randomElement(['nature', 'history', 'learning', 'community']),
            'description' => fake()->paragraph(),
            'open_hours' => '08:00 - 17:00',
            'contact' => fake()->phoneNumber(),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
