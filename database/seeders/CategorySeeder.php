<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items'],
            ['name' => 'Food & Beverages', 'description' => 'Food and drink products'],
            ['name' => 'Books', 'description' => 'Books and magazines'],
            ['name' => 'Toys', 'description' => 'Toys and games'],
            ['name' => 'Home & Garden', 'description' => 'Home and garden supplies'],
            ['name' => 'Sports', 'description' => 'Sports equipment and gear'],
            ['name' => 'Health & Beauty', 'description' => 'Health and beauty products'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}