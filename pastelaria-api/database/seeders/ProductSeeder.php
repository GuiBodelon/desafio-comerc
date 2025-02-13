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
            'photo' => 'products/pastel-de-carne.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Queijo',
            'price' => 4.50,
            'photo' => 'products/pastel-de-queijo.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Frango',
            'price' => 5.50,
            'photo' => 'products/pastel-de-frango.jpg',
        ]);

        Product::create([
            'name' => 'Coxinha',
            'price' => 3.50,
            'photo' => 'products/coxinha.jpg',
        ]);

        Product::create([
            'name' => 'Risole',
            'price' => 4.00,
            'photo' => 'products/risole.jpg',
        ]);

        Product::create([
            'name' => 'Empada de Frango',
            'price' => 6.00,
            'photo' => 'products/empada-frango.jpg',
        ]);

        Product::create([
            'name' => 'Torta de Palmito',
            'price' => 8.00,
            'photo' => 'products/torta-palmito.jpg',
        ]);

        Product::create([
            'name' => 'Churros',
            'price' => 4.00,
            'photo' => 'products/churros.jpg',
        ]);

        Product::create([
            'name' => 'Bauru',
            'price' => 7.50,
            'photo' => 'products/bauru.jpg',
        ]);

        Product::create([
            'name' => 'Salgado de Queijo',
            'price' => 3.00,
            'photo' => 'products/salgado-queijo.jpg',
        ]);
    }
}
