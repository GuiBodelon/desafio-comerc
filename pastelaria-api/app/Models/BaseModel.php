<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('d/m/Y H:i:s');
    }
}
