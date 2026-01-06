<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch categories to assign products
        $ringCat = Category::where('name', 'Rings')->first();
        $necklaceCat = Category::where('name', 'Necklaces')->first();
        $braceletCat = Category::where('name', 'Bracelets')->first();
        
        $products = [
            [
                'name' => 'Classic Gold Wedding Band',
                'description' => 'A timeless classic yellow gold wedding band, polished to perfection.',
                'price' => 45000.00,
                'gold_weight' => '4.2g',
                'karat' => '22K',
                'stock_status' => 'in_stock',
                'category_id' => $ringCat?->id,
                 // Using the dummy paths from frontend as placeholders
                'images_json' => ['/images/products/ring1.png', '/images/products/ring2.png'],
            ],
            [
                'name' => 'Diamond Solitaire Ring',
                'description' => 'Exquisite single diamond set in 18K white gold.',
                'price' => 125000.00,
                'gold_weight' => '3.5g',
                'karat' => '18K',
                'stock_status' => 'in_stock',
                'category_id' => $ringCat?->id,
                'images_json' => ['/images/products/ring2.png'],
            ],
            [
                'name' => 'Royal Gold Necklace',
                'description' => 'Intricate traditional design for weddings and special occasions.',
                'price' => 250000.00,
                'gold_weight' => '24g',
                'karat' => '22K',
                'stock_status' => 'in_stock',
                'category_id' => $necklaceCat?->id,
                'images_json' => ['/images/products/necklace1.png', '/images/products/necklace2.png'],
            ],
            [
                'name' => 'Simple Chain Necklace',
                'description' => 'Daily wear lightweight gold chain.',
                'price' => 65000.00,
                'gold_weight' => '8g',
                'karat' => '22K',
                'stock_status' => 'in_stock',
                'category_id' => $necklaceCat?->id,
                'images_json' => ['/images/products/necklace2.png'],
            ],
             [
                'name' => 'Charm Bracelet',
                'description' => 'Lovely bracelet with heart-shaped charms.',
                'price' => 85000.00,
                'gold_weight' => '10g',
                'karat' => '22K',
                'stock_status' => 'low_stock',
                'category_id' => $braceletCat?->id,
                'images_json' => ['/images/products/bracelet1.png'],
            ],
        ];

        foreach ($products as $product) {
            // Encode images array to JSON before saving (if not cast in model)
            // But Laravel casts 'array' or 'json' handle this automatically if defined in Model casts.
            // Let's ensure Model has casts or we manually encode.
            // Checking Product model... it has 'casts' => ['images_json' => 'array'] usually.
            // Assuming it does, we pass array. If not, json_encode.
            // Let's safe side, use array, assuming model has casts.
            
            Product::create($product);
        }
    }
}
