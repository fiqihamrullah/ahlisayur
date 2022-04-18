<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Customer extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->hasOne(CustomerAccount::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
