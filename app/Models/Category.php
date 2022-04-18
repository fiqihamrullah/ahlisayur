<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTimeInterface;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' 
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
