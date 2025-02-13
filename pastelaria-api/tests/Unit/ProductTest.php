<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_product()
    {
        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Pastel de Carne',
            'price' => 5.00,
            'photo' => 'products/pastel-de-carne.jpg',
        ]);

        // Verifica se o produto foi criado no banco de dados
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Pastel de Carne',
            'price' => 5.00,
            'photo' => 'products/pastel-de-carne.jpg',
        ]);
    }

    #[Test]
    public function it_can_read_a_product()
    {
        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Pastel de Queijo',
            'price' => 4.50,
            'photo' => 'products/pastel-de-queijo.jpg',
        ]);

        // Recupera o produto do banco de dados
        $retrievedProduct = Product::find($product->id);

        // Verifica se o produto foi recuperado corretamente
        $this->assertEquals($product->id, $retrievedProduct->id);
        $this->assertEquals($product->name, $retrievedProduct->name);
        $this->assertEquals($product->price, $retrievedProduct->price);
        $this->assertEquals($product->photo, $retrievedProduct->photo);
    }

    #[Test]
    public function it_can_update_a_product()
    {
        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Pastel de Frango',
            'price' => 5.50,
            'photo' => 'products/pastel-de-frango.jpg',
        ]);

        // Atualiza o produto
        $product->update([
            'name' => 'Pastel de Frango Especial',
            'price' => 6.50,
            'photo' => 'products/pastel-de-frango-especial.jpg',
        ]);

        // Verifica se o produto foi atualizado no banco de dados
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Pastel de Frango Especial',
            'price' => 6.50,
            'photo' => 'products/pastel-de-frango-especial.jpg',
        ]);
    }

    #[Test]
    public function it_can_delete_a_product()
    {
        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Coxinha',
            'price' => 3.50,
            'photo' => 'products/coxinha.jpg',
        ]);

        // Exclui o produto (soft delete)
        $product->delete();

        // Verifica se o produto foi marcado como excluído
        $this->assertSoftDeleted($product);
    }

    #[Test]
    public function it_has_many_orders()
    {
        // Cria um produto manualmente
        $product = Product::create([
            'name' => 'Risole',
            'price' => 4.00,
            'photo' => 'products/risole.jpg',
        ]);

        // Cria dois pedidos manualmente
        $order1 = Order::create(['customer_id' => 1]);
        $order2 = Order::create(['customer_id' => 2]);

        // Associa os pedidos ao produto
        $product->orders()->attach([$order1->id, $order2->id]);

        // Recarrega o produto com os pedidos associados
        $product->load('orders');

        // Verifica o relacionamento com os pedidos
        $this->assertCount(2, $product->orders);
        $this->assertTrue($product->orders->contains($order1));
        $this->assertTrue($product->orders->contains($order2));
    }
}
