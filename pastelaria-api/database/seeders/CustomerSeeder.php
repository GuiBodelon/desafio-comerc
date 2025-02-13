<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Ana Oliveira',
            'email' => 'ana.oliveira@example.com',
            'phone' => '991234567',
            'birth_date' => '1982-06-23',
            'address' => 'Rua São Paulo, 321',
            'complement' => 'Casa',
            'neighborhood' => 'Jardim Paulista',
            'zip_code' => '01234567',
        ]);

        Customer::create([
            'name' => 'Carlos Santos',
            'email' => 'carlos.santos@example.com',
            'phone' => '998765432',
            'birth_date' => '1991-11-15',
            'address' => 'Avenida Rio, 456',
            'complement' => 'Apto 202',
            'neighborhood' => 'Centro',
            'zip_code' => '23456789',
        ]);

        Customer::create([
            'name' => 'Juliana Almeida',
            'email' => 'juliana.almeida@example.com',
            'phone' => '977654321',
            'birth_date' => '1988-08-10',
            'address' => 'Rua dos Três Irmãos, 789',
            'complement' => 'Bloco A',
            'neighborhood' => 'Bairro Novo',
            'zip_code' => '34567890',
        ]);

        Customer::create([
            'name' => 'Felipe Lima',
            'email' => 'felipe.lima@example.com',
            'phone' => '966543210',
            'birth_date' => '1995-01-25',
            'address' => 'Rua Amazonas, 1010',
            'complement' => 'Casa 3',
            'neighborhood' => 'Vila Mariana',
            'zip_code' => '45678901',
        ]);

        Customer::create([
            'name' => 'Marcela Costa',
            'email' => 'marcela.costa@example.com',
            'phone' => '955432109',
            'birth_date' => '1993-03-30',
            'address' => 'Rua Pernambuco, 2020',
            'complement' => 'Apto 301',
            'neighborhood' => 'Santa Efigênia',
            'zip_code' => '56789012',
        ]);
    }
}
