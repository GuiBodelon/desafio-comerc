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
        Product::create([
            'name' => 'Pastel de Carne',
            'price' => 5.00,
            'photo' => 'images/products/pastel-de-carne.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Queijo',
            'price' => 4.50,
            'photo' => 'images/products/pastel-de-queijo.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Frango',
            'price' => 5.50,
            'photo' => 'images/products/pastel-de-frango.jpg',
        ]);

        Product::create([
            'name' => 'Coxinha',
            'price' => 3.50,
            'photo' => 'images/products/coxinha.jpg',
        ]);

        Product::create([
            'name' => 'Risole',
            'price' => 4.00,
            'photo' => 'images/products/risole.jpg',
        ]);

        Product::create([
            'name' => 'Empada de Frango',
            'price' => 6.00,
            'photo' => 'images/products/empada-frango.jpg',
        ]);

        Product::create([
            'name' => 'Torta de Palmito',
            'price' => 8.00,
            'photo' => 'images/products/torta-palmito.jpg',
        ]);

        Product::create([
            'name' => 'Churros',
            'price' => 4.00,
            'photo' => 'images/products/churros.jpg',
        ]);

        Product::create([
            'name' => 'Bauru',
            'price' => 7.50,
            'photo' => 'images/products/bauru.jpg',
        ]);

        Product::create([
            'name' => 'Salgado de Queijo',
            'price' => 3.00,
            'photo' => 'images/products/salgado-queijo.jpg',
        ]);
    }
}
