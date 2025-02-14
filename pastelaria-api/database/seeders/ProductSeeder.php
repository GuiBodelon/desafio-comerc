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
            'name' => 'Pastel de Calabresa',
            'price' => 5.50,
            'photo' => 'products/pastel-de-calabresa.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Pizza',
            'price' => 5.50,
            'photo' => 'products/pastel-de-pizza.jpg',
        ]);

        Product::create([
            'name' => 'Pastel de Frango',
            'price' => 5.50,
            'photo' => 'products/pastel-de-frango.jpg',
        ]);

        Product::create([
            'name' => 'Caldo de Cana 1L',
            'price' => 5.50,
            'photo' => 'products/caldo-de-cana-1l.jpg',
        ]);

    }
}
