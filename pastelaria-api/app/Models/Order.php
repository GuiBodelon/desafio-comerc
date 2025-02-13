<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
    ];

    /**
     * O cliente que pertence ao pedido.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Os produtos que pertencem ao pedido.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product');
    }
}
