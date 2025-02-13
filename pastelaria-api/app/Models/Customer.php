<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'birth_date', 'address', 'complement', 'neighborhood', 'zip_code',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
