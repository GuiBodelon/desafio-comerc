<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(): JsonResponse
    {
        return response()->json(Customer::all(), 200);
    }

    public function findByEmail(Request $request): JsonResponse
    {
        // Obtendo o e-mail a partir da requisição
        $email = $request->input('email'); // ou $request->email

        // Realizando a consulta
        $customer = Customer::where('email', $email)->first();

        // Verificando se o cliente foi encontrado
        if (!$customer) {
            return response()->json(null, 404);
        }

        // Retornando a resposta com o cliente encontrado
        return response()->json($customer, 200);
    }

    public function show($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer, 200);
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->createCustomer($request->validated());
        return response()->json($customer, 201);
    }

    public function update(CustomerRequest $request, $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $updatedCustomer = $this->customerService->updateCustomer($customer, $request->validated());
        return response()->json($updatedCustomer, 200);
    }

    public function destroy($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $this->customerService->deleteCustomer($customer);
        return response()->json(null, 204);
    }
}
