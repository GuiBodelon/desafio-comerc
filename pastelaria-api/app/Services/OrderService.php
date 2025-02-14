<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use Illuminate\Http\Request;
use App\Models\Product;

class OrderService
{
    public function createOrder(Request $request)
    {
        // Validação para garantir que 'products' é um array de objetos com 'product_id' e 'quantity'
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',  // Verifica se o cliente existe
            'products' => 'required|array',  // Certifica que 'products' é um array
            'products.*.product_id' => 'required|exists:products,id',  // Verifica se o product_id existe
            'products.*.quantity' => 'required|integer|min:1',  // Verifica se a quantidade é válida
        ]);

        // Criação do pedido
        $order = Order::create([
            'customer_id' => $request->customer_id,  // Definindo o cliente
        ]);

        // Associar produtos ao pedido com a quantidade
        foreach ($request->products as $product) {
            // Verifica se o produto existe e a quantidade está sendo enviada corretamente
            $order->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'], // Associando a quantidade
            ]);
        }

        // Envio de e-mail
        Mail::to($order->customer->email)->send(new OrderCreated($order));

        // Retorna uma resposta com o ID do pedido
        return response()->json([
            'message' => 'Pedido criado com sucesso',
            'order_id' => $order->id,
        ], 200);
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
