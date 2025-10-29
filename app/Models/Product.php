<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_available',
        'image_url',  // Cambiado de 'image'
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_available' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'image_full_url',  // Agregar URL completa como atributo
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get full URL for product image
     */
    public function getImageFullUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        return url('storage/' . $this->image_url);
    }
}