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
        $order = Order::create($data);

        // Enviar e-mail para o cliente após a criação do pedido
        $customer = Customer::findOrFail($data['customer_id']);
        Mail::to($customer->email)->send(new OrderCreated($order));

        return $order;
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
    }
}
