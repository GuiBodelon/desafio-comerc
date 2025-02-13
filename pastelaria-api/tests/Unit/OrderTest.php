<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_order()
    {
        // Cria um cliente manualmente
        $customer = Customer::create([
            'name' => 'João Silva',
            'email' => 'joao.silva@email.com',
            'address' => 'Rua ABC, 123',
            'phone' => '123456789',
        ]);

        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Pastel de Carne',
            'price' => 10.00,
            'image' => 'pastel_de_carne.jpg',
            'photo' => 'foto_pastel.jpg', // Adicionado campo 'photo'
        ]);

        // Cria um pedido associado ao cliente
        $order = Order::create(['customer_id' => $customer->id]);

        // Associa o produto ao pedido
        $order->products()->attach($product->id);

        // Verifica se o pedido foi criado no banco de dados
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
        ]);

        // Verifica se o produto foi associado ao pedido
        $this->assertDatabaseHas('order_product', [
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
    }

    #[Test]
    public function it_can_read_an_order()
    {
        // Cria um cliente manualmente
        $customer = Customer::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@email.com',
            'address' => 'Rua XYZ, 456',
            'phone' => '987654321',
        ]);

        // Cria um pedido manualmente
        $order = Order::create(['customer_id' => $customer->id]);

        // Recupera o pedido do banco de dados
        $retrievedOrder = Order::find($order->id);

        // Verifica se o pedido foi recuperado corretamente
        $this->assertEquals($order->id, $retrievedOrder->id);
        $this->assertEquals($order->customer_id, $retrievedOrder->customer_id);
    }

    #[Test]
    public function it_can_delete_an_order()
    {
        // Cria um cliente manualmente
        $customer = Customer::create([
            'name' => 'Carlos Pereira',
            'email' => 'carlos.pereira@email.com',
            'address' => 'Rua DEF, 789',
            'phone' => '123123123',
        ]);

        // Cria um pedido manualmente
        $order = Order::create(['customer_id' => $customer->id]);

        // Exclui o pedido (soft delete)
        $order->delete();

        // Verifica se o pedido foi marcado como excluído
        $this->assertSoftDeleted($order);
    }

    #[Test]
    public function it_belongs_to_a_customer()
    {
        // Cria um cliente manualmente
        $customer = Customer::create([
            'name' => 'Ana Souza',
            'email' => 'ana.souza@email.com',
            'address' => 'Rua GHI, 101',
            'phone' => '321321321',
        ]);

        // Cria um pedido associado ao cliente
        $order = Order::create(['customer_id' => $customer->id]);

        // Recarrega o pedido com o relacionamento de cliente
        $order->load('customer');

        // Verifica o relacionamento com o cliente
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    #[Test]
    public function it_belongs_to_many_products()
    {
        // Cria um cliente manualmente
        $customer = Customer::create([
            'name' => 'Felipe Lima',
            'email' => 'felipe.lima@email.com',
            'address' => 'Rua JKL, 202',
            'phone' => '456456456',
        ]);

        // Cria dois produtos manualmente
        $product1 = Product::create([
            'name' => 'Pastel de Queijo',
            'price' => 8.00,
            'image' => 'pastel_de_queijo.jpg',
            'photo' => 'foto_queijo.jpg',
        ]);

        $product2 = Product::create([
            'name' => 'Coxinha',
            'price' => 5.00,
            'image' => 'coxinha.jpg',
            'photo' => 'foto_coxinha.jpg',
        ]);

        // Cria um pedido associado ao cliente
        $order = Order::create(['customer_id' => $customer->id]);

        // Associa os produtos ao pedido
        $order->products()->attach([$product1->id, $product2->id]);

        // Verifica o relacionamento com os produtos
        $this->assertCount(2, $order->products);
        $this->assertTrue($order->products->contains($product1));
        $this->assertTrue($order->products->contains($product2));
    }
}
