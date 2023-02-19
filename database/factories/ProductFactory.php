<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'type' => Arr::random(['watches','sunglasses','opticals','straps']),
            'price' => $this->faker->randomFloat(2, 50, 200),
            'description' => $this->faker->paragraph(2),
            'family_id' => null,
        ];
    }
}
