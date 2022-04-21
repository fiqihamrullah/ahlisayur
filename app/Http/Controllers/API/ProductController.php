<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\ResponseFormatter;
 
use App\Models\Product;
 

use Carbon\Carbon;

class ProductController extends Controller
{
     
      public function getProducts()
      {
        $products = Product::query()->get();
        return ResponseFormatter::success($products);
      }

}
