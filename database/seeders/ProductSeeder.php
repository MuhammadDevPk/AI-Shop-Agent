<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15',
                'category' => 'Phone',
                'price' => 79999,
                'description' => 'Apple smartphone with premium performance.',
                'stock' => 10,
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'category' => 'Phone',
                'price' => 69999,
                'description' => 'Samsung Android flagship phone.',
                'stock' => 12,
            ],
            [
                'name' => 'Dell XPS 13',
                'category' => 'Laptop',
                'price' => 99999,
                'description' => 'Premium Windows laptop.',
                'stock' => 8,
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'category' => 'Accessory',
                'price' => 4500,
                'description' => 'Advanced wireless mouse for professionals.',
                'stock' => 25,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Accessory',
                'price' => 18500,
                'description' => 'Industry-leading noise-canceling headphones.',
                'stock' => 18,
            ],
            [
                'name' => 'Apple MacBook Air M3',
                'category' => 'Laptop',
                'price' => 99999,
                'description' => 'Apple ultrabook with M3 chip.',
                'stock' => 15,
            ],
            [
                'name' => 'Samsung Galaxy Tab S8',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Samsung Android tablet with premium performance.',
                'stock' => 12,
            ],
            [
                'name' => 'Lenovo Yoga Tab 13',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Lenovo Android tablet with premium performance.',
                'stock' => 12,
            ],
            [
                'name' => 'Lenovo Yoga Tab 13',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Lenovo Android tablet with premium performance.',
                'stock' => 12,
            ],
            [
                'name' => 'Lenovo Yoga Tab 13',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Lenovo Android tablet with premium performance.',
                'stock' => 12,
            ],
            [
                'name' => 'Lenovo Yoga Tab 13',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Lenovo Android tablet with premium performance.',
                'stock' => 12,
            ],
            [
                'name' => 'Lenovo Yoga Tab 13',
                'category' => 'Tablet',
                'price' => 59999,
                'description' => 'Lenovo Android tablet with premium performance.',
                'stock' => 12,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
