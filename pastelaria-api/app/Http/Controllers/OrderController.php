<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('customer', 'products')->get());
    }

    public function show(Order $order)
    {
        return response()->json($order->load('customer', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
            ]);

            $order = Order::create(['customer_id' => $validated['customer_id']]);

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['id']);
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ]);
            }

            // Enviar e-mail para o cliente
            Mail::to($order->customer->email)->send(new OrderConfirmation($order));

            return response()->json($order->load('products'), 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'products' => 'sometimes|array',
            'products.*.id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1',
        ]);

        if (isset($validated['customer_id'])) {
            $order->customer_id = $validated['customer_id'];
        }

        $order->save();

        if (isset($validated['products'])) {
            $order->products()->detach();

            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['id']);
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $product->price,
                ]);
            }
        }

        return response()->json($order->load('products'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted'], 200);
    }
}
