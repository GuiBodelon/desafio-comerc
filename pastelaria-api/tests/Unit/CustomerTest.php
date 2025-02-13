<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_customer()
    {
        $customerData = [
            'name' => 'João Silva',
            'email' => 'joao.silva@email.com',
            'phone' => '123456789',
            'birth_date' => '1990-01-01',
            'address' => 'Rua ABC, 123',
            'complement' => 'Casa',
            'neighborhood' => 'Centro',
            'zip_code' => '12345678',
        ];

        $customer = Customer::create($customerData);

        $this->assertDatabaseHas('customers', [
            'email' => $customerData['email'],
        ]);
    }

    #[Test]
    public function it_can_read_a_customer()
    {
        $customerData = [
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@email.com',
            'phone' => '987654321',
            'birth_date' => '1991-02-02',
            'address' => 'Rua XYZ, 456',
            'complement' => 'Apto 101',
            'neighborhood' => 'Bairro Novo',
            'zip_code' => '87654321',
        ];

        $customer = Customer::create($customerData);
        $retrievedCustomer = Customer::find($customer->id);

        $this->assertEquals($customerData['email'], $retrievedCustomer->email);
    }

    #[Test]
    public function it_can_update_a_customer()
    {
        $customerData = [
            'name' => 'Carlos Pereira',
            'email' => 'carlos.pereira@email.com',
            'phone' => '123123123',
            'birth_date' => '1992-03-03',
            'address' => 'Rua DEF, 789',
            'complement' => 'Bloco B',
            'neighborhood' => 'Vila Mariana',
            'zip_code' => '23456789',
        ];

        $customer = Customer::create($customerData);

        $updatedData = [
            'name' => 'Carlos Pereira Jr.',
            'address' => 'Rua DEF, 1011',
        ];

        $customer->update($updatedData);

        $this->assertEquals($updatedData['name'], $customer->fresh()->name);
        $this->assertEquals($updatedData['address'], $customer->fresh()->address);
    }

    #[Test]
    public function it_can_delete_a_customer()
    {
        $customerData = [
            'name' => 'Ana Souza',
            'email' => 'ana.souza@email.com',
            'phone' => '321321321',
            'birth_date' => '1993-04-04',
            'address' => 'Rua GHI, 101',
            'complement' => 'Casa 2',
            'neighborhood' => 'Santa Efigênia',
            'zip_code' => '34567890',
        ];

        $customer = Customer::create($customerData);
        $customer->delete(); // Soft delete

        $this->assertSoftDeleted($customer);
    }
}
