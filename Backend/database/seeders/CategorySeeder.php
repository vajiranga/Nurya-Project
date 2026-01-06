<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Rings',
                'slug' => 'rings',
                'description' => 'Elegant gold rings with premium craftsmanship',
            ],
            [
                'name' => 'Necklaces',
                'slug' => 'necklaces',
                'description' => 'Beautiful gold necklaces for every occasion',
            ],
            [
                'name' => 'Bracelets',
                'slug' => 'bracelets',
                'description' => 'Stunning gold bracelets with intricate designs',
            ],
            [
                'name' => 'Earrings',
                'slug' => 'earrings',
                'description' => 'Fashionable gold earrings to complete your look',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
