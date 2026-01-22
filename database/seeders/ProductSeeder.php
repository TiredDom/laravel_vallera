<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Sofas' => '/images/category-living-room.jpg',
            'Chairs' => '/images/category-dining.jpg',
            'Tables' => '/images/category-dining.jpg',
            'Beds' => '/images/category-bedroom.jpg',
            'Storage' => '/images/category-bedroom.jpg',
            'Desks' => '/images/category-dining.jpg',
            'Lighting' => '/images/category-bedroom.jpg',
        ];
        foreach ($categories as $category => $image) {
            for ($i = 1; $i <= 4; $i++) {
                Product::create([
                    'name' => $category . ' Product ' . $i,
                    'description' => 'Sample description for ' . $category . ' Product ' . $i,
                    'price' => rand(1000, 20000),
                    'stock' => rand(1, 20),
                    'category' => $category,
                    'image_path' => ltrim($image, '/'),
                    'is_featured' => $i === 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
