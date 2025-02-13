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
        // Cria um cliente com um e-mail único
        $customer = Customer::create([
            'name' => 'João Silva',
            'email' => 'joao.silva+1@email.com',
            'phone' => '123456789',
            'birth_date' => '1990-01-01',
            'address' => 'Rua ABC, 123',
            'complement' => 'Casa',
            'neighborhood' => 'Centro',
            'zip_code' => '12345678',
        ]);

        // Cria um produto
        $product = Product::create([
            'name' => 'Pastel de Carne',
            'price' => 10.00,
            'photo' => 'pastel_de_carne.jpg',
        ]);

        // Cria um pedido
        $order = Order::create(['customer_id' => $customer->id]);

        // Associa o produto ao pedido
        $order->products()->attach($product->id);

        // Verifica se o pedido foi criado
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
        ]);
    }

    #[Test]
    public function it_can_read_an_order()
    {
        // Cria um cliente com um e-mail único
        $customer = Customer::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira+1@email.com',
            'phone' => '987654321',
            'birth_date' => '1991-02-02',
            'address' => 'Rua XYZ, 456',
            'complement' => 'Apto 101',
            'neighborhood' => 'Bairro Novo',
            'zip_code' => '87654321',
        ]);

        // Cria um pedido
        $order = Order::create(['customer_id' => $customer->id]);

        // Recupera o pedido
        $retrievedOrder = Order::find($order->id);

        // Verifica se o pedido foi recuperado corretamente
        $this->assertEquals($order->id, $retrievedOrder->id);
    }

    #[Test]
    public function it_can_delete_an_order()
    {
        // Cria um cliente com um e-mail único
        $customer = Customer::create([
            'name' => 'Carlos Pereira',
            'email' => 'carlos.pereira+1@email.com',
            'phone' => '123123123',
            'birth_date' => '1992-03-03',
            'address' => 'Rua DEF, 789',
            'complement' => 'Bloco B',
            'neighborhood' => 'Vila Mariana',
            'zip_code' => '23456789',
        ]);

        // Cria um pedido
        $order = Order::create(['customer_id' => $customer->id]);

        // Exclui o pedido
        $order->delete();

        // Verifica se o pedido foi excluído
        $this->assertSoftDeleted($order);
    }

    #[Test]
    public function it_belongs_to_a_customer()
    {
        // Cria um cliente com um e-mail único
        $customer = Customer::create([
            'name' => 'Ana Souza',
            'email' => 'ana.souza+1@email.com',
            'phone' => '321321321',
            'birth_date' => '1993-04-04',
            'address' => 'Rua GHI, 101',
            'complement' => 'Casa 2',
            'neighborhood' => 'Santa Efigênia',
            'zip_code' => '34567890',
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
        // Cria um cliente com um e-mail único
        $customer = Customer::create([
            'name' => 'Felipe Lima',
            'email' => 'felipe.lima+1@email.com',
            'phone' => '456456456',
            'birth_date' => '1994-05-05',
            'address' => 'Rua JKL, 202',
            'complement' => 'Apto 303',
            'neighborhood' => 'Vila Madalena',
            'zip_code' => '45678901',
        ]);

        // Cria dois produtos
        $product1 = Product::create([
            'name' => 'Pastel de Queijo',
            'price' => 8.00,
            'photo' => 'pastel_de_queijo.jpg',
        ]);

        $product2 = Product::create([
            'name' => 'Coxinha',
            'price' => 5.00,
            'photo' => 'coxinha.jpg',
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
