<?php

namespace App\DTOs;

class ProductData
{
    public function __construct(
        public readonly int $brand_id,
        public readonly int $category_id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly int $stock_available,
        public readonly bool $is_active = true,
    ) {}

    /**
     * Create from validated array (from Form Request)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            brand_id: (int) $data['brand_id'],
            category_id: (int) $data['category_id'],
            name: $data['name'],
            description: $data['description'] ?? null,
            price: (float) $data['price'],
            stock_available: (int) $data['stock_available'],
            is_active: $data['is_active'] ?? true,
        );
    }

    /**
     * Convert to array for model creation/update
     */
    public function toArray(): array
    {
        return [
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock_available' => $this->stock_available,
            'is_active' => $this->is_active,
            // NO incluimos image_url aquí, lo maneja el Service
        ];
    }
}