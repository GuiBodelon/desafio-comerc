<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;

class OrderService
{
    public function createOrder(array $data)
    {
        // Cria o pedido apenas com o customer_id
        $order = Order::create([
            'customer_id' => $data['customer_id']
        ]);

        // Se produto(s) foram enviados, associa-os ao pedido
        if (isset($data['product_id'])) {
            // Se for um único ID ou um array, o attach funciona para ambos
            $order->products()->attach($data['product_id']);
        }

        // Enviar e-mail para o cliente após a criação do pedido
        $customer = Customer::findOrFail($data['customer_id']);
        Mail::to($customer->email)->send(new OrderCreated($order));

        return $order;
    }

    public function updateOrder(Order $order, array $data)
    {
        if (isset($data['product_id'])) {
            $order->products()->sync($data['product_id']);
        }

        return $order;
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
    }
}
