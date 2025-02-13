<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;


class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Método auxiliar para criar um produto com atributos opcionais.
     */
    private function createProduct(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'name' => 'Pastel Genérico',
            'price' => 5.00,
            'photo' => 'products/default.jpg',
        ], $attributes));
    }

    /**
     * Testa a criação de um produto.
     */
    public function test_can_create_a_product()
    {
        $product = $this->createProduct([
            'name' => 'Pastel de Carne',
            'photo' => 'products/pastel-de-carne.jpg',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Pastel de Carne',
            'photo' => 'products/pastel-de-carne.jpg',
        ]);
    }

    /**
     * Testa a leitura de um produto.
     */
    public function test_can_read_a_product()
    {
        $product = $this->createProduct([
            'name' => 'Pastel de Queijo',
            'photo' => 'products/pastel-de-queijo.jpg',
        ]);

        $retrievedProduct = Product::find($product->id);
        $this->assertEquals($product->price, $retrievedProduct->price);
        $this->assertEquals($product->name, $retrievedProduct->name);
    }

    /**
     * Testa a atualização de um produto.
     */
    public function test_can_update_a_product()
    {
        $product = $this->createProduct([
            'name' => 'Pastel de Frango',
            'photo' => 'products/pastel-de-frango.jpg',
        ]);

        $updatedData = [
            'name' => 'Pastel de Frango Especial',
            'price' => 6.50,
            'photo' => 'products/pastel-de-frango-especial.jpg',
        ];

        $product->update($updatedData);
        $this->assertDatabaseHas('products', $updatedData);
    }

    /**
     * Testa a exclusão de um produto (soft delete).
     */
    public function test_can_delete_a_product()
    {
        $product = $this->createProduct([
            'name' => 'Coxinha',
            'photo' => 'products/coxinha.jpg',
        ]);

        $product->delete();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Testa o relacionamento de um produto com múltiplos pedidos.
     */
    public function test_product_has_many_orders()
    {
        // Criar um produto
        $product = $this->createProduct(['name' => 'Risole', 'photo' => 'products/risole.jpg']);

        // Criar clientes manualmente
        $customer1 = Customer::create([
            'name' => 'Cliente 1',
            'email' => 'cliente1@example.com',
            // Adicione outros campos necessários para a criação do cliente
        ]);

        $customer2 = Customer::create([
            'name' => 'Cliente 2',
            'email' => 'cliente2@example.com',
            // Adicione outros campos necessários para a criação do cliente
        ]);

        // Criar pedidos para os clientes
        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'status' => 'pending',
            // Adicione outros campos necessários para a criação do pedido
        ]);

        $order2 = Order::create([
            'customer_id' => $customer2->id,
            'status' => 'pending',
            // Adicione outros campos necessários para a criação do pedido
        ]);

        // Associar os pedidos ao produto
        $product->orders()->attach([$order1->id, $order2->id]);
        $product->load('orders');

        // Verificar se o produto tem os pedidos corretamente associados
        $this->assertCount(2, $product->orders);
        $this->assertTrue($product->orders->contains($order1));
        $this->assertTrue($product->orders->contains($order2));
    }
}
