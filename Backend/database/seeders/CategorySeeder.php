<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Rings',
                'description' => 'Elegant gold and diamond rings for every occasion',
            ],
            [
                'name' => 'Necklaces',
                'description' => 'Stunning gold chains and necklaces',
            ],
            [
                'name' => 'Bracelets',
                'description' => 'Handcrafted bracelets for men and women',
            ],
            [
                'name' => 'Earrings',
                'description' => 'Beautiful studs and drops',
            ],
            [
                'name' => 'Pendants',
                'description' => 'Intricate pendants to match your chain',
            ],
        ];

        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['name']);
            Category::create($category);
        }
    }
}
