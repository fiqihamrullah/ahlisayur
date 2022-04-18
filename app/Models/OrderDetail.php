<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class OrderDetail extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }



}
