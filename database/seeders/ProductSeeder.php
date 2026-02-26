<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Mouse', 'category' => 'Electronics', 'price' => 25.99, 'quantity' => 50, 'sku' => 'ELEC001'],
            ['name' => 'USB Cable', 'category' => 'Electronics', 'price' => 9.99, 'quantity' => 100, 'sku' => 'ELEC002'],
            ['name' => 'Bluetooth Speaker', 'category' => 'Electronics', 'price' => 49.99, 'quantity' => 30, 'sku' => 'ELEC003'],
            ['name' => 'Phone Case', 'category' => 'Electronics', 'price' => 15.99, 'quantity' => 75, 'sku' => 'ELEC004'],
            ['name' => 'T-Shirt', 'category' => 'Clothing', 'price' => 19.99, 'quantity' => 60, 'sku' => 'CLTH001'],
            ['name' => 'Jeans', 'category' => 'Clothing', 'price' => 49.99, 'quantity' => 40, 'sku' => 'CLTH002'],
            ['name' => 'Sneakers', 'category' => 'Clothing', 'price' => 79.99, 'quantity' => 25, 'sku' => 'CLTH003'],
            ['name' => 'Coffee Beans', 'category' => 'Food & Beverages', 'price' => 12.99, 'quantity' => 80, 'sku' => 'FOOD001'],
            ['name' => 'Energy Drink', 'category' => 'Food & Beverages', 'price' => 2.99, 'quantity' => 150, 'sku' => 'FOOD002'],
            ['name' => 'Chocolate Bar', 'category' => 'Food & Beverages', 'price' => 1.99, 'quantity' => 200, 'sku' => 'FOOD003'],
            ['name' => 'Novel', 'category' => 'Books', 'price' => 14.99, 'quantity' => 35, 'sku' => 'BOOK001'],
            ['name' => 'Magazine', 'category' => 'Books', 'price' => 5.99, 'quantity' => 50, 'sku' => 'BOOK002'],
            ['name' => 'Action Figure', 'category' => 'Toys', 'price' => 24.99, 'quantity' => 45, 'sku' => 'TOYS001'],
            ['name' => 'Board Game', 'category' => 'Toys', 'price' => 34.99, 'quantity' => 20, 'sku' => 'TOYS002'],
            ['name' => 'Plant Pot', 'category' => 'Home & Garden', 'price' => 8.99, 'quantity' => 55, 'sku' => 'HOME001'],
            ['name' => 'Kitchen Knife', 'category' => 'Home & Garden', 'price' => 29.99, 'quantity' => 30, 'sku' => 'HOME002'],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if ($category) {
                Product::create([
                    'name' => $productData['name'],
                    'category_id' => $category->id,
                    'price' => $productData['price'],
                    'quantity' => $productData['quantity'],
                    'sku' => $productData['sku'],
                    'description' => 'Sample product for ' . $productData['name'],
                ]);
            }
        }
    }
}