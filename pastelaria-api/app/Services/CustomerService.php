<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function createCustomer(array $data)
    {
        return Customer::create($data);
    }

    public function updateCustomer(Customer $customer, array $data)
    {
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer(Customer $customer)
    {
        $customer->delete();
    }
}
