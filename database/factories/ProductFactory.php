<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true) . ' ' . fake()->word();
        
        return [
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 50, 800),
            'stock_available' => fake()->numberBetween(5, 100),
            'image_url' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product has an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_url' => 'products/test-product.jpg',
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_available' => 0,
        ]);
    }
}