<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image_path',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        // If no image path, return null
        if (!$this->image_path) {
            return null;
        }

        // If it's a Base64 string, return the route to serve it
        // This prevents sending the huge string in the JSON payload
        if (str_starts_with($this->image_path, 'data:image')) {
            return route('products.image', ['id' => $this->id]);
        }

        // Fallback for existing images or if we switch back to storage
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}
