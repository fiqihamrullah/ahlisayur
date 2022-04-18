<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Order extends Model
{
    use HasFactory;

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }



}
