<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer1 = \App\Models\Customer::find(1); // Ana Oliveira
        $customer2 = \App\Models\Customer::find(2); // Carlos Santos
        $customer3 = \App\Models\Customer::find(3); // Juliana Almeida
        $customer4 = \App\Models\Customer::find(4); // Felipe Lima
        $customer5 = \App\Models\Customer::find(5); // Marcela Costa

        $product1 = \App\Models\Product::find(1); // Pastel de Carne
        $product2 = \App\Models\Product::find(2); // Pastel de Queijo
        $product3 = \App\Models\Product::find(3); // Pastel de Calabresa
        $product4 = \App\Models\Product::find(4); // Pastel de Pizza
        $product5 = \App\Models\Product::find(5); // Pastel de Frango
        $product6 = \App\Models\Product::find(6); // Caldo de Cana 1L

        $order1 = Order::create(['customer_id' => $customer1->id]);
        $order1->products()->attach([$product1->id, $product2->id]);

        $order2 = Order::create(['customer_id' => $customer2->id]);
        $order2->products()->attach([$product3->id, $product4->id, $product5->id]);

        $order3 = Order::create(['customer_id' => $customer3->id]);
        $order3->products()->attach([$product6->id]);

        $order4 = Order::create(['customer_id' => $customer4->id]);
        $order4->products()->attach([$product5->id, $product4->id]);

        $order5 = Order::create(['customer_id' => $customer5->id]);
        $order5->products()->attach([$product2->id, $product1->id]);

        $order6 = Order::create(['customer_id' => $customer1->id]);
        $order6->products()->attach([$product3->id, $product4->id]);

        $order7 = Order::create(['customer_id' => $customer2->id]);
        $order7->products()->attach([$product3->id, $product5->id]);

        $order8 = Order::create(['customer_id' => $customer3->id]);
        $order8->products()->attach([$product2->id]);

        $order9 = Order::create(['customer_id' => $customer4->id]);
        $order9->products()->attach([$product4->id, $product5->id]);

        $order10 = Order::create(['customer_id' => $customer5->id]);
        $order10->products()->attach([$product4->id, $product1->id]);
    }
}
