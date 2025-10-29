<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the brand is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}