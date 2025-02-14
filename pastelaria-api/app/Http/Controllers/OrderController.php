<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Customer;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        return response()->json(Order::with(['customer', 'products'])->get(), 200);
    }

    public function show($id): JsonResponse
    {
        $order = Order::with(['customer', 'products'])->findOrFail($id);
        return response()->json($order, 200);
    }

    public function store(Request $request)
    {
        $orderService = new OrderService();
        return $orderService->createOrder($request); // Passando o objeto Request
    }

    public function update(OrderRequest $request, $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order = $this->orderService->updateOrder($order, $request->validated());
        return response()->json($order->load('customer', 'products'), 200);
    }

    public function destroy($id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $this->orderService->deleteOrder($order);
        return response()->json(null, 204);
    }
}
