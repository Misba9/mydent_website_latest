<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DefaultProductsSeeder extends Seeder
{
    /**
     * Seed default MyDent products.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'MyDent Clear Aligner Starter Kit',
                'description' => 'Complete home alignment starter kit including 3D scan voucher, impression kit, and personal alignment plan.',
                'category' => 'Aligners',
                'price' => 24999,
                'discount_price' => 19999,
                'quantity' => 50,
                'thumbnail' => 'assets/image/mydent-logo.png',
            ],
            [
                'id' => 2,
                'name' => 'MyDent Aligner Cleaning Crystals (30 Pack)',
                'description' => 'Specially formulated effervescent cleaning tablets to keep clear aligners fresh, transparent, and odor-free.',
                'category' => 'Accessories',
                'price' => 1199,
                'discount_price' => 899,
                'quantity' => 100,
                'thumbnail' => 'assets/image/mydent-logo.png',
            ],
            [
                'id' => 3,
                'name' => 'MyDent Aligner Removal Tool & Travel Case',
                'description' => 'Ergonomic aligner extraction tool with protective magnetic travel storage case.',
                'category' => 'Accessories',
                'price' => 699,
                'discount_price' => 499,
                'quantity' => 150,
                'thumbnail' => 'assets/image/mydent-logo.png',
            ],
            [
                'id' => 4,
                'name' => 'MyDent Ultrasonic UV Aligner Sanitizer',
                'description' => 'Advanced 42kHz ultrasonic cleaner with UV-C sterilization for deep aligner cleaning in 5 minutes.',
                'category' => 'Care',
                'price' => 3499,
                'discount_price' => 2499,
                'quantity' => 40,
                'thumbnail' => 'assets/image/mydent-logo.png',
            ]
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['id' => $p['id']], $p);
        }
    }
}
