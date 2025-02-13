<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'name' => 'Cliente Teste',
            'email' => 'cliente.teste@email.com',
            'phone' => '123456789',
            'birth_date' => '1990-01-01',
            'address' => 'Rua Teste, 123',
            'complement' => 'Apto 1',
            'neighborhood' => 'Centro',
            'zip_code' => '12345678',
        ], $overrides));
    }

    private function createProduct(array $overrides = []): Product
    {
        return Product::create(array_merge([
            'name' => 'Produto Teste',
            'price' => 10.00,
            'photo' => 'produto_teste.jpg',
        ], $overrides));
    }

    #[Test]
    public function it_can_create_an_order()
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct();

        $order = Order::create(['customer_id' => $customer->id]);
        $order->products()->attach($product->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
        ]);
    }

    #[Test]
    public function it_can_read_an_order()
    {
        $order = Order::create(['customer_id' => $this->createCustomer()->id]);

        $this->assertEquals($order->id, Order::find($order->id)->id);
    }

    #[Test]
    public function it_can_update_an_order()
    {
        // Cria cliente e produtos
        $customer = $this->createCustomer();
        $product1 = $this->createProduct(['name' => 'Produto 1']);
        $product2 = $this->createProduct(['name' => 'Produto 2']);

        // Cria um pedido com o cliente e o primeiro produto
        $order = Order::create(['customer_id' => $customer->id]);
        $order->products()->attach($product1->id);

        // Verifica que o pedido foi criado corretamente com o produto 1
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
        ]);
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product1->id,
        ]);

        // Atualiza o pedido, removendo o produto1 e adicionando o produto2
        $orderData = [
            'product_id' => [$product2->id], // Atualiza com o novo produto
        ];

        // Simula o comportamento do serviço de atualização
        $order = app(OrderService::class)->updateOrder($order, $orderData);

        // Verifica se o pedido foi atualizado corretamente no banco
        $this->assertDatabaseMissing('order_product', [
            'order_id' => $order->id,
            'product_id' => $product1->id, // Produto 1 foi removido
        ]);
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product2->id, // Produto 2 foi adicionado
        ]);
    }

    #[Test]
    public function it_can_delete_an_order()
    {
        $order = Order::create(['customer_id' => $this->createCustomer()->id]);
        $order->delete();

        $this->assertSoftDeleted($order);
    }

    #[Test]
    public function it_belongs_to_a_customer()
    {
        $customer = $this->createCustomer();
        $order = Order::create(['customer_id' => $customer->id]);

        $this->assertEquals($customer->id, $order->customer->id);
    }

    #[Test]
    public function it_belongs_to_many_products()
    {
        $order = Order::create(['customer_id' => $this->createCustomer()->id]);
        $products = [
            $this->createProduct(['name' => 'Pastel de Queijo']),
            $this->createProduct(['name' => 'Coxinha'])
        ];

        $order->products()->attach(array_column($products, 'id'));
        $this->assertCount(2, $order->products);
    }
}
