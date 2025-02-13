<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Customer extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'address',
        'complement',
        'neighborhood',
        'zip_code',
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function getBirthDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
