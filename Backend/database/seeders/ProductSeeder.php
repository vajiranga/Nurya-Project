<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $ringsCategory = Category::where('name', 'Rings')->first();
        $necklacesCategory = Category::where('name', 'Necklaces')->first();
        $braceletsCategory = Category::where('name', 'Bracelets')->first();
        $earringsCategory = Category::where('name', 'Earrings')->first();

        $products = [
            // Rings
            [
                'name' => 'Diamond Gold Ring',
                'description' => 'Elegant gold ring with brilliant diamond center stone',
                'price' => 125000.00,
                'gold_weight' => '5.5',
                'karat' => '22K',
                'category_id' => $ringsCategory->id,
                'images_json' => json_encode(['/images/products/ring1.png']),
            ],
            [
                'name' => 'Wedding Band Ring',
                'description' => 'Classic wedding band in pure gold with diamond accents',
                'price' => 95000.00,
                'gold_weight' => '4.2',
                'karat' => '22K',
                'category_id' => $ringsCategory->id,
                'images_json' => json_encode(['/images/products/ring2.png']),
            ],
            
            // Necklaces
            [
                'name' => 'Gold Chain Necklace',
                'description' => 'Beautiful gold chain with intricate pendant design',
                'price' => 185000.00,
                'gold_weight' => '12.5',
                'karat' => '22K',
                'category_id' => $necklacesCategory->id,
                'images_json' => json_encode(['/images/products/necklace1.png']),
            ],
            [
                'name' => 'Heart Pendant Necklace',
                'description' => 'Romantic heart-shaped pendant with diamonds',
                'price' => 165000.00,
                'gold_weight' => '10.3',
'karat' => '22K',
                'category_id' => $necklacesCategory->id,
                'images_json' => json_encode(['/images/products/necklace2.png']),
            ],
            
            // Bracelets
            [
                'name' => 'Gemstone Bracelet',
                'description' => 'Luxury gold bracelet with precious gemstones',
                'price' => 145000.00,
                'gold_weight' => '8.7',
                'karat' => '22K',
                'category_id' => $braceletsCategory->id,
                'images_json' => json_encode(['/images/products/bracelet1.png']),
            ],
            [
                'name' => 'Traditional Gold Bangle',
                'description' => 'Handcrafted bangle with traditional patterns',
                'price' => 135000.00,
                'gold_weight' => '9.2',
                'karat' => '22K',
                'category_id' => $braceletsCategory->id,
                'images_json' => json_encode(['/images/products/bracelet2.png']),
            ],
            
            // Earrings
            [
                'name' => 'Diamond Drop Earrings',
                'description' => 'Stunning drop earrings with diamonds',
                'price' => 75000.00,
                'gold_weight' => '3.5',
                'karat' => '22K',
                'category_id' => $earringsCategory->id,
                'images_json' => json_encode(['/images/products/earrings1.png']),
            ],
            [
                'name' => 'Diamond Stud Earrings',
                'description' => 'Classic stud earrings with sparkling diamonds',
                'price' => 65000.00,
                'gold_weight' => '2.8',
                'karat' => '22K',
                'category_id' => $earringsCategory->id,
                'images_json' => json_encode(['/images/products/earrings2.png']),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
