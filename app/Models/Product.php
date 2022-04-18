<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'price', 'picture_path', 'unit'
    ];


    public function category()
    {
          return $this->belongsTo(Category::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(OrderDetail::class);
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    


    
}
