<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'seller_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'reference_number' => $this->faker->uuid,
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'original_price' => $this->faker->randomFloat(2, 100, 10000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'condition_type' => $this->faker->randomElement(['New', 'Used']),
            'movement_type' => $this->faker->randomElement(['Automatic', 'Quartz', 'Manual']),
            'case_material' => $this->faker->randomElement(['Steel', 'Gold', 'Titanium']),
            'dial_color' => $this->faker->safeColorName,
            'strap_material' => $this->faker->randomElement(['Leather', 'Metal', 'Rubber']),
            'water_resistance' => '30m',
            'year_manufactured' => $this->faker->year,
            'warranty_info' => '2 years',
            'is_featured' => $this->faker->boolean,
            'is_active' => true,
            'status' => 'available',
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
