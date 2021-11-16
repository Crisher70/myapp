<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(
            [
                'name' => 'Televisión',
                'price' => 100000,
                'quantity' => 100
            ]
        );

        Product::create(
            [
                'name' => 'Mesa',
                'price' => 50000,
                'quantity' => 10
            ]
        );

        Product::create(
            [
                'name' => 'Silla escritorio',
                'price' => 80000,
                'quantity' => 50
            ]
        );
    }
}
