<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'name' => 'Cliente Teste',
            'email' => 'cliente@email.com',
            'phone' => '123456789',
            'birth_date' => '1990-01-01',
            'address' => 'Rua Teste, 123',
            'complement' => 'Casa',
            'neighborhood' => 'Centro',
            'zip_code' => '12345678',
        ], $overrides));
    }

    #[Test]
    public function it_can_create_a_customer()
    {
        $customer = $this->createCustomer();
        $this->assertDatabaseHas('customers', ['email' => $customer->email]);
    }

    #[Test]
    public function it_can_read_a_customer()
    {
        $customer = $this->createCustomer(['email' => 'maria@email.com']);
        $retrievedCustomer = Customer::find($customer->id);

        $this->assertEquals(
            $customer->only(['id', 'name', 'email', 'phone', 'birth_date', 'address', 'complement', 'neighborhood', 'zip_code']),
            $retrievedCustomer->only(['id', 'name', 'email', 'phone', 'birth_date', 'address', 'complement', 'neighborhood', 'zip_code'])
        );
    }

    #[Test]
    public function it_can_update_a_customer()
    {
        $customer = $this->createCustomer();
        $updatedData = ['name' => 'Cliente Atualizado', 'address' => 'Rua Nova, 456'];

        $customer->update($updatedData);

        $this->assertDatabaseHas('customers', array_merge(['id' => $customer->id], $updatedData));
        $this->assertEquals($updatedData['name'], $customer->fresh()->name);
    }

    #[Test]
    public function it_can_delete_a_customer()
    {
        $customer = $this->createCustomer();
        $customer->delete(); // Soft delete

        $this->assertSoftDeleted($customer);
    }
}
